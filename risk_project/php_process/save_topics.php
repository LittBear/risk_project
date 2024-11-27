<?php
// อ่านข้อมูลจาก JSON
$data = json_decode(file_get_contents("php://input"), true);
$department = $data['department'];
$topics = $data['topics'];

// สร้างไฟล์ JSON เพื่อเก็บข้อมูล
$file_path = "../php_process/topics.json";
if (!file_exists($file_path)) {
    file_put_contents($file_path, json_encode([]));
}

// อ่านข้อมูลเก่าจากไฟล์
$existing_data = json_decode(file_get_contents($file_path), true);
$existing_data[$department] = $topics;

// บันทึกข้อมูลใหม่ลงไฟล์
file_put_contents($file_path, json_encode($existing_data));

echo json_encode(["message" => "บันทึกข้อมูลสำเร็จ!"]);
?>
