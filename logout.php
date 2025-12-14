<?php
// 1. เริ่ม Session (สำคัญมาก เพราะต้องเข้าถึง Session ที่กำลังใช้งานอยู่)
session_start();

// 2. ล้างตัวแปร Session ทั้งหมด
$_SESSION = array();

// 3. ทำลาย Session บนเซิร์ฟเวอร์
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    // ลบคุกกี้ Session ออกจากเครื่องผู้ใช้
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
} 
session_destroy();

// 4. นำไปยังหน้าเข้าสู่ระบบ (Login Page)
header("location: login.php");
exit; // หยุดการทำงานของสคริปต์ทันที
?>