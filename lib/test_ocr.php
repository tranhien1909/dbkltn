<?php
require_once __DIR__ . '/kb_ingest.php';

// Đường dẫn file cần đọc
// $file = "C:/Users/Admin/Downloads/TB867.pdf"; // hoặc ảnh .jpg/.png
$file = "C:\\Users\\Admin\\Downloads\\TB867.pdf";

try {
    // Tự động nhận dạng loại file
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'pdf':
            $text = kb_parse_pdf($file);
            break;
        case 'docx':
            $text = kb_parse_docx($file);
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
            $text = kb_parse_image($file);
            break;
        default:
            throw new Exception("Định dạng $ext chưa được hỗ trợ");
    }

    echo "✅ Trích xuất thành công!\n\n";
    echo "===== KẾT QUẢ OCR =====\n";
    echo mb_substr($text, 0, 2000) . "\n"; // in thử 2000 ký tự đầu

} catch (Throwable $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
