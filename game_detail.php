<?php
session_start();
require_once 'db_config.php'; 

$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($game_id === 0) { header("Location: allgame.php"); exit; }

$sql = "SELECT * FROM games WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();
if (!$game) { header("Location: allgame.php"); exit; }

$slides = [
    !empty($game['image_url']) ? $game['image_url'] : 'https://placehold.co/800x800?text=IMAGE+1',
    !empty($game['image_url_2']) ? $game['image_url_2'] : 'https://placehold.co/800x800?text=IMAGE+2',
    !empty($game['image_url_3']) ? $game['image_url_3'] : 'https://placehold.co/800x800?text=IMAGE+3'
];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($game['title']) ?> - STUNSHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: #FFFCF2; 
            background-image: 
                linear-gradient(45deg, #e5e5f7 25%, transparent 25%), 
                linear-gradient(-45deg, #e5e5f7 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, #e5e5f7 75%), 
                linear-gradient(-45deg, transparent 75%, #e5e5f7 75%);
            background-size: 40px 40px;
            background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
        }

        /* บังคับขอบเหลี่ยมและเส้นหนา */
        * { border-radius: 0 !important; }
        
        .neo-border { border: 4px solid #000; }
        .neo-shadow { box-shadow: 8px 8px 0px #000; }
        .neo-shadow-lg { box-shadow: 12px 12px 0px #000; }
        .neo-shadow-hover:hover { 
            box-shadow: 16px 16px 0px #000; 
            transform: translate(-4px, -4px); 
        }

        /* เอฟเฟกต์ตัวอักษรขอบดำ */
        .text-stroke {
            -webkit-text-stroke: 2px black;
            color: white;
        }

        .marquee {
            white-space: nowrap;
            overflow: hidden;
            background: #000;
            color: #fff;
            padding: 10px 0;
        }
        .marquee div {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .swiper-button-next, .swiper-button-prev {
            background: #FFEF00 !important;
            border: 3px solid #000 !important;
            color: #000 !important;
            padding: 25px !important;
        }
    </style>
</head>
<body class="text-black overflow-x-hidden">

    <header class="sticky top-0 z-50 bg-white border-b-8 border-black">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="allgame.php" class="text-4xl font-black italic flex items-center">
                <span class="bg-pop-yellow border-2 border-pink px-2 py-0.5 italic shadow-[3px_3px_0px_#000]">STUN</span>
                <span class="text-black">SHOP</span>
            </a>
            <button id="open-cart-btn" class="bg-[#2DFF81] p-3 neo-border neo-shadow hover:bg-white transition-all relative">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span id="cart-item-count" class="absolute -top-3 -right-3 bg-[#FF48B0] text-white font-black h-8 w-8 flex items-center justify-center neo-border">0</span>
            </button>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-7xl mx-auto mb-12">
            <a href="allgame.php" class="bg-white px-8 py-4 font-black uppercase italic neo-border neo-shadow-hover inline-flex items-center transition-all">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Archive
            </a>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-0 bg-white neo-border neo-shadow-lg overflow-hidden">
                
                <div class="w-full lg:w-8/12 border-b-4 lg:border-b-0 lg:border-r-4 border-black">
                    <div class="aspect-video bg-black relative">
                        <?php if(!empty($game['video_url'])): ?>
                            <?php 
                                $vid = $game['video_url'];
                                $connector = (strpos($vid, '?') !== false) ? '&' : '?';
                                $final_url = $vid . $connector . "autoplay=1&mute=1&loop=1&playlist=" . basename(parse_url($vid, PHP_URL_PATH));
                            ?>
                            <iframe class="w-full h-full" src="<?= $final_url ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-white font-black italic">VIDEO PREVIEW UNAVAILABLE</div>
                        <?php endif; ?>
                        
                        <div class="absolute top-4 left-4 bg-[#FF48B0] text-white px-3 py-1 font-black neo-border rotate-[-2deg]">LIVE_YouTube</div>
                    </div>

                    <div class="marquee neo-border border-x-0">
                        <div class="text-xl font-black uppercase italic italic">
                             • NOW PLAYING: <?= htmlspecialchars($game['title']) ?> • GET IT NOW • LOWEST PRICE GUARANTEED • STUNSHOP EXCLUSIVE • 
                             • NOW PLAYING: <?= htmlspecialchars($game['title']) ?> • GET IT NOW • LOWEST PRICE GUARANTEED • STUNSHOP EXCLUSIVE • 
                        </div>
                    </div>

                    <div class="p-8 md:p-12">
                        <div class="flex items-center gap-4 mb-6">
                            <span class="bg-[#00C2FF] text-white px-6 py-2 text-xl font-black uppercase neo-border rotate-1">
                                <?= htmlspecialchars($game['genre']) ?>
                            </span>
                            <span class="font-black text-2xl italic">ID: #<?= $game['id'] ?></span>
                        </div>
                        <h1 class="text-5xl md:text-7xl font-black uppercase italic mb-8 leading-none">
                            <span class="block"><?= htmlspecialchars($game['title']) ?></span>
                        </h1>
                        <p class="text-2xl font-bold mb-10 text-gray-800 border-l-8 border-[#FFEF00] pl-6 leading-tight">
                            "<?= htmlspecialchars($game['description']) ?>"
                        </p>
                        <div class="prose prose-xl max-w-none font-medium leading-relaxed mb-12">
                            <?= nl2br(htmlspecialchars($game['long_description'])) ?>
                        </div>

                        <div class="bg-[#2DFF81] p-8 neo-border neo-shadow flex flex-wrap items-center justify-between gap-6">
                            <div>
                                <p class="font-black uppercase text-sm mb-2">Community Score</p>
                                <div class="text-7xl font-black italic leading-none"><?= number_format($game['rating'], 1) ?>/10</div>
                            </div>
                            <div class="flex gap-2">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <div class="w-12 h-12 neo-border bg-white flex items-center justify-center">
                                        <svg class="w-8 h-8 <?= ($i <= floor($game['rating'] / 2)) ? 'text-[#FF7A00] fill-current' : 'text-gray-300' ?>" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-4/12 bg-[#FFEF00]/20">
                    <div class="swiper mySwiper w-full h-[400px] border-b-4 border-black">
                        <div class="swiper-wrapper">
                            <?php foreach($slides as $url): ?>
                            <div class="swiper-slide">
                                <img src="<?= $url ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <div class="p-8 sticky top-24">
                        <div class="bg-white p-8 neo-border neo-shadow mb-8 relative">
                            <div class="absolute -top-5 -right-5 bg-black text-white px-4 py-1 font-black italic rotate-12 neo-border">TOP SELLER</div>
                            <p class="font-black uppercase opacity-60">Buy License Now</p>
                            <div class="text-7xl font-black tracking-tighter text-[#FF48B0] my-4">
                                ฿<?= number_format($game['price'], 0) ?>
                            </div>
                            <button id="add-to-cart-btn" 
                                    data-id="<?= $game['id'] ?>" 
                                    data-title="<?= htmlspecialchars($game['title']) ?>"
                                    data-price="<?= $game['price'] ?>"
                                    class="w-full py-6 bg-[#FF7A00] text-white font-black text-3xl uppercase italic neo-border neo-shadow-hover hover:bg-black transition-all flex items-center justify-center gap-4">
                                <span>ADD TO CART</span>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-black text-white p-4 font-black uppercase flex justify-between italic">
                                <span>Developer:</span>
                                <span class="text-[#2DFF81]"><?= htmlspecialchars($game['developer'] ?? 'UNKNOWN') ?></span>
                            </div>
                            <div class="bg-white p-4 neo-border font-black uppercase flex justify-between italic shadow-[4px_4px_0px_#000]">
                                <span>Released:</span>
                                <span><?= !empty($game['release_date']) ? date('M d, Y', strtotime($game['release_date'])) : 'TBA' ?></span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-8">
                                <div class="p-4 bg-white neo-border text-center font-black italic shadow-[4px_4px_0px_#000]">PC / WINDOWS</div>
                                <div class="p-4 bg-white neo-border text-center font-black italic shadow-[4px_4px_0px_#000]">DIGITAL KEY</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="cart-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[110] hidden flex items-center justify-center p-4">
        <div class="bg-white neo-border neo-shadow-lg w-full max-w-md p-8 relative">
            <button id="close-cart-modal-btn" class="absolute -top-6 -right-6 bg-[#FF48B0] text-white w-12 h-12 font-black neo-border hover:rotate-90 transition-all">X</button>
            <h2 class="text-4xl font-black mb-8 italic uppercase border-b-8 border-black pb-2">Your Haul 📦</h2>
            <div id="cart-items-list" class="space-y-4 max-h-[40vh] overflow-y-auto pr-4"></div>
            <div class="mt-8 pt-6 border-t-8 border-black">
                <div class="flex justify-between items-center mb-8">
                    <span class="font-black text-2xl italic">TOTAL:</span>
                    <span id="cart-total-amount" class="text-5xl font-black text-[#FF48B0]">฿0</span>
                </div>
                <button class="w-full py-5 bg-[#2DFF81] font-black text-2xl uppercase italic neo-border neo-shadow hover:translate-y-1 transition-all">Checkout Now 🔥</button>
            </div>
        </div>
    </div>

    <footer class="mt-24 py-16 bg-black text-white text-center border-t-8 border-[#FFEF00]">
        <div class="text-6xl font-black italic mb-4 opacity-20">STUNSHOP STUNSHOP STUNSHOP</div>
        <p class="font-bold text-xl uppercase tracking-widest">© 2026 STUNSHOP TOY ART - NAC VOCC</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        const swiper = new Swiper('.mySwiper', { 
            loop: true, 
            autoplay: { delay: 3000 },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' } 
        });

        // Cart Logic
        const getCart = () => JSON.parse(localStorage.getItem('game_cart') || '[]');
        const renderCart = () => {
            const cart = getCart();
            const list = document.getElementById('cart-items-list');
            const totalEl = document.getElementById('cart-total-amount');
            document.getElementById('cart-item-count').textContent = cart.length;
            let total = 0;
            list.innerHTML = cart.length ? '' : '<p class="text-center font-black py-10 italic">CART IS EMPTY... GO BUY SOMETHING!</p>';
            cart.forEach((item, i) => {
                total += parseFloat(item.price);
                list.innerHTML += `
                    <div class="flex justify-between items-center bg-gray-100 p-4 neo-border shadow-[4px_4px_0px_#000]">
                        <span class="font-black uppercase truncate w-1/2">${item.title}</span>
                        <span class="font-black text-[#FF48B0]">฿${parseFloat(item.price).toLocaleString()}</span>
                        <button onclick="removeItem(${i})" class="bg-black text-white px-3 py-1 font-black">X</button>
                    </div>`;
            });
            totalEl.textContent = `฿${total.toLocaleString()}`;
        };

        window.removeItem = (idx) => { let c = getCart(); c.splice(idx, 1); localStorage.setItem('game_cart', JSON.stringify(c)); renderCart(); };
        
        document.getElementById('add-to-cart-btn').onclick = function() {
            let c = getCart();
            if(!c.some(i => i.id == this.dataset.id)) {
                c.push({ id: this.dataset.id, title: this.dataset.title, price: this.dataset.price });
                localStorage.setItem('game_cart', JSON.stringify(c));
            }
            renderCart();
            document.getElementById('cart-modal').classList.remove('hidden');
        };

        document.getElementById('open-cart-btn').onclick = () => document.getElementById('cart-modal').classList.remove('hidden');
        document.getElementById('close-cart-modal-btn').onclick = () => document.getElementById('cart-modal').classList.add('hidden');
        renderCart();
    </script>
</body>
</html>