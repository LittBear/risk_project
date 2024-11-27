<?php
include('db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_card = $_POST['id_card'];
    $user_type = $_POST['user_type'];
    $password = isset($_POST['password']) && !empty($_POST['password']) ? md5($_POST['password']) : null;


    // อัปเดตข้อมูลผู้ใช้
    if ($password) {
        $sql = "UPDATE users SET user_type = ?, password = ? WHERE id_card = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user_type, $password, $id_card);
    } else {
        $sql = "UPDATE users SET user_type = ? WHERE id_card = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user_type, $id_card);
    }

    if ($stmt->execute()) {
        header("Location: ../admin/edit_user.php?success=1");
    } else {
        header("Location: ../admin/edit_user.php?error=1");
    }
    exit();
}
?>
