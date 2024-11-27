<?php
include('../php_process/db_connection.php');
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['id_card'])) {
    header("Location: ../php_process/login.php");
    exit();
}

$sql = "SELECT image_path FROM images WHERE user_id_card = ? ORDER BY uploaded_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['id_card']);
$stmt->execute();
$result = $stmt->get_result();
$images = $result->fetch_all(MYSQLI_ASSOC);

$sql_note = "SELECT note_text FROM settings WHERE id = 1";
$result_note = $conn->query($sql_note);
$noteText = ($result_note && $result_note->num_rows > 0) ? $result_note->fetch_assoc()['note_text'] : "ข้อความเริ่มต้น...";

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
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css_all/admin.css">
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
           
            <a href="#">หน้าหลัก</a>

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
                <span class="user-icon">👤 <?= htmlspecialchars($_SESSION['user_type']) ?></span>
                <div class="dropdown">
                    <button class="dropdown-btn">เมนู ▼</button>
                    <div class="dropdown-content">
                        <a href="../admin/setting.php">การตั้งค่าหน้าเว็บ</a>
                        <a href="../admin/edit_paper.php">จัดการการทำแบบประเมิน</a>
                        <a href="../admin/edit_user.php">จัดการผู้ใช้งาน</a>
                        <a href="../php_process/logout.php">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="carousel">
        <?php foreach ($images as $index => $image): ?>
            <div class="carousel-item">
                <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="User Image">
            </div>
        <?php endforeach; ?>
    
        <!-- จุด (dots) สำหรับการเปลี่ยนภาพ -->
        <div class="dots">
            <?php foreach ($images as $index => $image): ?>
                <span class="dot"></span>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="icon-links">
        <a href="https://msi.thai-nrls.org/">
            <div class="icon-item">
                <img src="../pic/hrms.png" alt="Icon Image">
                <p>HRMS</p>
            </div>
        </a>
        <a href="another-link-here">
            <div class="icon-item">
                <img src="../pic/paper.png" alt="Icon Image">
                <p>แบบสำรวจและแบบประเมิน</p>
            </div>
        </a>
    </div>

    <!-- Note -->
    <div class="note">
        <span id="noteText"><?= htmlspecialchars($noteText) ?></span>
    </div>

                
    <!-- Footer -->
    <div class="footer">
        <a >ติดปัญหาติดต่อเรา : </a>
        <a >คุณ ต่อศักดิ์  ตาวงค์ กลุ่มงาน เทคนิการแพทย์ โทร.113  |</a>
        <a >คุณ ธันว์ชนก  ทะนะวงค์ กลุ่มงาน แพทย์แผนไทย โทร.233 |</a>
        <a >คุณ ณรงค์  อวดเหลี้ยม กลุ่มงาน เวชกรรมฟื้นฟู โทร.150||</a>
        <a>จำนวนคนที่ออนไลน์: <?php echo $online_users; ?></a>
    </div>


    <script src="../js/admin.js"></script>
</body>
</html>
