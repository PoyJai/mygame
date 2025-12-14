<?php
session_start();
include('server.php'); 

// ถ้าเข้าสู่ระบบอยู่แล้ว นำไปที่หน้าหลักทันที
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

if (isset($_POST['login_user'])) {
    
    // รับค่าและทำความสะอาด
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 1. ตรวจสอบว่าผู้ใช้กรอกข้อมูลครบถ้วนหรือไม่
    if (empty($username)) {
        $_SESSION['error'] = "กรุณากรอกชื่อผู้ใช้งาน";
        header("location: login.php");
        exit;
    }
    if (empty($password)) {
        $_SESSION['error'] = "กรุณากรอกรหัสผ่าน";
        header("location: login.php");
        exit;
    }

    // 2. ค้นหาชื่อผู้ใช้งานในฐานข้อมูล
    $sql = "SELECT username, password FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql); 

    // ตรวจสอบข้อผิดพลาดของ SQL Query
    if ($result === FALSE) {
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการดึงข้อมูลฐานข้อมูล: " . mysqli_error($conn);
        header("location: login.php");
        exit();
    }
    
    // 3. ตรวจสอบจำนวนแถวที่ค้นหาได้
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // 4. ตรวจสอบรหัสผ่าน: นี่คือส่วนสำคัญที่ต้องใช้ password_verify()
        if (password_verify($password, $user['password'])) {
            
            // ✅ เข้าสู่ระบบสำเร็จ
            $_SESSION['loggedin'] = true; // ตั้งค่า Session Flag
            $_SESSION['username'] = $user['username']; // ตั้งค่า Session Username
            
            // นำไปยังหน้าหลัก (index.php)
            header("location: index.php");
            exit;

        } else {
            // รหัสผ่านไม่ถูกต้อง
            $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
            header("location: login.php");
            exit;
        }

    } else {
        // ไม่พบชื่อผู้ใช้งาน
        $_SESSION['error'] = "ไม่พบชื่อผู้ใช้งาน";
        header("location: login.php");
        exit;
    }
} else {
    header("location: login.php");
    exit;
}
?>