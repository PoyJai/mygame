<?php
session_start();
require_once 'db_config.php'; 

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header('location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('location: login.php'); 
    exit;
}

$is_logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$current_username = $is_logged_in ? htmlspecialchars($_SESSION["username"]) : "Guest"; 

// ดึงข้อมูลเกมมาแสดงผลและใช้ทำ Slider
$sql = "SELECT id, title, genre, image_url, price FROM games LIMIT 8";
$result = $conn->query($sql);
$games = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $games[] = $row;
    }
}
$conn->close();

// เตรียม Array รูปภาพสำหรับ JavaScript Slider
$game_images = array_column($games, 'image_url');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StunShop - Gaming World! 🎮✨</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pop-yellow': '#FFEF00', 'pop-blue': '#00C2FF',
                        'pop-pink': '#FF48B0', 'pop-green': '#2DFF81', 'pop-orange': '#FF7A00',
                    },
                }
            }
        }
    </script>
    <style>
        * {
            border-radius: 0 !important;
        }

        /* แก้ไขส่วน pop-btn เดิม (ถ้ามี rounded-full ให้เอาออก) */
        .pop-btn {
            border: 3px solid #000;
            box-shadow: 4px 4px 0px #000;
            transition: all 0.1s;
            border-radius: 0; /* บังคับขอบเหลี่ยม */
        }

        /* ส่วนของ Card */
        .pop-card {
            background: white;
            border: 3px solid #000;
            box-shadow: 6px 6px 0px #000;
            border-radius: 0; /* บังคับขอบเหลี่ยม */
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @media (min-width: 768px) { .pop-card:hover { transform: scale(1.03) rotate(1deg); box-shadow: 12px 12px 0px #FF48B0; } }
        .pop-btn { border: 3px solid #000; box-shadow: 4px 4px 0px #000; transition: all 0.1s; }
        .pop-btn:active { transform: translate(3px, 3px); box-shadow: 0px 0px 0px #000; }
        .float-anim { animation: float 4s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        #mobile-menu { transition: transform 0.3s ease-in-out; }
        .menu-open { transform: translateX(0) !important; }
        
        /* สไตล์เพิ่มเติมสำหรับ Slider */
        .slider-container { position: relative; overflow: hidden; border: 4px solid #000; box-shadow: 10px 10px 0px #000; }
        #game-slider img { transition: opacity 0.8s ease-in-out; }
    </style>
</head>
<body class="text-black">

    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b-4 border-black">
        <nav class="container mx-auto px-4 py-3 md:px-6 md:py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl md:text-4xl font-black tracking-tighter flex items-center group">
                <span class="bg-pop-yellow border-2 border-black px-2 py-0.5 italic shadow-[3px_3px_0px_#000]">STUN</span>
                <span class="ml-1 uppercase">Shop</span>
            </a>
            <div class="hidden md:flex space-x-6 items-center font-bold">
                <a href="index.php" class="hover:text-pop-pink transition">หน้าแรก</a>
                <a href="allgame.php" class="hover:text-pop-blue transition">คลังเกม</a>
                <a href="contact.php" class="hover:text-pop-green transition">ติดต่อเรา</a>
                <button id="open-cart-btn" class="pop-btn bg-pop-green p-2 rounded-full relative group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="cart-count-badge absolute -top-2 -right-2 bg-pop-pink text-white text-xs px-2 py-0.5 border-2 border-black rounded-full">0</span>
                </button>
                <div class="flex items-center space-x-2 bg-white border-2 border-black px-3 py-1 shadow-[3px_3px_0px_#000]">
                    <span class="text-xs font-black text-pop-pink"><?= $current_username ?></span>
                    <a href="index.php?logout=1" class="text-[10px] text-red-500 underline font-black">EXIT</a>
                </div>
            </div>
            <div class="flex md:hidden items-center space-x-3">
                <button id="open-cart-btn-mob" class="pop-btn bg-pop-green p-2 rounded-lg relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="cart-count-badge absolute -top-2 -right-2 bg-pop-pink text-white text-[10px] px-1.5 py-0.5 border-2 border-black rounded-full">0</span>
                </button>
                <button id="menu-toggle" class="p-2 border-2 border-black bg-pop-yellow shadow-[3px_3px_0px_#000]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </nav>
    </header>

    <div id="mobile-menu" class="fixed inset-0 z-[60] bg-pop-blue transform translate-x-full md:hidden flex flex-col items-center justify-center space-y-8 text-2xl font-black italic border-l-8 border-black">
        <button id="menu-close" class="absolute top-6 right-6 text-white bg-black p-2 rounded-full">X</button>
        <a href="index.php" class="hover:bg-white px-4 py-2 border-4 border-transparent hover:border-black transition">หน้าแรก</a>
        <a href="allgame.php" class="hover:bg-white px-4 py-2 border-4 border-transparent hover:border-black transition">คลังเกม</a>
        <a href="contact.php" class="hover:bg-white px-4 py-2 border-4 border-transparent hover:border-black transition">ติดต่อเรา</a>
        <a href="index.php?logout=1" class="text-red-600 bg-white border-4 border-black px-6 py-2">LOGOUT EXIT</a>
    </div>

    <main class="container mx-auto px-4 md:px-6 py-8 md:py-12">
        
        <div class="bg-pop-orange border-4 border-black p-6 md:p-12 mb-10 md:mb-16 relative overflow-hidden shadow-[8px_8px_0px_#000] md:shadow-[15px_15px_0px_#000]" data-aos="zoom-in">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-8xl font-black text-white uppercase mb-4 [text-shadow:3px_3px_0px_#000] md:[text-shadow:6px_6px_0px_#000] leading-tight">
                        Happy <br>Gaming!
                    </h1>
                    <p class="text-sm md:text-2xl font-bold text-black bg-white inline-block px-4 py-1 border-2 border-black rotate-2">
                        แหล่งรวมเกมที่สดใสที่สุด 🌈
                    </p>
                </div>

                <div class="mt-8 md:mt-0 w-full md:w-1/2 max-w-lg" data-aos="fade-left">
                    <div class="slider-container aspect-video bg-black">
                        <div id="game-slider" class="w-full h-full relative">
                            <img id="slider-img" src="<?= !empty($game_images) ? $game_images[0] : 'https://placehold.co/600x400?text=STUNSHOP+GAMES' ?>" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-white p-2 text-center font-bold text-sm">
                                🔥 คลังเกมอัปเดตใหม่ทุกสัปดาห์!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
            <div class="pop-card bg-pop-blue p-8 flex flex-col justify-center" data-aos="fade-right">
                <h2 class="text-3xl font-black mb-4 uppercase italic">ทำไมต้อง StunShop?</h2>
                <p class="text-lg font-bold leading-relaxed">
                    เราคือแพลตฟอร์มจำหน่ายเกมดิจิทัลที่เน้นความสนุกและเข้าถึงง่าย! ไม่ว่าคุณจะเป็นเกมเมอร์สาย Hardcore หรือสาย Casual 
                    เรามีเกมคัดสรรคุณภาพในราคาที่เป็นมิตร พร้อมระบบที่ใช้งานง่ายและรวดเร็ว
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4" data-aos="fade-left">
                <div class="pop-card bg-pop-green p-4 text-center flex flex-col items-center justify-center">
                    <span class="text-4xl mb-2">🚀</span>
                    <h4 class="font-black">ส่งไว</h4>
                    <p class="text-xs font-bold">ได้รับโค้ดทันทีหลังชำระเงิน</p>
                </div>
                <div class="pop-card bg-pop-yellow p-4 text-center flex flex-col items-center justify-center">
                    <span class="text-4xl mb-2">💎</span>
                    <h4 class="font-black">ของแท้</h4>
                    <p class="text-xs font-bold">ลิขสิทธิ์แท้ 100% ทุกเกม</p>
                </div>
                <div class="pop-card bg-pop-pink p-4 text-center flex flex-col items-center justify-center">
                    <span class="text-4xl mb-2">🛡️</span>
                    <h4 class="font-black">ปลอดภัย</h4>
                    <p class="text-xs font-bold">ระบบชำระเงินมาตรฐานสากล</p>
                </div>
                <div class="pop-card bg-white p-4 text-center flex flex-col items-center justify-center">
                    <span class="text-4xl mb-2">📞</span>
                    <h4 class="font-black">24/7</h4>
                    <p class="text-xs font-bold">ทีมงานพร้อมซัพพอร์ตตลอด</p>
                </div>
            </div>
        </section>

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-12" data-aos="fade-right">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-pop-blue p-2 border-2 border-black mr-4 rotate-12">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2"><path d="M21 7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7z"/><path d="M6 12h4m-2-2v4m7-2h.01M17 10h.01"/></svg>
                </div>
                <h2 class="text-xl md:text-4xl font-black uppercase italic bg-pop-pink text-white px-3 md:px-4 border-2 border-black shadow-[4px_4px_0px_#000]">เกมยอดนิยม</h2>
            </div>
            <a href="allgame.php" class="text-sm font-bold underline decoration-2 md:decoration-4 decoration-pop-blue hover:text-pop-blue transition">ดูทั้งหมด</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-10 relative z-10">
            <?php if (!empty($games)): ?>
                <?php foreach ($games as $game): 
                    $img = !empty($game['image_url']) ? $game['image_url'] : 'https://placehold.co/400x300/white/black?text=GAME+PIC';
                ?>
                <div class="pop-card group" data-aos="fade-up">
                    <div class="border-b-4 border-black overflow-hidden bg-gray-200 aspect-video md:h-48 relative">
                        <img src="<?= $img ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="<?= $game['title'] ?>">
                        <div class="absolute top-2 right-2 bg-pop-green border-2 border-black px-2 py-1 text-[8px] md:text-[10px] font-black rotate-12">มาแรง!</div>
                    </div>
                    <div class="p-4">
                        <span class="text-[8px] md:text-[10px] font-black uppercase bg-pop-yellow px-2 border border-black inline-block mb-1 italic">
                            <?= htmlspecialchars($game['genre']) ?>
                        </span>
                        <h3 class="text-lg md:text-xl font-black mb-3 line-clamp-1 uppercase group-hover:text-pop-blue"><?= htmlspecialchars($game['title']) ?></h3>
                        <div class="flex justify-between items-center">
                            <span class="text-xl md:text-2xl font-black tracking-tighter">฿<?= number_format($game['price'], 0) ?></span>
                            <button onclick='addToCart(<?= json_encode($game) ?>)' class="pop-btn bg-pop-yellow p-2 hover:bg-pop-pink transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20 bg-white border-4 border-dashed border-black">
                    <p class="text-xl font-bold italic animate-pulse">กำลังเตรียมความสนุก... 🎮</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="cart-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[110] hidden flex items-center justify-center p-4">
        <div class="bg-white border-4 border-black shadow-[8px_8px_0px_#000] w-full max-w-md p-6 md:p-8 relative">
            <button id="close-cart-modal-btn" class="absolute -top-4 -right-4 bg-pop-pink border-4 border-black text-white px-3 py-1 font-black shadow-[3px_3px_0px_#000]">X</button>
            <h2 class="text-2xl font-black mb-6 border-b-4 border-black inline-block uppercase italic bg-pop-yellow px-2">ตะกร้าของคุณ 🛒</h2>
            <div id="cart-items-list" class="space-y-4 mb-6 max-h-[50vh] overflow-y-auto pr-2"></div>
            <div class="border-t-4 border-black pt-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-black italic uppercase">ยอดรวม:</span>
                    <span id="cart-total-amount" class="text-3xl font-black text-pop-pink">฿0.00</span>
                </div>
                <button id="checkout-btn" class="pop-btn w-full py-3 bg-pop-green font-black text-xl uppercase italic">BUY NOW! 🚀</button>
            </div>
        </div>
    </div>

    <footer class="mt-10 py-10 text-center border-t-4 border-black bg-white">
        <p class="font-black text-sm md:text-lg">STUNSHOP.TOY &copy; 2026</p>
        <p class="font-black text-sm md:text-lg">เว็บนี้สร้างไว้สำหรับส่งงานเท่านั้น<br>วิทยาลัยอาชีวศึกษาวิริยาลัยนครสวรรค์ &copy;</p>
    </footer>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // --- Logic: Game Image Slider ---
        const gameImages = <?= json_encode($game_images) ?>;
        let currentImgIndex = 0;
        const sliderImg = document.getElementById('slider-img');

        if(gameImages.length > 1) {
            setInterval(() => {
                sliderImg.style.opacity = 0; // ค่อยๆ เฟดออก
                setTimeout(() => {
                    currentImgIndex = (currentImgIndex + 1) % gameImages.length;
                    sliderImg.src = gameImages[currentImgIndex];
                    sliderImg.style.opacity = 1; // ค่อยๆ เฟดเข้า
                }, 800);
            }, 4000); // เปลี่ยนรูปทุก 4 วินาที
        }

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuClose = document.getElementById('menu-close');
        menuToggle.onclick = () => mobileMenu.classList.add('menu-open');
        menuClose.onclick = () => mobileMenu.classList.remove('menu-open');

        // Cart Logic (โค้ดเดิมที่ปรับปรุงเล็กน้อย)
        const cartModal = document.getElementById('cart-modal');
        const cartItemsList = document.getElementById('cart-items-list');
        const cartTotalAmount = document.getElementById('cart-total-amount');
        const checkoutBtn = document.getElementById('checkout-btn');

        const getCart = () => JSON.parse(localStorage.getItem('game_cart') || '[]');
        const saveCart = (cart) => localStorage.setItem('game_cart', JSON.stringify(cart));

        window.addToCart = (game) => {
            const cart = getCart();
            cart.push(game);
            saveCart(cart);
            renderCart();
            // ให้เด้งตะกร้าขึ้นมาโชว์เมื่อเพิ่มเกม
            cartModal.classList.remove('hidden');
        };

        window.removeItem = (index) => {
            const cart = getCart();
            cart.splice(index, 1);
            saveCart(cart);
            renderCart();
        };

        const renderCart = () => {
            const cart = getCart();
            let total = 0;
            cartItemsList.innerHTML = '';
            document.querySelectorAll('.cart-count-badge').forEach(b => b.textContent = cart.length);

            if (cart.length === 0) {
                cartItemsList.innerHTML = '<div class="text-center py-6 italic opacity-50">ว่างเปล่า...</div>';
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('opacity-50');
            } else {
                checkoutBtn.disabled = false;
                checkoutBtn.classList.remove('opacity-50');
                cart.forEach((item, index) => {
                    const price = parseFloat(item.price || 0);
                    total += price;
                    cartItemsList.innerHTML += `
                        <div class="flex justify-between items-center p-2 border-2 border-black bg-gray-50 mb-2">
                            <div>
                                <div class="font-black text-xs uppercase">${item.title}</div>
                                <div class="text-pop-pink font-bold text-sm">฿${price.toLocaleString()}</div>
                            </div>
                            <button onclick="removeItem(${index})" class="bg-red-500 text-white border-2 border-black px-2 py-1 font-black">X</button>
                        </div>`;
                });
            }
            cartTotalAmount.textContent = `฿${total.toLocaleString(undefined, {minimumFractionDigits: 0})}`;
        };

        document.getElementById('open-cart-btn').onclick = () => { renderCart(); cartModal.classList.remove('hidden'); };
        document.getElementById('open-cart-btn-mob').onclick = () => { renderCart(); cartModal.classList.remove('hidden'); };
        document.getElementById('close-cart-modal-btn').onclick = () => cartModal.classList.add('hidden');
        checkoutBtn.onclick = () => { window.location.href = 'checkout.php'; };

        renderCart();
    </script>
</body>
</html>