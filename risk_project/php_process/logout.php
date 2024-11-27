<?php
// เริ่มต้น session
session_start();

// ลบข้อมูล session ทั้งหมด
session_unset();

// ทำลาย session
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
header("Location: ../index.php");
exit();
?>
