<?php
// ต้องเรียกใช้ session_start() ก่อนการส่งออกใด ๆ
session_start();

// !!! เพิ่มการเชื่อมต่อฐานข้อมูล !!!
// ในหน้า checkout จริง อาจจำเป็นต้องใช้ฐานข้อมูลเพื่อดึงข้อมูลลูกค้า หรือบันทึกออร์เดอร์
require_once 'db_config.php'; 

// 1. ตรวจสอบการออกจากระบบ (Logout Logic)
if (isset($_GET['logout'])) {
    session_destroy(); 
    header('location: login.php'); 
    exit;
}

// 2. ตรวจสอบสถานะการเข้าสู่ระบบ
$is_logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$current_username = $is_logged_in ? htmlspecialchars($_SESSION["username"]) : "Guest"; 

// !!! ปิดการเชื่อมต่อฐานข้อมูลเมื่อเสร็จสิ้นการใช้งาน PHP ด้านบน !!!
if (isset($conn)) $conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดำเนินการชำระเงิน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#4F46E5', 
                        'secondary': '#F97316', 
                        'background': '#1F2937', 
                        'card': '#374151', 
                    },
                    fontFamily: {
                        sans: ['Inter', 'Tahoma', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #1F2937;
            color: #F3F4F6;
        }
        input[type="text"], input[type="email"], textarea, select {
            background-color: #1F2937;
            border: 1px solid #4B5563; /* Gray-600 */
            color: #F3F4F6;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        input[type="text"]:focus, input[type="email"]:focus, textarea:focus, select:focus {
            border-color: #4F46E5; /* Primary */
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.5);
            outline: none;
        }
        /* Custom scrollbar for checkout list */
        .checkout-list::-webkit-scrollbar {
            width: 8px;
        }
        .checkout-list::-webkit-scrollbar-thumb {
            background-color: #4B5563;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <header class="bg-background/90 shadow-lg border-b border-gray-700">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-primary">
                AESTHETIC<span class="text-secondary">.GAMES</span>
            </a>
            <span class="text-xl text-white font-semibold hidden md:block">ขั้นตอนที่ 2: ชำระเงิน</span>
            
            <a href="allgame.php" class="text-sm text-gray-400 hover:text-white transition duration-150">
                &larr; กลับไปเลือกเกม
            </a>
        </nav>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
        <h1 class="text-4xl font-extrabold text-white mb-10 text-center">ดำเนินการชำระเงิน</h1>

        <div class="lg:grid lg:grid-cols-3 lg:gap-10">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-card p-6 rounded-xl shadow-2xl border border-gray-700">
                    <h2 class="text-2xl font-bold text-primary mb-5">1. ข้อมูลติดต่อและที่อยู่</h2>
                    <form id="checkout-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">ชื่อ-นามสกุล</label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-2 rounded-lg" placeholder="ปิยวัฒน์ ใจดี">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">อีเมล</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-2 rounded-lg" placeholder="you@example.com">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="address" class="block text-sm font-medium text-gray-300 mb-1">ที่อยู่จัดส่ง</label>
                            <textarea id="address" name="address" rows="3" required class="w-full px-4 py-2 rounded-lg" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-300 mb-1">จังหวัด</label>
                                <input type="text" id="province" name="province" required class="w-full px-4 py-2 rounded-lg" placeholder="กรุงเทพมหานคร">
                            </div>
                            <div>
                                <label for="zipcode" class="block text-sm font-medium text-gray-300 mb-1">รหัสไปรษณีย์</label>
                                <input type="text" id="zipcode" name="zipcode" required class="w-full px-4 py-2 rounded-lg" pattern="[0-9]{5}" placeholder="10000">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">เบอร์โทรศัพท์</label>
                                <input type="text" id="phone" name="phone" required class="w-full px-4 py-2 rounded-lg" pattern="[0-9]{10}" placeholder="08XXXXXXXX">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-card p-6 rounded-xl shadow-2xl border border-gray-700">
                    <h2 class="text-2xl font-bold text-primary mb-5">2. วิธีการชำระเงิน</h2>
                    <div class="space-y-3">
                        <div class="flex items-start bg-gray-700 p-3 rounded-lg border border-gray-600 cursor-pointer hover:border-primary transition duration-150">
                            <input type="radio" id="payment-bank" name="payment-method" value="bank_transfer" required class="mt-1 h-5 w-5 text-primary focus:ring-primary" checked>
                            <label for="payment-bank" class="ml-3 block text-white font-medium">โอนเงินผ่านธนาคาร</label>
                        </div>
                        <div class="flex items-start bg-gray-700 p-3 rounded-lg border border-gray-600 cursor-pointer hover:border-primary transition duration-150">
                            <input type="radio" id="payment-credit" name="payment-method" value="credit_card" required class="mt-1 h-5 w-5 text-primary focus:ring-primary">
                            <label for="payment-credit" class="ml-3 block text-white font-medium">บัตรเครดิต/เดบิต (จำลอง)</label>
                        </div>
                        <div class="flex items-start bg-gray-700 p-3 rounded-lg border border-gray-600 cursor-pointer hover:border-primary transition duration-150">
                            <input type="radio" id="payment-promptpay" name="payment-method" value="promptpay" required class="mt-1 h-5 w-5 text-primary focus:ring-primary">
                            <label for="payment-promptpay" class="ml-3 block text-white font-medium">พร้อมเพย์</label>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="lg:col-span-1 mt-10 lg:mt-0">
                <div class="bg-card p-6 rounded-xl shadow-2xl border border-secondary/50 sticky top-20">
                    <h2 class="text-2xl font-bold text-secondary mb-5 border-b border-gray-700 pb-3">สรุปคำสั่งซื้อ</h2>
                    
                    <div id="checkout-items-list" class="checkout-list max-h-72 overflow-y-auto space-y-4 pr-2">
                        <p class="text-center text-gray-500 py-10">กำลังโหลดรายการสินค้า...</p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-700 space-y-3">
                        <div class="flex justify-between text-gray-300">
                            <span>ราคารวมสินค้า:</span>
                            <span id="subtotal-amount">฿0.00</span>
                        </div>
                        <div class="flex justify-between text-gray-300">
                            <span>ค่าจัดส่ง:</span>
                            <span id="shipping-fee" class="text-green-400">ฟรี</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-2xl font-bold pt-3 border-t border-gray-600">
                            <span class="text-white">ยอดชำระสุทธิ:</span>
                            <span id="grand-total-amount" class="text-secondary">฿0.00</span>
                        </div>
                    </div>
                    
                    <button id="place-order-btn" type="submit" form="checkout-form" class="w-full mt-6 px-4 py-3 bg-secondary rounded-lg text-white font-bold text-lg hover:bg-orange-700 transition duration-300 disabled:opacity-50" disabled>
                        ยืนยันและชำระเงิน
                    </button>
                    <p id="empty-cart-message" class="text-red-400 text-sm mt-3 text-center hidden">กรุณาเพิ่มสินค้าลงในตะกร้าก่อนดำเนินการต่อ</p>
                </div>
            </div>

        </div>
    </main>
    
    <footer class="bg-card border-t border-gray-700 mt-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-gray-400">
            <p>&copy; 2025 โลกแห่งเกมอันงดงาม | Checkout</p>
        </div>
    </footer>
    
    <script>
        // **************** Cart UI & Logic Variables (ใช้ Local Storage) ****************
        const cartItemsList = document.getElementById('checkout-items-list');
        const subtotalAmount = document.getElementById('subtotal-amount');
        const grandTotalAmount = document.getElementById('grand-total-amount');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const checkoutForm = document.getElementById('checkout-form');
        const emptyCartMessage = document.getElementById('empty-cart-message');

        // 1. ดึงข้อมูลตะกร้าจาก Local Storage
        const getCartFromStorage = () => {
            const cartString = localStorage.getItem('game_cart');
            return cartString ? JSON.parse(cartString) : [];
        };

        // 2. ฟังก์ชัน Render รายการสินค้าและคำนวณยอดรวม
        const renderCartAndCalculate = () => {
            const cart = getCartFromStorage();
            let subTotal = 0;
            const shippingFee = 0; // สมมติว่าจัดส่งฟรี
            cartItemsList.innerHTML = '';
            
            if (cart.length === 0) {
                cartItemsList.innerHTML = '<p class="text-center text-gray-500 py-10">ตะกร้าว่างเปล่า</p>';
                placeOrderBtn.disabled = true;
                emptyCartMessage.classList.remove('hidden');
            } else {
                emptyCartMessage.classList.add('hidden');
                placeOrderBtn.disabled = false;
                
                cart.forEach(item => {
                    const price = item.price ? parseFloat(item.price) : 0.00; 
                    subTotal += price;
                    
                    const itemHtml = `
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-300 truncate pr-2">${item.title}</span>
                            <span class="text-white font-medium">฿${price.toFixed(2)}</span>
                        </div>
                    `;
                    cartItemsList.innerHTML += itemHtml;
                });
            }
            
            const grandTotal = subTotal + shippingFee;
            
            subtotalAmount.textContent = `฿${subTotal.toFixed(2)}`;
            grandTotalAmount.textContent = `฿${grandTotal.toFixed(2)}`;
        };
        
        // 3. จัดการการส่งคำสั่งซื้อ (จำลอง)
        const handlePlaceOrder = (e) => {
            e.preventDefault();
            
            if (getCartFromStorage().length === 0) {
                alert("ตะกร้าสินค้าว่างเปล่า กรุณาเพิ่มสินค้าก่อน");
                return;
            }
            
            // 1. รวบรวมข้อมูล
            const formData = new FormData(checkoutForm);
            const orderData = {};
            formData.forEach((value, key) => (orderData[key] = value));
            
            const cartItems = getCartFromStorage();
            
            // 2. แสดงผลสรุป (แทนการส่งไป Server จริง)
            console.log("Order Data:", orderData);
            console.log("Cart Items:", cartItems);
            
            const total = parseFloat(grandTotalAmount.textContent.replace('฿', ''));
            
            alert(`
                ✅ การสั่งซื้อสำเร็จ (จำลอง)!
                ยอดชำระ: ฿${total.toFixed(2)}
                วิธีการชำระ: ${orderData['payment-method']}
                ที่อยู่: ${orderData.address}, ${orderData.province} ${orderData.zipcode}
                
                *** หากมีการเชื่อมต่อฐานข้อมูลจริง โค้ดส่วนนี้จะทำการส่งข้อมูลไปยัง Server เพื่อบันทึกออร์เดอร์ ***
            `);
            
            // 3. ล้างตะกร้าสินค้าใน Local Storage และ Redirect กลับหน้าหลัก
            localStorage.removeItem('game_cart');
            window.location.href = 'index.php'; 
        };

        // **************** Event Listeners และ Initialization ****************
        document.addEventListener('DOMContentLoaded', () => {
            // โหลดรายการสินค้าในตะกร้าเมื่อโหลดหน้าเสร็จ
            renderCartAndCalculate(); 
            
            // แนบ Event Listener ให้ปุ่มยืนยันการสั่งซื้อ
            checkoutForm.addEventListener('submit', handlePlaceOrder);
        });
    </script>
</body>
</html>