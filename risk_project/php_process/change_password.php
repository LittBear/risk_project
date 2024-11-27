<?php
include('../php_process/db_connection.php');
session_start();

if (!isset($_SESSION['id_card'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน.'); window.location.href = '../php_process/login.php';</script>";
    exit();
}

$id_card = $_SESSION['id_card'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    echo "<script>alert('รหัสผ่านใหม่ไม่ตรงกับรหัสผ่านที่ยืนยัน.'); window.location.href = '../user/profile.php';</script>";
    exit();
}

$sql_check = "SELECT password FROM users WHERE id_card = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $id_card);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row = $result_check->fetch_assoc();

if ($row && md5($current_password) === $row['password']) {
    $new_password_hashed = md5($new_password);
    $sql_update = "UPDATE users SET password = ? WHERE id_card = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $new_password_hashed, $id_card);

    if ($stmt_update->execute()) {
        echo "<script>alert('รหัสผ่านของคุณถูกเปลี่ยนเรียบร้อย.'); window.location.href = '../user/profile.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน.'); window.location.href = '../user/profile.php';</script>";
    }
} else {
    echo "<script>alert('รหัสผ่านปัจจุบันไม่ถูกต้อง.'); window.location.href = '../user/profile.php';</script>";
}

$conn->close();
?>
