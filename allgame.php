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

// 3. Pagination Logic
$games_per_page = 12; 
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$total_games = 0;
if (isset($conn) && $conn->ping()) {
    $count_res = $conn->query("SELECT COUNT(*) AS total FROM games");
    $total_games = $count_res ? $count_res->fetch_assoc()['total'] : 0;
}

$total_pages = ceil($total_games / $games_per_page);
$offset = ($current_page - 1) * $games_per_page;

// 4. Fetch Games
$games = [];
if ($total_games > 0) {
    $sql = "SELECT id, title, description, genre, image_url, price FROM games LIMIT $games_per_page OFFSET $offset";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) { $games[] = $row; }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>คลังเกมสุดซ่า - StunShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pop-yellow': '#FFEF00', 'pop-blue': '#00C2FF',
                        'pop-pink': '#FF48B0', 'pop-green': '#2DFF81',
                    },
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: #f8f8f8; 
            background-image: radial-gradient(#000 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }
        .pop-card {
            background: white; border: 3px solid #000;
            box-shadow: 6px 6px 0px #000; border-radius: 1rem;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @media (min-width: 768px) {
            .pop-card:hover { transform: translate(-6px, -6px); box-shadow: 14px 14px 0px #00C2FF; }
        }
        .pop-btn {
            border: 2px solid #000; box-shadow: 3px 3px 0px #000;
            transition: all 0.15s ease;
        }
        .pop-btn:active { transform: translate(2px, 2px); box-shadow: 0px 0px 0px #000; }
        
        #mobile-menu { transition: transform 0.3s ease-in-out; }
        .menu-open { transform: translateX(0) !important; }

        /* --- New Animations --- */
        .floating-icon {
            position: fixed; z-index: -1; opacity: 0.15;
            pointer-events: none; animation: float 10s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(15deg); }
        }
        @keyframes shake {
            0% { transform: scale(1); }
            25% { transform: scale(0.9) rotate(-5deg); }
            75% { transform: scale(1.1) rotate(5deg); }
            100% { transform: scale(1); }
        }
        .btn-bounce { animation: shake 0.3s ease-in-out; }
        .logo-wiggle:hover span:first-child { animation: shake 0.4s infinite; display: inline-block; }
    </style>
</head>
<body class="text-black">

    <div class="floating-icon text-6xl" style="top: 15%; left: 5%; animation-delay: 0s;">🎮</div>
    <div class="floating-icon text-5xl" style="top: 40%; right: 8%; animation-delay: 2s;">🕹️</div>
    <div class="floating-icon text-7xl" style="bottom: 10%; left: 10%; animation-delay: 4s;">⌨️</div>
    <div class="floating-icon text-5xl" style="top: 70%; right: 15%; animation-delay: 1s;">🖱️</div>
    <div class="floating-icon text-4xl" style="bottom: 20%; right: 5%; animation-delay: 3s;">💾</div>

    <header class="sticky top-0 z-[100] bg-white border-b-4 border-black">
        <nav class="container mx-auto px-4 md:px-6 py-3 flex justify-between items-center">
            <a href="index.php" class="logo-wiggle text-2xl md:text-3xl font-black italic flex items-center group">
                <span class="bg-pop-pink text-white px-2 py-0.5 border-2 border-black mr-1 rotate-[-2deg]">STUN</span>
                <span>SHOP 🕹️</span>
            </a>

            <div class="hidden md:flex items-center space-x-6">
                <a href="index.php" class="font-bold hover:text-pop-pink transition-colors">หน้าแรก</a>
                <button id="open-cart-btn" class="pop-btn bg-pop-green p-2 rounded-xl relative group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="cart-item-count absolute -top-2 -right-2 bg-pop-pink text-white text-[10px] font-bold rounded-full h-6 w-6 flex items-center justify-center border-2 border-black">0</span>
                </button>
            </div>

            <div class="flex md:hidden items-center space-x-2">
                <button id="open-cart-btn-mob" class="pop-btn bg-pop-green p-2 rounded-lg relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="cart-item-count absolute -top-2 -right-2 bg-pop-pink text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-black">0</span>
                </button>
                <button id="menu-toggle" class="p-2 border-2 border-black bg-pop-yellow shadow-[3px_3px_0px_#000]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </nav>
    </header>

    <div id="mobile-menu" class="fixed inset-0 z-[150] bg-pop-blue transform translate-x-full md:hidden flex flex-col items-center justify-center space-y-8 text-2xl font-black italic border-l-8 border-black">
        <button id="menu-close" class="absolute top-6 right-6 text-white bg-black p-2 rounded-full">X</button>
        <a href="index.php" class="bg-white px-6 py-2 border-4 border-black shadow-[5px_5px_0px_#000]">หน้าแรก</a>
        <a href="allgame.php" class="bg-pop-yellow px-6 py-2 border-4 border-black shadow-[5px_5px_0px_#000]">คลังเกม</a>
        <a href="index.php?logout=1" class="text-red-500 bg-white border-4 border-black px-6 py-2">ออกจากระบบ</a>
    </div>

    <main class="container mx-auto px-4 md:px-6 py-8 md:py-12">
        <div class="mb-10 md:mb-16" data-aos="fade-down">
            <h1 class="text-4xl md:text-8xl font-black text-black mb-2 uppercase italic leading-tight">
                <span class="bg-pop-yellow border-4 border-black px-3 md:px-4 shadow-[5px_5px_0px_#000] md:shadow-[10px_10px_0px_#000]">All Games</span>
            </h1>
        </div>
            <p class="text-sm md:text-2xl font-bold italic text-pop-pink mt-4">หยิบความสนุกใส่ตะกร้าได้เลย! ✨</p>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-10">
            <?php $delay = 0; foreach ($games as $game): ?>
                <div class="pop-card group relative flex flex-col h-full bg-white overflow-hidden" data-aos="zoom-in-up" data-aos-delay="<?= $delay ?>">
                    <a href="game_detail.php?id=<?= $game['id'] ?>" class="flex flex-col flex-grow">
                        <div class="border-b-2 md:border-b-4 border-black overflow-hidden relative aspect-video md:h-56">
                            <img src="<?= htmlspecialchars($game['image_url'] ?: 'https://placehold.co/400x300/eee/333?text=NO+IMAGE') ?>" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            
                            <div class="absolute top-1 left-1 md:top-2 md:left-2">
                                <span class="text-[8px] md:text-[12px] font-black uppercase bg-pop-pink text-white px-1.5 md:px-3 py-0.5 border-2 border-black shadow-[2px_2px_0px_#000]">
                                    <?= htmlspecialchars($game['genre'] ?: 'General') ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-3 md:p-6 flex flex-col flex-grow">
                            <h3 class="font-black text-sm md:text-2xl mb-2 md:mb-4 line-clamp-1 italic uppercase tracking-tighter transition-colors group-hover:text-pop-blue">
                                <?= htmlspecialchars($game['title']) ?>
                            </h3>
                        </div>
                    </a>
                    
                    <div class="px-3 pb-3 md:px-6 md:pb-6 mt-auto">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 pt-2 border-t-2 border-dashed border-gray-200">
                            <span class="text-lg md:text-3xl font-black">฿<?= number_format($game['price'], 0) ?></span>
                            
                            <button onclick="addToCart(event, <?= $game['id'] ?>, '<?= addslashes($game['title']) ?>', <?= $game['price'] ?>)"
                                    class="pop-btn bg-pop-yellow p-2 md:p-3 hover:bg-pop-green rounded-lg flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 md:w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 100-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"></path>
                                </svg>
                                <span class="hidden md:inline ml-2 font-bold text-sm">ใส่ตะกร้า</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php $delay += 50; endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="mt-12 md:mt-24 flex flex-wrap justify-center gap-2" data-aos="fade-up">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="allgame.php?page=<?= $i ?>" 
                   class="pop-btn px-4 py-2 md:px-8 md:py-3 font-black text-sm md:text-xl transition-all rounded-xl <?= $i == $current_page ? 'bg-pop-pink text-white -translate-y-2' : 'bg-white' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </main>

    <div id="cart-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden flex items-center justify-center p-4">
        <div class="bg-white border-4 border-black shadow-[10px_10px_0px_#000] md:shadow-[20px_20px_0px_#000] w-full max-w-md p-6 md:p-8 relative rounded-3xl">
            <button id="close-cart-modal" class="absolute -top-4 -right-4 bg-pop-pink border-4 border-black text-white w-10 h-10 flex items-center justify-center font-black rounded-full">X</button>
            <h2 class="text-2xl md:text-4xl font-black mb-6 border-b-4 border-black pb-2 italic">CART 🛒</h2>
            <div id="cart-items-list" class="space-y-3 max-h-[50vh] overflow-y-auto pr-2"></div>
            <div class="mt-6 pt-4 border-t-4 border-black">
                <div class="flex justify-between items-center mb-6">
                    <span class="font-black text-xl italic">TOTAL:</span>
                    <span id="cart-total-amount" class="text-3xl font-black text-pop-pink">฿0.00</span>
                </div>
                <button onclick="location.href='checkout.php'" id="checkout-btn" 
                        class="pop-btn w-full py-4 bg-pop-green font-black text-xl md:text-2xl uppercase italic rounded-2xl disabled:opacity-50">
                    CHECKOUT 🚀
                </button>
            </div>
        </div>
    </div>

    <footer class="mt-10 py-10 text-center border-t-4 border-black bg-white">
        <p class="font-black text-sm md:text-lg">STUNSHOP.TOY &copy; 2026</p>
        <p class="font-black text-sm md:text-lg">เว็บนี้สร้างไว้สำหรับส่งงานเท่านั้น<br>วิทยาลัยอาชีวศึกษาวิทยาลัยนครสวรรค์ &copy;</p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // Mobile Menu
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuClose = document.getElementById('menu-close');
        menuToggle.onclick = () => mobileMenu.classList.add('menu-open');
        menuClose.onclick = () => mobileMenu.classList.remove('menu-open');

        // Cart Logic
        function getCart() { return JSON.parse(localStorage.getItem('game_cart') || '[]'); }
        function saveCart(cart) { localStorage.setItem('game_cart', JSON.stringify(cart)); updateUI(); }

        function addToCart(e, id, title, price) {
            let cart = getCart();
            if (cart.some(item => item.id == id)) { 
                alert('มีในตะกร้าแล้ว! 🕹️'); 
                return; 
            }

            // Animation Feedback
            const btn = e.currentTarget;
            btn.classList.add('btn-bounce');
            setTimeout(() => btn.classList.remove('btn-bounce'), 300);
            spawnEmoji(e.clientX, e.clientY);

            cart.push({ id, title, price });
            saveCart(cart);
        }

        function spawnEmoji(x, y) {
            const emojis = ['🎮', '✨', '🔥', '👾', '🕹️'];
            for(let i=0; i<6; i++) {
                const el = document.createElement('div');
                el.innerText = emojis[Math.floor(Math.random()*emojis.length)];
                el.style.cssText = `position:fixed; left:${x}px; top:${y}px; pointer-events:none; z-index:1000; font-size:24px; transition:all 0.8s ease-out;`;
                document.body.appendChild(el);
                
                const tx = (Math.random() - 0.5) * 200;
                const ty = -100 - Math.random() * 100;
                
                requestAnimationFrame(() => {
                    el.style.transform = `translate(${tx}px, ${ty}px) rotate(${Math.random()*360}deg)`;
                    el.style.opacity = '0';
                });
                setTimeout(() => el.remove(), 800);
            }
        }

        function removeItem(index) {
            let cart = getCart();
            cart.splice(index, 1);
            saveCart(cart);
        }

        function updateUI() {
            const cart = getCart();
            const list = document.getElementById('cart-items-list');
            const totalEl = document.getElementById('cart-total-amount');
            const counts = document.querySelectorAll('.cart-item-count');
            
            counts.forEach(c => {
                c.textContent = cart.length;
                c.style.display = cart.length > 0 ? 'flex' : 'none';
            });

            let total = 0;
            list.innerHTML = cart.length ? '' : '<p class="text-center font-bold opacity-50 py-10">ตะกร้าว่างเปล่า... 🛸</p>';
            
            cart.forEach((item, i) => {
                total += parseFloat(item.price);
                list.innerHTML += `
                    <div class="flex justify-between items-center border-2 border-black p-3 bg-white shadow-[3px_3px_0px_#000] rounded-xl">
                        <div class="flex flex-col"><span class="font-black text-xs uppercase line-clamp-1">${item.title}</span><span class="font-black text-pop-pink text-sm">฿${item.price.toLocaleString()}</span></div>
                        <button onclick="removeItem(${i})" class="bg-black text-white w-6 h-6 flex items-center justify-center rounded-lg text-xs">X</button>
                    </div>`;
            });
            totalEl.textContent = `฿${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
            document.getElementById('checkout-btn').disabled = cart.length === 0;
        }

        // Modal Controls
        const modal = document.getElementById('cart-modal');
        const openBtns = [document.getElementById('open-cart-btn'), document.getElementById('open-cart-btn-mob')];
        openBtns.forEach(btn => btn.onclick = () => { updateUI(); modal.classList.remove('hidden'); });
        document.getElementById('close-cart-modal').onclick = () => modal.classList.add('hidden');

        document.addEventListener('DOMContentLoaded', updateUI);
    </script>
</body>
</html>