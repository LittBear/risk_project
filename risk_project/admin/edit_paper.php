<?php include('../php_process/db_connection.php'); ?>
<?php
// เริ่มต้น session
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (isset($_SESSION['id_card'])) {
    $id_card = $_SESSION['id_card']; // ดึงค่า username จาก session
} else {
    // ถ้าผู้ใช้ไม่ได้ล็อกอิน ให้ redirect ไปยังหน้า login
    header("Location: ../php_process/login.php");
    exit();
}
$sql = "SELECT id, image_path FROM images WHERE user_id_card = ? ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['id_card']); // หรือแทนที่กับตัวแปรที่เก็บ user_id ของผู้ใช้
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบผลลัพธ์
if ($result->num_rows > 0) {
    $images = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลมาเก็บในตัวแปร $images
} else {
    $images = []; // ถ้าไม่มีรูปภาพในฐานข้อมูล ให้ตั้งค่าตัวแปรเป็นอาร์เรย์ว่าง
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Management</title>
    <link rel="stylesheet" href="../css_all/edit_paper.css">
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
            <a href="../admin/admin.php">หน้าหลัก</a>

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
                        <a href="#">จัดการการทำแบบประเมิน</a>
                        <a href="../admin/edit_user.php">จัดการผู้ใช้งาน</a>
                        <a href="../php_process/logout.php">ออกจากระบบ</a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="container">
        <h1>ตั้งค่าหัวข้อและลิงก์</h1>
        <label for="department-admin">เลือกแผนก:</label>
        <select id="department-admin">
            <option value="">-- กรุณาเลือกแผนก --</option>
        </select>

        <div id="topics-admin-container" style="display: none;">
            <h2>หัวข้อและหัวข้อย่อย</h2>
            <form id="admin-form">
                <ul id="topics-admin-list"></ul>
                <button type="submit">บันทึก</button>
            </form>
        </div>
    </div>
    




    <!-- Note -->
    <!--<div class="note">
        <span>ข้อความ ไหล.............................................ต่อเนื่อง</span>
    </div>*/-->
    <!-- Footer -->
    <!-- <div class="footer">
        <a >Link HRMS</a>
        <a >แบบสำรวจและแบบประเมิน</a>
        <a >ประชาสัมพันธ์</a>
    </div>-->

    <script src="../js/paper_edit.js"></script>
</body>
</html>
