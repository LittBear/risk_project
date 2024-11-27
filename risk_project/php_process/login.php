<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$servername = "61.19.146.46";
$username = "root";
$password = "msh11195";
$dbname = "risk_db";;

$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจากฟอร์ม
$id_card = $_POST['id_card'];
$password = $_POST['password'];

// เตรียมคำสั่ง SQL
$sql = "SELECT * FROM users WHERE id_card = ?";
$stmt = $conn->prepare($sql);

// ตรวจสอบว่า prepare สำเร็จหรือไม่
if (!$stmt) {
    die("SQL error: " . $conn->error); // แสดงข้อผิดพลาด
}

$stmt->bind_param("s", $id_card);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่าพบผู้ใช้หรือไม่
if ($result->num_rows > 0) {
    // ดึงข้อมูลผู้ใช้
    $row = $result->fetch_assoc();

    // ตรวจสอบรหัสผ่าน
    if (md5($password) === $row['password']) {
        // ตั้งค่า session
        $_SESSION['id_card'] = $id_card;
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['user_type'] = $row['user_type'];

        // บันทึกข้อมูลการล็อกอิน
        $sql_login = "INSERT INTO login_info (id_card) VALUES ('$id_card')";
        if ($conn->query($sql_login) === TRUE) {
            echo "Login successfully recorded.";
        } else {
            echo "Error: " . $conn->error;
        }

        // แยกประเภทผู้ใช้
        if ($row['user_type'] === 'admin') {
            header("Location: /risk_project/admin/admin.php");
        } else if ($row['user_type'] === 'user') {
            header("Location: /risk_project/user/user.php");
        }
        exit();
    } else {
        // รหัสผ่านไม่ถูกต้อง
        echo "<script>alert('รหัสผ่านไม่ถูกต้อง');  window.history.back();</script>";
    }
} else {
    // id_card ไม่ถูกต้อง
    echo "<script>alert('เลขบัตรประชาชนไม่ถูกต้อง');  window.history.back();</script>";
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>
