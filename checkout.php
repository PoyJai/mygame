<?php
session_start();
require_once 'db_config.php'; 

// 1. Logout Logic
if (isset($_GET['logout'])) {
    session_destroy(); 
    header('location: login.php'); 
    exit;
}

// 2. Auth Check
$is_logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$current_username = $is_logged_in ? htmlspecialchars($_SESSION["username"]) : "Guest"; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ชำระเงิน | YoToy ✨</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@300;400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'toy-pink': '#FFB4B4',
                        'toy-blue': '#B4E4FF',
                        'toy-yellow': '#FDF7C3',
                        'toy-green': '#BFF6C3',
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Mitr', sans-serif; 
            background-color: #FFFDF9; 
            background-image: radial-gradient(#FFB4B4 0.5px, transparent 0.5px);
            background-size: 20px 20px;
            -webkit-tap-highlight-color: transparent;
        }
        .toy-card {
            background: white;
            border: 3px solid #000;
            box-shadow: 6px 6px 0px #000;
            border-radius: 1.5rem;
        }
        @media (min-width: 768px) {
            .toy-card { box-shadow: 8px 8px 0px #000; }
        }
        .input-toy {
            border: 3px solid #000;
            border-radius: 1rem;
            font-size: 16px; /* ป้องกัน iOS Auto-zoom */
        }
        .btn-toy {
            border: 3px solid #000;
            box-shadow: 4px 4px 0px #000;
            transition: all 0.1s;
        }
        .btn-toy:active {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0px #000;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #000; border-radius: 10px; }
    </style>
</head>
<body class="pb-10 md:pb-20">

    <header class="bg-white/80 backdrop-blur-md border-b-4 border-black mb-6 md:mb-10 sticky top-0 z-50">
        <nav class="container mx-auto px-4 md:px-6 py-3 md:py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl md:text-3xl font-black italic">
                <span class="text-toy-pink">Yo</span><span class="text-toy-blue">Toy</span>
            </a>
            <div class="flex items-center gap-2 md:gap-4">
                <span class="text-[10px] md:text-sm font-bold bg-toy-yellow px-2 md:px-3 py-1 border-2 border-black rounded-full truncate max-w-[100px] md:max-w-none">
                    👤 <?= $current_username ?>
                </span>
                <a href="allgame.php" class="font-bold text-gray-500 hover:text-black transition text-[10px] md:text-sm underline md:no-underline">← กลับ</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 md:px-6 max-w-6xl">
        <div class="flex flex-col lg:flex-row gap-6 md:gap-10">
            
            <div class="flex-1 order-2 lg:order-1 space-y-6 md:space-y-8">
                <div class="toy-card p-5 md:p-8">
                    <h2 class="text-xl md:text-2xl font-black text-black mb-6 md:mb-8 flex items-center uppercase italic">
                        <span class="bg-toy-blue w-8 h-8 md:w-10 md:h-10 border-2 border-black rounded-full inline-flex items-center justify-center mr-3 shadow-[2px_2px_0px_#000] non-italic text-sm md:text-base">1</span>
                        Shipping
                    </h2>
                    <form id="checkout-form" class="space-y-4 md:space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div class="space-y-1 md:space-y-2">
                                <label class="text-sm font-bold ml-1 text-gray-700">ชื่อ-นามสกุล</label>
                                <input type="text" name="name" placeholder="John Doe" required class="input-toy w-full px-4 py-3 md:py-4">
                            </div>
                            <div class="space-y-1 md:space-y-2">
                                <label class="text-sm font-bold ml-1 text-gray-700">อีเมล</label>
                                <input type="email" name="email" placeholder="hello@example.com" required class="input-toy w-full px-4 py-3 md:py-4">
                            </div>
                        </div>
                        <div class="space-y-1 md:space-y-2">
                            <label class="text-sm font-bold ml-1 text-gray-700">เบอร์โทร</label>
                            <input type="tel" name="phone" placeholder="08X-XXXXXXX" required class="input-toy w-full md:w-1/2 px-4 py-3 md:py-4">
                        </div>
                    </form>
                </div>

                <div class="toy-card p-5 md:p-8">
                    <h2 class="text-xl md:text-2xl font-black text-black mb-6 md:mb-8 flex items-center uppercase italic">
                        <span class="bg-toy-yellow w-8 h-8 md:w-10 md:h-10 border-2 border-black rounded-full inline-flex items-center justify-center mr-3 shadow-[2px_2px_0px_#000] non-italic text-black text-sm md:text-base">2</span>
                        Payment
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <label class="relative flex items-center p-4 md:p-5 border-4 border-black rounded-2xl cursor-pointer bg-white shadow-[4px_4px_0px_#000] active:translate-y-1 active:shadow-none transition-all">
                            <input type="radio" name="payment-method" value="promptpay" class="w-5 h-5 accent-black" checked>
                            <span class="ml-4 font-black text-base md:text-lg">PROMPTPAY 📱</span>
                        </label>
                        <label class="relative flex items-center p-4 md:p-5 border-4 border-gray-300 rounded-2xl bg-gray-50 opacity-50">
                            <input type="radio" name="payment-method" value="wallet" class="w-5 h-5" disabled>
                            <span class="ml-4 font-black text-base md:text-lg text-gray-400">WALLET (Soon)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="lg:w-96 order-1 lg:order-2">
                <div class="toy-card p-5 md:p-8 sticky top-24 md:top-28 bg-white">
                    <h2 class="text-lg md:text-xl font-black mb-4 md:mb-6 border-b-4 border-black pb-3 md:pb-4 text-center uppercase italic">Your Order</h2>
                    
                    <div id="checkout-items-list" class="space-y-3 mb-6 max-h-48 md:max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                        </div>

                    <div id="empty-cart-message" class="hidden text-center py-6">
                        <p class="font-bold text-gray-400">ตะกร้าว่างเปล่า 🧐</p>
                    </div>

                    <div class="space-y-3 pt-4 border-t-4 border-black font-bold">
                        <div class="flex justify-between text-gray-500 text-sm md:text-base">
                            <span>ราคารวม:</span>
                            <span id="subtotal-amount">฿0.00</span>
                        </div>
                        <div class="flex justify-between text-green-600 text-sm md:text-base">
                            <span>ค่าจัดส่ง:</span>
                            <span>FREE</span>
                        </div>
                        <div class="flex justify-between text-2xl md:text-3xl font-black text-black pt-2 border-t-2 border-dashed border-gray-200">
                            <span id="grand-total-amount">฿0.00</span>
                        </div>
                    </div>

                    <button id="place-order-btn" type="submit" form="checkout-form" class="btn-toy w-full mt-6 md:mt-10 py-4 md:py-5 bg-toy-green text-black font-black text-xl md:text-2xl rounded-2xl uppercase italic disabled:opacity-50">
                        Confirm ✨
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        const cartItemsList = document.getElementById('checkout-items-list');
        const subtotalAmount = document.getElementById('subtotal-amount');
        const grandTotalAmount = document.getElementById('grand-total-amount');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const checkoutForm = document.getElementById('checkout-form');
        const emptyCartMessage = document.getElementById('empty-cart-message');

        const getCartFromStorage = () => JSON.parse(localStorage.getItem('game_cart') || '[]');

        const renderCartAndCalculate = () => {
            const cart = getCartFromStorage();
            let total = 0;
            cartItemsList.innerHTML = '';
            
            if (cart.length === 0) {
                emptyCartMessage.classList.remove('hidden');
                placeOrderBtn.disabled = true;
                placeOrderBtn.classList.add('grayscale');
                return;
            }

            emptyCartMessage.classList.add('hidden');
            placeOrderBtn.disabled = false;
            
            cart.forEach(item => {
                const price = parseFloat(item.price || 0);
                total += price;
                
                cartItemsList.innerHTML += `
                    <div class="flex justify-between items-start gap-4 p-3 border-2 border-black rounded-xl bg-gray-50">
                        <span class="font-bold text-[12px] md:text-sm leading-tight text-black line-clamp-2">${item.title}</span>
                        <span class="font-black text-toy-pink whitespace-nowrap text-sm">฿${price.toLocaleString()}</span>
                    </div>
                `;
            });

            subtotalAmount.textContent = `฿${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
            grandTotalAmount.textContent = `฿${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        };
        
        const handlePlaceOrder = async (e) => {
            e.preventDefault();
            const cart = getCartFromStorage();
            
            if (cart.length === 0) { alert("ตะกร้าว่างเปล่า!"); return; }

            const formData = new FormData(checkoutForm);
            const orderData = {
                ...Object.fromEntries(formData.entries()),
                cart: cart,
                total: parseFloat(grandTotalAmount.textContent.replace('฿', '').replace(/,/g, ''))
            };

            try {
                placeOrderBtn.disabled = true;
                placeOrderBtn.textContent = "Processing... ⏳";

                const response = await fetch('process_checkout.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(orderData)
                });

                const result = await response.json();

                if (result.success) {
                    localStorage.removeItem('game_cart');
                    window.location.href = `payment.php?order_id=${result.order_id}&total=${orderData.total}`;
                } else {
                    alert("❌ " + result.message);
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.textContent = "Confirm Order ✨";
                }
            } catch (error) {
                console.error("Error:", error);
                alert("เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์");
                placeOrderBtn.disabled = false;
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            renderCartAndCalculate(); 
            checkoutForm.addEventListener('submit', handlePlaceOrder);
        });
    </script>
</body>
</html>