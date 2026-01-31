<?php
require_once 'db_config.php';
$order_id = isset($_GET['order_id']) ? mysqli_real_escape_string($conn, $_GET['order_id']) : '0';
$total = isset($_GET['total']) ? $_GET['total'] : '0';

// ดึงรายการสินค้าเพื่อมาแสดงในใบเสร็จ
$items_query = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = '$order_id'");
$promptpay_id = "1629400028337"; 
$qr_url = "https://promptpay.io/$promptpay_id/$total.png";
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จและชำระเงิน #<?= $order_id ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Mitr', sans-serif; background-color: #FFFDF9; background-image: radial-gradient(#000 0.5px, transparent 0.5px); background-size: 20px 20px; }
        .toy-card { background: white; border: 4px solid #000; box-shadow: 10px 10px 0px #000; border-radius: 2rem; }
        .receipt-line { border-top: 4px dashed #000; }
        .bg-toy-pink { background-color: #FFB4B4; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6 py-12">
    <div class="max-w-md w-full space-y-8">
        
        <div class="toy-card p-8 bg-white relative">
            <div class="absolute -top-4 -right-4 bg-yellow-400 border-4 border-black px-4 py-1 font-black italic -rotate-12 shadow-[4px_4px_0px_#000]">
                NEW ORDER!
            </div>
            
            <h1 class="text-3xl font-black mb-1 italic">YoToy Store</h1>
            <p class="text-gray-500 mb-6 font-bold text-sm uppercase">Order ID: #<?= $order_id ?></p>
            
            <div class="space-y-3 mb-6">
                <?php while($item = mysqli_fetch_assoc($items_query)): ?>
                <div class="flex justify-between font-bold">
                    <span>x1 <?= $item['game_title'] ?></span>
                    <span>฿<?= number_format($item['price'], 2) ?></span>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="receipt-line pt-4 flex justify-between items-center">
                <span class="text-xl font-black italic">ราคาสุทธิ</span>
                <span class="text-3xl font-black text-pink-500">฿<?= number_format($total, 2) ?></span>
            </div>
            
            <p class="mt-6 text-center text-xs font-black text-gray-400 bg-gray-100 py-2 rounded-lg uppercase">
                📸 กรุณาบันทึกภาพหน้าจอนี้ไว้
            </p>
        </div>

        <div class="toy-card p-8 bg-white text-center">
            <h2 class="text-xl font-black mb-6 italic uppercase underline decoration-toy-blue decoration-4">Scan to Pay</h2>
            
            <div class="inline-block p-4 border-4 border-black rounded-2xl mb-6 shadow-[5px_5px_0px_#000]">
                <img src="<?= $qr_url ?>" alt="PromptPay" class="w-48 h-48">
            </div>

            <form action="confirm_payment.php" method="POST" enctype="multipart/form-data" class="text-left space-y-4">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <div class="space-y-2">
                    <label class="font-bold text-sm ml-1">📸 แนบสลิปการโอนเงิน:</label>
                    <input type="file" name="slip" accept="image/*" required 
                        class="w-full border-4 border-black rounded-xl px-4 py-3 bg-gray-50 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-2 file:border-black file:text-xs file:font-black file:bg-toy-yellow hover:file:bg-white transition">
                </div>
                <button type="submit" class="w-full py-4 bg-[#BFF6C3] border-4 border-black text-black font-black text-xl rounded-2xl shadow-[5px_5px_0px_#000] hover:translate-y-1 hover:shadow-none transition-all uppercase italic">
                    Confirm & Send Slip 🚀
                </button>
            </form>
        </div>

    </div>
</body>
</html>