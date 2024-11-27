<?php
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

// ค้นหาจำนวนผู้ใช้ที่ล็อกอินในช่วง 10 นาทีล่าสุด
$sql = "SELECT COUNT(*) AS online_users FROM login_info WHERE login_time > NOW() - INTERVAL 10 MINUTE";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$online_users = $row['online_users'];

echo "จำนวนคนที่ออนไลน์: " . $online_users;

$conn->close();
?>
