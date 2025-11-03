<?php
// tools/auto_hide.php  
// CLI: php tools/auto_hide.php  
// Chức năng: Chỉ ẩn các comment có rủi ro cao, KHÔNG reply  

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/fb_graph.php';
require_once __DIR__ . '/../lib/openai_client.php';

// Helper functions  
function already_hidden($commentId)
{
    $st = db()->prepare('SELECT 1 FROM auto_actions WHERE object_id=? AND action="hidden" LIMIT 1');
    $st->execute([$commentId]);
    return (bool)$st->fetchColumn();
}

function mark_hidden($commentId, $risk, $reason)
{
    $st = db()->prepare('INSERT IGNORE INTO auto_actions(object_id,object_type,action,risk,reason) VALUES (?,?,?,?,?)');
    $st->execute([$commentId, 'comment', 'hidden', $risk, $reason]);
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
$pageId    = envv('FB_PAGE_ID');

$since = time() - $winMin * 60;
$processed = 0;

echo "AutoHide: window {$winMin}m, threshold {$threshold}\n";

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

            // Bỏ qua nếu đã ẩn trước đó  
            if (already_hidden($cid)) continue;

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

            // Thực hiện hide  
            try {
                fb_hide_comment($cid, true);
                mark_hidden($cid, $risk, 'auto_hide');
                echo "Hidden $cid (risk=$risk)\n";
                $processed++;
                usleep(600000); // 0.6s tránh rate limit  
            } catch (Throwable $eAct) {
                $reason = (strpos($eAct->getMessage(), '1446036') !== false)
                    ? 'spam_blocked'
                    : 'hide_error:' . substr($eAct->getMessage(), 0, 120);
                mark_skipped($cid, $risk, $reason);
                echo "Hide error on $cid: " . $eAct->getMessage() . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Fatal: " . $e->getMessage() . "\n";
}

echo "Done. hidden=$processed\n";
