<?php
session_start();
header('Content-Type: application/json');

// ตรวจสอบและสร้างตะกร้าสินค้าใน Session ถ้ายังไม่มี
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = ['success' => false, 'message' => 'Invalid action', 'cart' => $_SESSION['cart']];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $action = $data['action'] ?? '';
    $game_id = (int)($data['id'] ?? 0);
    $title = $data['title'] ?? '';

    // --- Action: เพิ่มสินค้าเข้าตะกร้า ---
    if ($action === 'add' && $game_id > 0 && !empty($title)) {
        
        // ตรวจสอบว่ามีเกมนี้อยู่ในตะกร้าแล้วหรือไม่
        $found = false;
        foreach ($_SESSION['cart'] as $item) {
            if ($item['id'] === $game_id) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $game_id,
                'title' => htmlspecialchars($title),
                'price' => 199.00 // สมมติราคาเริ่มต้น 199 บาท
            ];
            $response['success'] = true;
            $response['message'] = "เพิ่ม '{$title}' เข้าตะกร้าเรียบร้อย";
        } else {
            $response['success'] = false;
            $response['message'] = "เกมนี้อยู่ในตะกร้าอยู่แล้ว";
        }
    }
    
    // --- Action: ลบสินค้าออกจากตะกร้า ---
    elseif ($action === 'remove' && $game_id > 0) {
        $initial_count = count($_SESSION['cart']);
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($game_id) {
            return $item['id'] !== $game_id;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']); // จัดเรียง index ใหม่
        
        if (count($_SESSION['cart']) < $initial_count) {
            $response['success'] = true;
            $response['message'] = "ลบสินค้าออกจากตะกร้าเรียบร้อย";
        } else {
            $response['success'] = false;
            $response['message'] = "ไม่พบสินค้าในตะกร้า";
        }
    }
    
    // --- Action: ดูสถานะตะกร้า (ใช้สำหรับอัพเดต UI) ---
    elseif ($action === 'get') {
        $response['success'] = true;
        $response['message'] = 'Current cart status';
    }
}

$response['cart'] = $_SESSION['cart']; // ส่งตะกร้าล่าสุดกลับไป
$response['total_items'] = count($_SESSION['cart']);
echo json_encode($response);
?>