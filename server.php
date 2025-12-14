<?php

    // กำหนดรายละเอียดการเชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $username_db = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
    $password_db = "";     // เปลี่ยนเป็นรหัสผ่านฐานข้อมูลของคุณ
    $dbname = "register_db"; // ชื่อฐานข้อมูลของคุณ

    // 1. สร้างการเชื่อมต่อ
    $conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

    // 2. ตรวจสอบการเชื่อมต่อ
    if (!$conn) {
        // หากเชื่อมต่อล้มเหลว ให้แสดงข้อความผิดพลาด
        die("Connection failed: " . mysqli_connect_error());
    }

    // 3. ตั้งค่า Charset เป็น utf8mb4 (สำคัญสำหรับภาษาไทย)
    if (!mysqli_set_charset($conn, "utf8mb4")) {
        die("Error loading character set utf8mb4: " . mysqli_error($conn));
    }

?>