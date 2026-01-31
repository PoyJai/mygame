<?php
session_start();
include('server.php'); // ต้องมีไฟล์นี้สำหรับการเชื่อมต่อฐานข้อมูล

$errors = []; // เก็บข้อผิดพลาดทั้งหมด

if (isset($_POST['reg_user'])) {

    // รับค่าจากฟอร์ม
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    // เก็บข้อมูลชั่วคราว (เพื่อให้ผู้ใช้ไม่ต้องกรอกใหม่หากมี error)
    $_SESSION['temp_data'] = [
        'username' => $username,
        'email' => $email
    ];

    // ทำความสะอาด (Sanitize) ข้อมูล
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);

    // 1. ตรวจสอบความถูกต้องของข้อมูล
    if (empty($username)) $errors[] = "กรุณากรอกชื่อผู้ใช้งาน";
    if (empty($email)) $errors[] = "กรุณากรอกอีเมล";
    if (empty($password)) $errors[] = "กรุณากรอกรหัสผ่าน";
    if (strlen($password) < 6) $errors[] = "รหัสผ่านควรมีความยาวอย่างน้อย 6 ตัวอักษร";
    if ($password !== $confirm_password) $errors[] = "รหัสผ่านที่คุณกรอกไม่ตรงกัน";

    // 2. ตรวจสอบชื่อผู้ใช้งานและอีเมลซ้ำในฐานข้อมูล
    $user_check_query = "SELECT username, email FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $query = mysqli_query($conn, $user_check_query);

    // ตรวจสอบข้อผิดพลาดในการ Query
    if (!$query) {
        $_SESSION['error_messages'] = ["SQL Error: ไม่สามารถตรวจสอบข้อมูลผู้ใช้ได้ (" . mysqli_error($conn) . ")"];
        header("Location: register.php");
        exit;
    }

    $result = mysqli_fetch_assoc($query);

    if ($result) {
        if ($result['username'] === $username) $errors[] = "ชื่อผู้ใช้งานนี้มีคนใช้แล้ว กรุณาเลือกชื่อใหม่";
        if ($result['email'] === $email) $errors[] = "อีเมลนี้ได้ถูกลงทะเบียนแล้ว";
    }

    // 3. หากไม่มีข้อผิดพลาด ให้ลงทะเบียน
    if (count($errors) === 0) {
        
        // เข้ารหัสรหัสผ่าน (สำคัญมาก)
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // คำสั่ง SQL สำหรับบันทึกข้อมูล
        $sql_insert = "INSERT INTO users (username, email, password)
                       VALUES ('$username', '$email', '$password_hashed')";
        
        $insert_result = mysqli_query($conn, $sql_insert);

        // ตรวจสอบข้อผิดพลาดในการบันทึกข้อมูล
        if (!$insert_result) {
            $_SESSION['error_messages'] = ["SQL Error: ไม่สามารถบันทึกข้อมูลผู้ใช้ได้ (" . mysqli_error($conn) . ")"];
            header("Location: register.php");
            exit;
        }

        // 4. ลงทะเบียนสำเร็จ
        $_SESSION['success'] = "สมัครสมาชิกสำเร็จ! คุณสามารถเข้าสู่ระบบได้เลย";
        
        // ลบข้อมูลชั่วคราวและ errors
        unset($_SESSION['temp_data']); 
        
        // นำไปหน้าเข้าสู่ระบบ
        header("Location: login.php");
        exit;
    } 

    // 5. หากมีข้อผิดพลาด ให้นำกลับไปหน้าลงทะเบียนพร้อมแสดงข้อความผิดพลาด
    $_SESSION['error_messages'] = $errors;
    header("Location: register.php");
    exit;
} else {
    // กรณีเข้าถึงไฟล์นี้โดยตรงโดยไม่ผ่านฟอร์ม POST
    header("Location: register.php");
    exit;
}
?>