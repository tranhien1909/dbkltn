<?php
// tools/auto_reply.php  
// CLI: php tools/auto_reply.php  
// Chức năng: Chỉ comment tự động vào các comment có rủi ro cao, KHÔNG ẩn  

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/fb_graph.php';
require_once __DIR__ . '/../lib/openai_client.php';

// Helper functions  
function already_replied($commentId)
{
    $st = db()->prepare('SELECT 1 FROM auto_actions WHERE object_id=? AND action="replied" LIMIT 1');
    $st->execute([$commentId]);
    return (bool)$st->fetchColumn();
}

function mark_replied($commentId, $risk, $reason, $reply)
{
    $st = db()->prepare('INSERT IGNORE INTO auto_actions(object_id,object_type,action,risk,reason,response_text) VALUES (?,?,?,?,?,?)');
    $st->execute([$commentId, 'comment', 'replied', $risk, $reason, $reply]);
}

function mark_skipped($commentId, $risk, $reason)
{
    $st = db()->prepare('INSERT IGNORE INTO auto_actions(object_id,object_type,action,risk,reason) VALUES (?,?,?,?,?)');
    $st->execute([$commentId, 'comment', 'skipped', $risk, $reason]);
}

// Configuration  
$winMin    = (int) envv('AUTO_SCAN_WINDOW_MINUTES', 60);
$maxBatch  = (int) envv('AUTO_MAX_COMMENTS_PER_RUN', 50);
$threshold = (int) envv('AUTO_RISK_THRESHOLD', 60);
$prefix    = envv('AUTO_REPLY_PREFIX', '⚠️[BQT]');
$pageId    = envv('FB_PAGE_ID');

$since = time() - $winMin * 60;
$processed = 0;

echo "AutoReply: window {$winMin}m, threshold {$threshold}, prefix={$prefix}\n";

try {
    $posts = fb_get_page_posts_since($since, 25);
    foreach (($posts['data'] ?? []) as $p) {
        $postId = $p['id'];
        $comments = fb_get_post_comments_since($postId, $since, 100);

        foreach (($comments['data'] ?? []) as $c) {
            if ($processed >= $maxBatch) {
                echo "Reached batch limit\n";
                break 2;
            }

            $cid   = $c['id'];
            $from  = $c['from']['id'] ?? '';
            $msg   = trim($c['message'] ?? '');
            if ($msg === '') continue;

            // Bỏ qua comment của chính page  
            if ($from && $pageId && $from === $pageId) continue;

            // Bỏ qua nếu đã reply trước đó  
            if (already_replied($cid)) continue;

            // Phân tích  
            try {
                $res = analyze_text_with_schema($msg);
            } catch (Throwable $e) {
                mark_skipped($cid, 0, 'analyze_error:' . substr($e->getMessage(), 0, 120));
                continue;
            }

            $risk = (int)($res['overall_risk'] ?? 0);

            // Chỉ hành động nếu vượt ngưỡng  
            if ($risk < $threshold) {
                mark_skipped($cid, $risk, 'under_threshold');
                continue;
            }

            // Quyết định nội dung trả lời theo nhãn  
            $labels = $res['labels'] ?? [];
            if (!empty($labels['scam_phishing'])) {
                $template = "Cảnh báo: Có dấu hiệu mời chào/lừa đảo hoặc liên hệ ngoài nền tảng. Vui lòng cảnh giác, không cung cấp thông tin cá nhân hay chuyển tiền. Nếu có bằng chứng xác thực, vui lòng chia sẻ nguồn.";
            } elseif (!empty($labels['hate_speech'])) {
                $template = "Nhắc nhở: Xin giữ trao đổi văn minh, tránh lời lẽ xúc phạm/công kích cá nhân. Hãy tập trung vào thông tin và nguồn xác thực.";
            } elseif (!empty($labels['misinformation'])) {
                $template = "Lưu ý: Nội dung có thể chưa đủ nguồn xác thực. Vui lòng bổ sung đường dẫn đến nguồn tin cậy (cơ quan báo chí chính thống, công bố chính thức).";
            } else {
                $template = "Lưu ý: Nội dung có rủi ro gây hiểu nhầm. Vui lòng kiểm chứng và sử dụng ngôn từ phù hợp.";
            }
            $reply = $prefix . ' ' . $template;

            // Thực hiện reply  
            try {
                fb_comment($cid, $reply);
                mark_replied($cid, $risk, 'auto_reply', $reply);
                echo "Replied to $cid (risk=$risk)\n";
                $processed++;
                usleep(600000); // 0.6s tránh rate limit  
            } catch (Throwable $eAct) {
                $reason = (strpos($eAct->getMessage(), '1446036') !== false)
                    ? 'spam_blocked'
                    : 'reply_error:' . substr($eAct->getMessage(), 0, 120);
                mark_skipped($cid, $risk, $reason);
                echo "Reply error on $cid: " . $eAct->getMessage() . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Fatal: " . $e->getMessage() . "\n";
}

echo "Done. replied=$processed\n";
