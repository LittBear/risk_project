<?php
include('../php_process/db_connection.php');
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['id_card'])) {
    header("Location: ../php_process/login.php");
    exit();
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$id_card = $_SESSION['id_card'];
$sql_user = "SELECT id_card, full_name, jt_name FROM users WHERE id_card = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $id_card);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();

// ข้อความที่สามารถแก้ไขได้จากฐานข้อมูล
$sql_note = "SELECT note_text FROM settings WHERE id = 1";
$result_note = $conn->query($sql_note);
$noteText = ($result_note && $result_note->num_rows > 0) ? $result_note->fetch_assoc()['note_text'] : "ข้อความเริ่มต้น...";

// คำนวณจำนวนผู้ใช้งานที่ออนไลน์
$sql = "SELECT COUNT(*) AS online_users FROM login_info WHERE login_time > NOW() - INTERVAL 10 MINUTE";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$online_users = $row['online_users'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้ใช้</title>
    <link rel="stylesheet" href="../css_all/profile.css">
    <script src="../js/user.js"></script>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="menu">
            <div class="icon">
                <img src="..\pic\ic1.png" alt="Icon"  width="60px" height="60px" >
                <div class="text">
                    <h1>โรงพยาบาลแม่สรวย</h1>
                    <a>MAESUAI HOSPITAL</a>
                </div>
            </div>
           
            <a href="../user/user.php">หน้าหลัก</a>

            <div class="dropdown0">
                <a >งานบริหารและจัดการความเสี่ยง</a>
                <div class="dropdown-content0">
                    <a href="#">โครงสร้าง | คณะกรรมการแต่งตั้ง</a>
                    <a href="#">นโยบายความปลอดภัย</a>
                    <a href="#">นโยบายความเสี่ยง</a>
                </div>
            </div>

            <div class="dropdown1">
                <a >RM Knowledge</a>
                <div class="dropdown-content1">
                    <a href="#">คู่มือความเสี่ยง โรงพยาบาลแม่สรวย</a>
                    <a href="#">ฟอร์มเอกสาร RM</a>
                    <a href="#">คู่มือการใช้งาน HRMS ปี 2568</a>
                    <a href="#">บัญชีอุบัติการความเสี่ยง ปี 2568</a>
                </div>
            </div>

            <div class="user-menu">
                <span class="user-icon">👤 <?= htmlspecialchars($_SESSION['full_name']) ?></span>
                 <div class="dropdown">
                    <button class="dropdown-btn">เมนู ▼</button>
                        <div class="dropdown-content">
                        <a href="../user/profile.php">ข้อมูลผู้ใช้</a>
                        <a href="../php_process/logout.php">ออกจากระบบ</a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Note -->
    <div class="note">
        <span id="noteText"><?= htmlspecialchars($noteText) ?></span>
    </div>

    <!-- ข้อมูลผู้ใช้ -->
    <div class="user-info">
        <h2>ข้อมูลผู้ใช้</h2>
        <p><strong>ID Card:</strong> <?= htmlspecialchars($user_data['id_card']) ?></p>
        <p><strong>ชื่อเต็ม:</strong> <?= htmlspecialchars($user_data['full_name']) ?></p>
        <p><strong>ตำแหน่ง:</strong> <?= htmlspecialchars($user_data['jt_name']) ?></p>
    </div>

    <!-- ฟอร์มเปลี่ยนรหัสผ่าน -->
    <div class="change-password">
        <h2>เปลี่ยนรหัสผ่าน</h2>
        <form action="../php_process/change_password.php" method="POST">
            <label for="current_password">รหัสผ่านปัจจุบัน:</label>
            <input type="password" id="current_password" name="current_password" required><br><br>

            <label for="new_password">รหัสผ่านใหม่:</label>
            <input type="password" id="new_password" name="new_password" required><br><br>

            <label for="confirm_password">ยืนยันรหัสผ่านใหม่:</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>

            <button type="submit">เปลี่ยนรหัสผ่าน</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <a>ติดปัญหาติดต่อเรา : </a>
        <a>คุณ ต่อศักดิ์  ตาวงค์ กลุ่มงาน เทคนิการแพทย์ โทร.113 |</a>
        <a>คุณ ธันว์ชนก  ทะนะวงค์ กลุ่มงาน แพทย์แผนไทย โทร.233 |</a>
        <a>คุณ ณรงค์  อวดเหลี้ยม กลุ่มงาน เวชกรรมฟื้นฟู โทร.150 ||</a>
        <a>จำนวนคนที่ออนไลน์: <?= $online_users ?></a>
    </div>
</body>
</html>
