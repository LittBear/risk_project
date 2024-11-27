<?php
// เริ่มต้น session
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['id_card'])) {
    header("Location: ../php_process/login.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
include('db_connection.php');

// รับข้อมูลผู้ใช้จาก session
$id_card = $_SESSION['id_card'];

// ตรวจสอบว่ามีไฟล์อัปโหลด
if (!empty($_FILES['images']['name'][0])) {
    $uploadedImages = $_FILES['images'];
    $uploadDir = "../uploads/"; // โฟลเดอร์สำหรับเก็บไฟล์

    // สร้างโฟลเดอร์ถ้ายังไม่มี
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // ตรวจสอบจำนวนไฟล์ในฐานข้อมูล
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM images WHERE user_id_card = ?");
    $stmt->bind_param("s", $id_card);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $totalImages = $result['total'];

    if ($totalImages + count($uploadedImages['name']) > 5) {
        echo "<script>alert('คุณสามารถอัปโหลดได้สูงสุด 5 รูปเท่านั้น!'); window.location.href='../admin/setting.php';</script>";
        exit();
    }

    // อัปโหลดไฟล์
    foreach ($uploadedImages['name'] as $key => $imageName) {
        $tmpName = $uploadedImages['tmp_name'][$key];
        $filePath = $uploadDir . uniqid() . "_" . basename($imageName);

        if (move_uploaded_file($tmpName, $filePath)) {
            $stmt = $conn->prepare("INSERT INTO images (user_id_card, image_path) VALUES (?, ?)");
            $stmt->bind_param("ss", $id_card, $filePath);
            $stmt->execute();
        }
    }

    echo "<script>alert('อัปโหลดรูปภาพสำเร็จ!'); window.location.href='../admin/setting.php';</script>";
} else {
    echo "<script>alert('กรุณาเลือกไฟล์ก่อนอัปโหลด!'); window.location.href='../admin/setting.php';</script>";
}
?>