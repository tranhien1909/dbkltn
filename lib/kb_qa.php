<?php
// lib/kb_qa.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/kb.php';   // đã có vi_norm(), kb_search_chunks_v2()

/**
 * Trả lời nhanh “điểm trúng tuyển … năm …” nếu tìm được trong KB (bảng PDF/ảnh đã OCR).
 * Trả về: ['answer' => string, 'citation' => ['url'=>..., 'date'=>..., 'title'=>...]] hoặc null nếu không match.
 */
function kb_answer_cutoff(PDO $pdo, string $q, array $opts = []): ?array
{
    // 1) nhận diện năm + tên ngành (cho phép sai “quản trị/quản lý”)
    $norm = vi_norm($q); // dùng hàm sẵn có trong kb.php
    preg_match('/\b(20\d{2}|19\d{2})\b/u', $norm, $m);
    $year = $m[1] ?? date('Y');

    // ý định câu hỏi
    $intent = preg_match('/(điểm\s*(trúng|chuẩn|xét)\s*tuyển|điểm\s*sàn)/u', $norm);
    if (!$intent) return null;

    // lấy “tên ngành” còn lại sau khi trừ các từ khóa
    $normMajor = trim(preg_replace('/\b(điểm|trúng|chuẩn|xét|tuyển|sàn|' . preg_quote($year, '/') . ')\b/u', ' ', $norm));
    if ($normMajor === '') $normMajor = $norm;

    // 2) ưu tiên tài liệu IUH Official
    $hits = kb_search_chunks_v2($pdo, 'điểm trúng tuyển ' . $normMajor . ' ' . $year, 12, [
        'source'    => 'IUH Official',
        'trust_min' => (float)($opts['trust_min'] ?? 0.85),
        'days'      => (int)($opts['days'] ?? 900),
    ]);
    if (!$hits) return null;

    // gom theo post → lấy bài tốt nhất (mới nhất)
    $byPost = [];
    foreach ($hits as $h) {
        $pid = (int)$h['post_id'];
        if (!isset($byPost[$pid])) $byPost[$pid] = ['meta' => $h, 'text' => ''];
        $byPost[$pid]['text'] .= "\n" . ($h['text_clean'] ?? $h['text'] ?? '');
    }
    usort(
        $byPost,
        fn($a, $b) =>
        strtotime($b['meta']['created_time'] ?? '1970-01-01') <=> strtotime($a['meta']['created_time'] ?? '1970-01-01')
    );

    // 3) regex linh hoạt bắt 4 cột điểm (TN, ĐGNL 1200, ĐGNL 30, Kết hợp)
    $rxMajor = '(qu[aă]n\s*(?:l[ýy]|tr[ịi])\s*x[âa]y\s*d[ựu]ng|' . preg_quote($normMajor, '/') . ')';
    $rx = '/(?P<name>' . $rxMajor . ').{0,120}?'
        . '(?P<TN>\d{1,2}(?:[.,]\d{1,2})?)\s+'
        . '(?P<DGNL1200>\d{3,4})\s+'
        . '(?P<DGNL30>\d{1,2}(?:[.,]\d{1,2})?)\s+'
        . '(?P<KH>\d{1,2}(?:[.,]\d{1,2})?)/isu';

    foreach ($byPost as $item) {
        $txt = $item['text'];
        if (preg_match($rx, $txt, $m)) {
            $title = $item['meta']['title'] ?? '';
            $date  = $item['meta']['created_time'] ?? '';
            $url   = $item['meta']['permalink_url'] ?? '';

            // chuẩn hoá dấu phẩy → chấm
            $fmt = fn($v) => str_replace(',', '.', $v);

            $answer = sprintf(
                "Điểm trúng tuyển **%s** năm **%s**:\n- TN: **%s**\n- ĐGNL (thang 1200): **%s**\n- ĐGNL (thang 30): **%s**\n- Xét kết hợp: **%s**",
                trim($m['name']),
                $year,
                $fmt($m['TN']),
                $m['DGNL1200'],
                $fmt($m['DGNL30']),
                $fmt($m['KH'])
            );
            return [
                'answer'   => $answer,
                'citation' => ['url' => $url, 'date' => $date, 'title' => $title],
            ];
        }
    }
    return null;
}
