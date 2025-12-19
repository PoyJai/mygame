<?php
session_start();
include('server.php');

if (isset($_POST['reset_password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $p1 = mysqli_real_escape_string($conn, $_POST['password_1']);
    $p2 = mysqli_real_escape_string($conn, $_POST['password_2']);

    // 1. ตรวจสอบความถูกต้องเบื้องต้น
    if (empty($username) || empty($p1) || empty($p2)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
        header("location: forgot_password.php");
        exit();
    }

    if ($p1 !== $p2) {
        $_SESSION['error'] = "รหัสผ่านทั้งสองช่องไม่ตรงกัน";
        header("location: forgot_password.php");
        exit();
    }

    // 2. ตรวจสอบว่ามีชื่อผู้ใช้นี้จริงไหม
    $sql_check = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        
        // 3. สำคัญ: เข้ารหัสแบบเดียวกันกับที่หน้า Login ต้องการ (BCRYPT)
        $new_password_hashed = password_hash($p1, PASSWORD_DEFAULT);
        
        $sql_update = "UPDATE users SET password = '$new_password_hashed' WHERE username = '$username'";
        
        if (mysqli_query($conn, $sql_update)) {
            $_SESSION['success'] = "เปลี่ยนรหัสผ่านสำเร็จ! กรุณาลองเข้าสู่ระบบ";
            header("location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
            header("location: forgot_password.php");
        }
    } else {
        $_SESSION['error'] = "ไม่พบชื่อผู้ใช้งานนี้ในระบบ";
        header("location: forgot_password.php");
    }
} else {
    header("location: forgot_password.php");
}
?>