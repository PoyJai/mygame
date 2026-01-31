<?php
session_start();
require_once 'db_config.php'; 

// 1. Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header('location: login.php'); 
    exit;
}

$is_logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$current_username = $is_logged_in ? htmlspecialchars($_SESSION["username"]) : "Guest"; 

// --- LOGIC ดึงข้อมูลเกม ---
$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($game_id === 0) {
    header("Location: allgame.php");
    exit;
}

$sql = "SELECT id, title, description, long_description, genre, image_url, image_url_2, image_url_3, price, release_date, developer, rating FROM games WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    header("Location: allgame.php");
    exit;
}

$stmt->close();
$conn->close();

$slides = [
    !empty($game['image_url']) ? $game['image_url'] : 'https://placehold.co/1200x600/eee/333?text=NO+IMAGE+1',
    !empty($game['image_url_2']) ? $game['image_url_2'] : 'https://placehold.co/1200x600/ddd/444?text=NO+IMAGE+2',
    !empty($game['image_url_3']) ? $game['image_url_3'] : 'https://placehold.co/1200x600/ccc/555?text=NO+IMAGE+3'
];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($game['title']) ?> - StunShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pop-yellow': '#FFEF00',
                        'pop-blue': '#00C2FF',
                        'pop-pink': '#FF48B0',
                        'pop-green': '#2DFF81',
                        'pop-orange': '#FF7A00',
                    },
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: #f0f0f0; 
            background-image: radial-gradient(#000 0.5px, transparent 0.5px);
            background-size: 20px 20px;
        }
        .pop-card {
            background: white;
            border: 4px solid #000;
            box-shadow: 10px 10px 0px #000;
        }
        .pop-btn {
            border: 3px solid #000;
            box-shadow: 4px 4px 0px #000;
            transition: all 0.1s;
        }
        .pop-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0px #000;
        }
        
        /* Animation Custom */
        @keyframes logo-shake {
            0% { transform: rotate(-2deg); }
            50% { transform: rotate(2deg); }
            100% { transform: rotate(-2deg); }
        }
        .logo-animate { animation: logo-shake 3s ease-in-out infinite; }

        .swiper-button-next, .swiper-button-prev {
            background: white !important;
            color: black !important;
            width: 50px !important;
            height: 50px !important;
            border: 3px solid #000 !important;
            box-shadow: 4px 4px 0px #000 !important;
            border-radius: 12px;
        }
    </style>
</head>
<body class="text-black">

    <header class="sticky top-0 z-50 bg-white border-b-4 border-black">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="allgame.php" class="text-3xl font-black italic flex items-center logo-animate">
                <span class="bg-pop-yellow text-black px-3 py-1 border-2 border-black mr-1 rotate-[-2deg]">STUN</span>
                <span>SHOP</span>
            </a>
            <div class="flex items-center space-x-6">
                <button id="open-cart-btn" class="pop-btn bg-pop-green p-2 rounded-full relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span id="cart-item-count" class="absolute -top-2 -right-2 bg-pop-pink text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center border-2 border-black">0</span>
                </button>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-12">
        <div class="max-w-6xl mx-auto mb-8" data-aos="fade-down">
            <a href="allgame.php" class="pop-btn bg-white px-6 py-3 font-black uppercase italic inline-flex items-center hover:bg-pop-pink hover:text-white transition group">
                <svg class="w-6 h-6 mr-2 transform group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Store
            </a>
        </div>

        <div class="pop-card overflow-hidden bg-white max-w-6xl mx-auto" data-aos="zoom-in">
            <div class="relative border-b-4 border-black group">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach($slides as $url): ?>
                        <div class="swiper-slide">
                            <img src="<?= htmlspecialchars($url) ?>" class="w-full h-[300px] md:h-[550px] object-cover">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-next opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="swiper-button-prev opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="swiper-pagination"></div>
                </div>

                <div class="absolute bottom-0 left-0 bg-pop-yellow border-t-4 border-r-4 border-black p-6 md:p-10 max-w-2xl z-10" data-aos="fade-right" data-aos-delay="400">
                    <h1 class="text-4xl md:text-6xl font-black uppercase italic leading-none">
                        <?= htmlspecialchars($game['title']) ?>
                    </h1>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                <div class="lg:col-span-2 p-8 md:p-12 border-b-4 lg:border-b-0 lg:border-r-4 border-black" data-aos="fade-up">
                    <div class="mb-8">
                        <span class="bg-pop-pink text-white px-4 py-1 text-lg font-black uppercase border-2 border-black inline-block mb-4 rotate-1" data-aos="zoom-in" data-aos-delay="600">
                            <?= htmlspecialchars($game['genre']) ?>
                        </span>
                        <p class="text-2xl font-bold italic mb-6 text-gray-700">
                            <?= htmlspecialchars($game['description']) ?>
                        </p>
                        <h2 class="text-3xl font-black uppercase italic mb-4 bg-black text-white inline-block px-2">About Game</h2>
                        <div class="text-lg leading-relaxed space-y-4">
                            <?= nl2br(htmlspecialchars($game['long_description'])) ?>
                        </div>
                    </div>

                    <div class="bg-pop-blue/10 border-4 border-dashed border-pop-blue p-6 flex items-center space-x-6" data-aos="flip-up">
                        <div class="text-6xl font-black"><?= number_format($game['rating'], 1) ?></div>
                        <div>
                            <div class="flex text-pop-orange">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <svg class="w-8 h-8 <?= ($i <= floor($game['rating'] / 2)) ? 'fill-current' : 'opacity-20' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <?php endfor; ?>
                            </div>
                            <p class="font-bold italic uppercase">Player Rating</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-12 bg-pop-yellow/50" data-aos="fade-left" data-aos-delay="500">
                    <div class="sticky top-32">
                        <div class="mb-8">
                            <p class="text-lg font-black uppercase italic">Price</p>
                            <div class="text-6xl font-black tracking-tighter">฿<?= number_format($game['price'], 0) ?></div>
                        </div>

                        <button id="add-to-cart-btn" 
                                data-id="<?= $game['id'] ?>" 
                                data-title="<?= htmlspecialchars($game['title']) ?>"
                                data-price="<?= $game['price'] ?>"
                                class="pop-btn w-full py-6 bg-pop-orange text-white font-black text-3xl uppercase italic hover:bg-black transition duration-300 flex items-center justify-center space-x-3">
                            <span>ADD TO CART</span>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                    <div class="mt-10 space-y-4">
                            <h3 class="text-xl font-black uppercase border-b-2 border-black pb-2">Specs</h3>
                            <div class="flex justify-between font-bold">
                                <span class="uppercase opacity-60">Developer</span>
                                <span><?= htmlspecialchars($game['developer'] ?? 'TBA') ?></span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span class="uppercase opacity-60">Release</span>
                                <span><?= !empty($game['release_date']) ? date('M d, Y', strtotime($game['release_date'])) : 'TBA' ?></span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span class="uppercase opacity-60">Game ID</span>
                                <span class="bg-black text-white px-2">#<?= $game['id'] ?></span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </main>

    <div id="cart-modal" class="fixed inset-0 bg-pop-blue/40 backdrop-blur-md z-[110] hidden flex items-center justify-center p-4">
        <div class="bg-white border-4 border-black shadow-[15px_15px_0px_#000] w-full max-w-md p-8 relative rounded-xl">
            <button id="close-cart-modal-btn" class="absolute -top-5 -right-5 bg-pop-pink border-4 border-black text-white p-2 font-black hover:rotate-12 transition">CLOSE X</button>
            <h2 class="text-3xl font-black mb-8 border-b-4 border-black inline-block uppercase italic">Your Cart 🛒</h2>
            <div id="cart-items-list" class="space-y-4 max-h-60 overflow-y-auto pr-2"></div>
            <div class="mt-8 pt-6 border-t-4 border-black">
                <div class="flex justify-between items-center mb-6">
                    <span class="font-black text-xl italic">TOTAL:</span>
                    <span id="cart-total-amount" class="text-4xl font-black text-pop-pink">฿0.00</span>
                </div>
                <button id="checkout-btn" class="pop-btn w-full py-4 bg-pop-green font-black text-2xl uppercase italic rounded-xl">Check Out! 🚀</button>
            </div>
        </div>
    </div>

    <footer class="mt-10 py-10 text-center border-t-4 border-black bg-white">
        <p class="font-black text-sm md:text-lg">STUNSHOP.TOY &copy; 2026</p>
        <p class="font-black text-sm md:text-lg">เว็บนี้สร้างไว้สำหรับส่งงานเท่านั้น<br>วิทยาลัยอาชีวศึกษาวิทยาลัยนครสวรรค์ &copy;</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, easing: 'ease-out-back' });

        const swiper = new Swiper('.mySwiper', {
            loop: true,
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            autoplay: { delay: 5000 },
        });

        // Cart Logic (เหมือนเดิมของคุณ)
        const getCartFromStorage = () => JSON.parse(localStorage.getItem('game_cart') || '[]');
        const saveCartToStorage = (cart) => { localStorage.setItem('game_cart', JSON.stringify(cart)); renderCart(); };

        const renderCart = () => {
            const cart = getCartFromStorage();
            const list = document.getElementById('cart-items-list');
            const totalEl = document.getElementById('cart-total-amount');
            const badge = document.getElementById('cart-item-count');
            badge.textContent = cart.length;
            badge.style.display = cart.length > 0 ? 'flex' : 'none';
            let total = 0;
            list.innerHTML = cart.length ? '' : '<p class="text-center font-bold py-10 uppercase italic">Empty Cart... 🛸</p>';
            cart.forEach((item, i) => {
                total += parseFloat(item.price);
                list.innerHTML += `<div class="flex justify-between items-center border-2 border-black p-3 bg-pop-yellow/20 rounded-lg mb-2">
                    <span class="font-black italic uppercase truncate">${item.title}</span>
                    <div class="flex items-center space-x-3">
                        <span class="font-black text-pop-pink">฿${parseFloat(item.price).toLocaleString()}</span>
                        <button onclick="removeItem(${i})" class="bg-black text-white px-2 font-black hover:bg-pop-pink transition">X</button>
                    </div>
                </div>`;
            });
            totalEl.textContent = `฿${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        };

        window.removeItem = (index) => {
            let cart = getCartFromStorage();
            cart.splice(index, 1);
            saveCartToStorage(cart);
        };

        document.getElementById('add-to-cart-btn').onclick = function() {
            let cart = getCartFromStorage();
            const id = this.dataset.id;
            if (cart.some(item => item.id == id)) {
                alert('อยู่ในตะกร้าแล้วจ้า! 🕹️');
            } else {
                cart.push({ id: id, title: this.dataset.title, price: this.dataset.price });
                saveCartToStorage(cart);
                document.getElementById('cart-modal').classList.remove('hidden');
            }
        };

        document.getElementById('open-cart-btn').onclick = () => { renderCart(); document.getElementById('cart-modal').classList.remove('hidden'); };
        document.getElementById('close-cart-modal-btn').onclick = () => document.getElementById('cart-modal').classList.add('hidden');
        document.addEventListener('DOMContentLoaded', renderCart);
    </script>
</body>
</html>