<?php
// กำหนดค่าเชื่อมต่อฐานข้อมูล
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // เปลี่ยนเป็น Username ของคุณ
define('DB_PASSWORD', ''); // เปลี่ยนเป็น Password ของคุณ
define('DB_NAME', 'aesthetic_games_db'); // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ MySQLi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("ERROR: ไม่สามารถเชื่อมต่อฐานข้อมูลได้. " . $conn->connect_error);
}

// ตั้งค่า charset เป็น utf8mb4
$conn->set_charset("utf8mb4");
?>