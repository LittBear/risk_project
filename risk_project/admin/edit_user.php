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

$sql = "SELECT id_card, user_type, full_name, jt_name FROM users ORDER BY full_name ASC";
$result = $conn->query($sql);

$items_per_page = 20; // จำนวนผู้ใช้ต่อหน้า
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // ต้องไม่น้อยกว่าหน้าแรก
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// คำนวณ offset สำหรับการแบ่งหน้า
$offset = ($page - 1) * $items_per_page;

// เตรียมคำสั่ง SQL
$sql = "SELECT id_card, user_type, full_name, jt_name 
        FROM users 
        WHERE full_name LIKE ? OR id_card LIKE ?
        ORDER BY full_name ASC 
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$search_param = "%$search_query%";
$stmt->bind_param("ssii", $search_param, $search_param, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// ดึงข้อมูลผู้ใช้
$users = $result->fetch_all(MYSQLI_ASSOC);

// นับจำนวนทั้งหมดสำหรับการสร้างปุ่มแบ่งหน้า
$count_sql = "SELECT COUNT(*) AS total 
              FROM users 
              WHERE full_name LIKE ? OR id_card LIKE ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("ss", $search_param, $search_param);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_users = $count_result->fetch_assoc()['total'];

// คำนวณจำนวนหน้าทั้งหมด
$total_pages = ceil($total_users / $items_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Management</title>
    <link rel="stylesheet" href="../css_all/edu.css">
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
                        <a href="../admin/edit_paper.php">จัดการการทำแบบประเมิน</a>
                        <a href="#">จัดการผู้ใช้งาน</a>
                        <a href="../php_process/logout.php">ออกจากระบบ</a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="content">
        <h2>จัดการผู้ใช้งาน</h2>
    <!-- ฟอร์มค้นหา -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="ค้นหาจากชื่อหรือรหัสบัตรประชาชน" value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit">ค้นหา</button>
        </form>

    <!-- ตารางข้อมูลผู้ใช้ -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>รหัสบัตรประชาชน</th>
                    <th>ประเภทผู้ใช้</th>
                    <th>ชื่อเต็ม</th>
                    <th>ตำแหน่งงาน</th>
                    <th>แก้ไข</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id_card']) ?></td>
                            <td><?= htmlspecialchars($user['user_type']) ?></td>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['jt_name']) ?></td>
                            <td>
                                <button onclick="openEditModal('<?= htmlspecialchars($user['id_card']) ?>', '<?= htmlspecialchars($user['user_type']) ?>')">แก้ไข</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">ไม่มีข้อมูลผู้ใช้งาน</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <!-- ปุ่มแบ่งหน้า -->
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search_query) ?>">ย้อนกลับ</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search_query) ?>" class="<?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search_query) ?>">ถัดไป</a>
            <?php endif; ?>
        </div>
    </div>


    <!-- Modal สำหรับแก้ไขผู้ใช้ -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form id="editUserForm" method="POST" action="../php_process/update_user.php">
                <h3>แก้ไขข้อมูลผู้ใช้</h3>
                <input type="hidden" id="editIdCard" name="id_card">
                <label for="editUserType">ประเภทผู้ใช้:</label>
                <select id="editUserType" name="user_type" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <label for="editPassword">รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน):</label>
                <input type="password" id="editPassword" name="password" placeholder="รหัสผ่าน">
                <button type="submit">บันทึก</button>
            </form>
        </div>
    </div>
   
    




    <!-- 
    <div class="note">
        <span>ข้อความ ไหล.............................................ต่อเนื่อง</span>
    </div>
  
    <div class="footer">
        <a >Link HRMS</a>
        <a >แบบสำรวจและแบบประเมิน</a>
        <a >ประชาสัมพันธ์</a>
    </div>-->

    <script src="../js/edit_user.js"></script>
</body>
</html>
