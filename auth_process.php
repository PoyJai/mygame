<?php
session_start();
header('Content-Type: application/json');

// นำเข้าการตั้งค่าฐานข้อมูล
require_once 'db_config.php';

$response = ['success' => false, 'message' => 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

// รับข้อมูลจาก AJAX (JSON body)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$mode = $data['mode'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$requestTester = $data['requestTester'] ?? false;

// ตรวจสอบข้อมูลเบื้องต้น
if (empty($email) || empty($password)) {
    $response['message'] = 'กรุณากรอกอีเมลและรหัสผ่านให้ครบถ้วน.';
    echo json_encode($response);
    exit;
}

$email = $conn->real_escape_string($email);
$username = explode('@', $email)[0]; // ใช้ส่วนแรกของอีเมลเป็น username ชั่วคราว

if ($mode === 'signup') {
    // ******************** สมัครสมาชิก (Sign Up) ********************
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password_hash, is_tester) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $password_hash, $requestTester);

    if ($stmt->execute()) {
        $response = [
            'success' => true, 
            'message' => 'สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ.',
            'userId' => $stmt->insert_id // หรือใช้ email แทนในกรณีที่จำเป็น
        ];
    } else {
        // ตรวจสอบว่าเกิดจาก Email ซ้ำหรือไม่ (Error code 1062 คือ Duplicate entry)
        if ($conn->errno === 1062) {
            $response['message'] = 'อีเมลนี้มีผู้ใช้งานแล้ว.';
        } else {
            $response['message'] = 'เกิดข้อผิดพลาดในการสมัครสมาชิก: ' . $stmt->error;
        }
    }
    $stmt->close();

} elseif ($mode === 'login') {
    // ******************** เข้าสู่ระบบ (Log In) ********************
    $sql = "SELECT id, username, password_hash FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password_hash'])) {
            // สร้าง Session
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $user['id'];
            $_SESSION["username"] = $user['username'];
            
            $response = [
                'success' => true, 
                'message' => 'เข้าสู่ระบบสำเร็จ!',
                'redirect' => 'allgame.php' // ให้ Browser ทำการ Redirect
            ];
        } else {
            $response['message'] = 'รหัสผ่านไม่ถูกต้อง.';
        }
    } else {
        $response['message'] = 'ไม่พบอีเมลในระบบ.';
    }
    $stmt->close();

} else {
    $response['message'] = 'Invalid mode specified.';
}

$conn->close();
echo json_encode($response);
?>