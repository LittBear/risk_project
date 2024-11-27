<?php
$servername = "61.19.146.46";
$username = "root";
$password = "msh11195";
$dbname = "risk_db";

// เชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
} else {
    echo "เชื่อมต่อฐานข้อมูลสำเร็จ";
}



// ตรวจสอบการส่งข้อมูล
if (isset($_POST['noteText'])) {
    $noteText = $_POST['noteText'];

    // สุ่มเลือก id จากตาราง settings
    $sql = "UPDATE settings 
            SET note_text = ? 
            WHERE id = (SELECT id FROM settings ORDER BY RAND() LIMIT 1)"; 

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $noteText); // การเตรียมค่าพารามิเตอร์

    // ตรวจสอบการทำงาน
    if ($stmt->execute()) {
        echo "บันทึกสำเร็จ";
    } else {
        echo "ไม่สามารถบันทึกได้: " . $conn->error;
    }
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>
