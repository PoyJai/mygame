<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    
    // 1. จัดการเรื่องไฟล์รูปภาพ
    if (isset($_FILES['slip']) && $_FILES['slip']['error'] === 0) {
        $upload_dir = 'uploads/slips/'; // อย่าลืมสร้าง Folder นี้ในเครื่องด้วยนะครับ
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_ext = pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION);
        $new_file_name = "slip_" . $order_id . "_" . time() . "." . $file_ext;
        $upload_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($_FILES['slip']['tmp_name'], $upload_path)) {
            // 2. อัปเดตฐานข้อมูล
            $sql = "UPDATE orders SET slip_image = '$new_file_name', status = 'waiting_verify' WHERE id = '$order_id'";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>
                    alert('ส่งหลักฐานสำเร็จ! จะส่งใบเสร็จไปทางอีเมลครับ,คะ');
                    window.location.href = 'index.php';
                </script>";
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    }
}
?>