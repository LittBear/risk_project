<?php
// กำหนดค่าการเชื่อมต่อฐานข้อมูล
$servername = "61.19.146.46"; // หรือใส่ IP Address ของเซิร์ฟเวอร์
$username = "root"; // ชื่อผู้ใช้ MySQL
$password = "msh11195"; // รหัสผ่านของ MySQL
$dbname = "risk_db"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT COUNT(*) AS online_users FROM login_info WHERE login_time > NOW() - INTERVAL 10 MINUTE";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$online_users = $row['online_users'];

$sql_note = "SELECT note_text FROM settings WHERE id = 1";
$result_note = $conn->query($sql_note);
$noteText = ($result_note && $result_note->num_rows > 0) ? $result_note->fetch_assoc()['note_text'] : "ข้อความเริ่มต้น...";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Management</title>
    <link rel="stylesheet" href="css_all/index.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        
        <div class="menu">
            
            <div class="icon">
                <img src="pic\ic1.png" alt="Icon"  width="60px" height="60px" >
                <div class="text">
                    <h1>โรงพยาบาลแม่สรวย</h1>
                    <a>MAESUAI HOSPITAL</a>
                </div>
            </div>

            <a href="index.php">หน้าหลัก</a>
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
            <a href="#" onclick="showLoginModal()">เข้าสู่ระบบ</a>
        </div>
    </div>

    <!-- Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="hideLoginModal()">&times;</span>
            <h1>เข้าสู่ระบบ</h1>
            <form action="php_process/login.php" method="POST" onsubmit="return validateLogin()">
                <label for="id_card">รหัสบัตรประชาชน:</label>
                <input type="text" id="id_card" name="id_card" maxlength="13" required>
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" class="btn">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>


    <!-- Content -->
    <div class="content">
        <h1>Risk Management</h1>
        <p>งานบริหารและจัดการความเสี่ยง โรงพยาบาลแม่สรวย</p>
        
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


    <script src="js/index.js"></script>
</body>
</html>
