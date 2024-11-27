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
    <link rel="stylesheet" href="../css_all/setting.css">
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
                        <a href="#">การตั้งค่าหน้าเว็บ</a>
                        <a href="../admin/edit_paper.php">จัดการการทำแบบประเมิน</a>
                        <a href="../admin/edit_user.php">จัดการผู้ใช้งาน</a>
                        <a href="../php_process/logout.php">ออกจากระบบ</a>
                    </div>
                </div>
            </div>


        </div>
    </div>
    
    <div class="upload-form">
        <h2>อัปโหลดรูปภาพ</h2>
        <form action="../php_process/upload_images.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="images[]" accept="image/*" multiple required>
            <button type="submit">อัปโหลด</button>
        </form>
    </div>

       <!-- แสดงรูปภาพที่อัปโหลด -->
    <div class="image-gallery">
        <h2>รายการรูปภาพที่อัปโหลด</h2>
        <ul>
            <?php foreach ($images as $image): ?>
             <li>
                    <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="User Image" width="150px" height="150px">
                    <button onclick="deleteImage(<?= $image['id'] ?>)">ลบ</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="note">
        <span id="noteText"><?= htmlspecialchars($noteText) ?></span> <!-- แสดงข้อความปัจจุบัน -->
        <button id="editNoteBtn">แก้ไข</button>
        <form id="noteForm" action="../php_process/update_note.php" method="POST" style="display: none;">
            <textarea name="noteText" id="noteTextarea" rows="3"><?= htmlspecialchars($noteText) ?></textarea>
            <button type="submit">บันทึก</button>
            <button type="button" id="cancelEditBtn">ยกเลิก</button>
        </form>
    </div>




    <!--
    <div class="note">
        <span>ข้อความ ไหล.............................................ต่อเนื่อง</span>
    </div> Note -->
    <!-- Footer -->
    <div class="footer">
        <a >Link HRMS</a>
        <a >แบบสำรวจและแบบประเมิน</a>
        <a >ประชาสัมพันธ์</a>
    </div>

    <script src="../js/setting.js"></script>
</body>
</html>
