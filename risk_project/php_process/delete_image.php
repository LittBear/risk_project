<?php
// เชื่อมต่อฐานข้อมูล
include('../php_process/db_connection.php');

// ตรวจสอบว่าได้ส่ง id มาหรือไม่
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // ป้องกัน SQL Injection โดยการใช้ Prepared Statements
    $stmt = $conn->prepare("SELECT * FROM images WHERE id = ?");
    $stmt->bind_param("i", $id);  // "i" สำหรับตัวแปรที่เป็น integer
    $stmt->execute();
    $result = $stmt->get_result();
    $image = $result->fetch_assoc();

    // หากพบรูปภาพในฐานข้อมูล
    if ($image) {
        $imagePath = $image['image_path'];
        
        // ลบไฟล์รูปภาพในเซิร์ฟเวอร์
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                // ลบข้อมูลจากฐานข้อมูล
                $deleteStmt = $conn->prepare("DELETE FROM images WHERE id = ?");
                $deleteStmt->bind_param("i", $id);
                if ($deleteStmt->execute()) {
                    echo "ลบรูปภาพสำเร็จ";
                } else {
                    echo "เกิดข้อผิดพลาดในการลบข้อมูลจากฐานข้อมูล";
                }
            } else {
                echo "ไม่สามารถลบไฟล์ได้";
            }
        } else {
            echo "ไม่พบไฟล์ที่ต้องการลบ";
        }
    } else {
        echo "ไม่พบรูปภาพที่ต้องการลบ";
    }
} else {
    echo "ไม่มีการส่งค่า id มา";
}
?>
