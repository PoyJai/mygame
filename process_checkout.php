<?php
session_start();
require_once 'db_config.php';

error_reporting(0); 
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        $response['message'] = 'ไม่พบข้อมูล JSON';
        echo json_encode($response);
        exit;
    }

    // เตรียมตัวแปรให้ตรงกับตารางใน SQL
    $fullname = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $full_addr = $data['address'] . " " . $data['province'] . " " . $data['zipcode'];
    $address = mysqli_real_escape_string($conn, $full_addr);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $total = (float)$data['total'];
    $username = $_SESSION['username'] ?? 'Guest';

    mysqli_begin_transaction($conn);

    try {
        // 1. บันทึกลงตาราง orders
        $sql_order = "INSERT INTO orders (username, fullname, email, address, phone, total_price, status) 
                      VALUES ('$username', '$fullname', '$email', '$address', '$phone', '$total', 'pending')";
        
        if (!mysqli_query($conn, $sql_order)) {
            throw new Exception(mysqli_error($conn));
        }
        
        $order_id = mysqli_insert_id($conn);

        // 2. บันทึกรายการสินค้าลง order_items (เพื่อให้ใบเสร็จมีรายชื่อเกม)
        if (isset($data['cart']) && is_array($data['cart'])) {
            foreach ($data['cart'] as $item) {
                $g_id = (int)$item['id'];
                $g_title = mysqli_real_escape_string($conn, $item['title']);
                $g_price = (float)$item['price'];
                
                $sql_item = "INSERT INTO order_items (order_id, game_id, game_title, price) 
                             VALUES ('$order_id', '$g_id', '$g_title', '$g_price')";
                mysqli_query($conn, $sql_item);
            }
        }

        mysqli_commit($conn);
        
        // ส่งค่ากลับไปให้ JavaScript ทำงานต่อ
        echo json_encode([
            'success' => true, 
            'order_id' => $order_id, 
            'total' => $total
        ]);
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $response['message'] = 'DB Error: ' . $e->getMessage();
    }
}

echo json_encode($response);