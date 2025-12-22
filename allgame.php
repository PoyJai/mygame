<?php
// ต้องเรียกใช้ session_start() ก่อนการส่งออกใด ๆ
session_start();

// !!! เพิ่มการเชื่อมต่อฐานข้อมูล !!!
require_once 'db_config.php'; 

// 1. ตรวจสอบการออกจากระบบ (Logout Logic)
if (isset($_GET['logout'])) {
    session_destroy(); // ทำลาย Session ทั้งหมด
    header('location: login.php'); 
    exit;
}

// 2. ตรวจสอบสถานะการเข้าสู่ระบบ (Authentication Check)
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["username"])) {
    // ผู้ใช้ยังไม่ได้เข้าสู่ระบบ, Redirect ไปหน้า login
    $_SESSION["error"] = "กรุณาเข้าสู่ระบบก่อนเพื่อเข้าใช้งาน!";
    header("Location: login.php");
    exit; 
}

// กำหนดตัวแปรสำหรับแสดงผลใน HTML
$is_logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$current_username = $is_logged_in ? htmlspecialchars($_SESSION["username"]) : "Guest"; 

// --- *** LOGIC สำหรับ Pagination และ Database *** ---

// 1. กำหนดค่า Pagination
$games_per_page = 16; 
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1; 

// 2. เชื่อมต่อฐานข้อมูลอีกครั้งสำหรับนับจำนวน (db_config.php เชื่อมต่อไว้แล้ว)
// ถ้า $conn มีปัญหา จะเกิด Error ที่นี่

// 2. นับจำนวนเกมทั้งหมดในตาราง 'games' 
// ตรวจสอบว่า $conn ถูกตั้งค่าและทำงานได้ก่อน
$total_games = 0;
if (isset($conn) && $conn->ping()) {
    $total_games_query = $conn->query("SELECT COUNT(*) AS total FROM games");
    if ($total_games_query) {
        $total_games = $total_games_query->fetch_assoc()['total'];
    }
} else {
    // จัดการข้อผิดพลาดการเชื่อมต่อ
    $_SESSION["error"] = "ไม่สามารถเชื่อมต่อฐานข้อมูลได้! กรุณาตรวจสอบ db_config.php";
    // ถ้าต้องการให้รันต่อโดยไม่มีเกม: $total_games = 0;
    // หรือถ้าต้องการหยุด: die("Database Connection Error.");
}


// 3. คำนวณจำนวนหน้ารวมทั้งหมด
$calculated_total_pages = ceil($total_games / $games_per_page);
$total_pages = min(5, $calculated_total_pages); // จำกัดสูงสุดที่ 5 หน้า

if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages; // ป้องกันเลขหน้าที่เกิน
}

// 4. คำนวณ OFFSET (จุดเริ่มต้นในการดึงข้อมูล)
$offset = ($current_page - 1) * $games_per_page;

// 5. ดึงข้อมูลเกมสำหรับหน้าปัจจุบัน
$games = [];
if ($total_games > 0 && isset($conn) && $conn->ping()) {
    $sql = "SELECT id, title, description, genre, image_url, price FROM games LIMIT $games_per_page OFFSET $offset";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $games[] = $row;
        }
    }
}


// !!! ปิดการเชื่อมต่อฐานข้อมูลเมื่อเสร็จสิ้นการใช้งาน PHP ด้านบน !!!
if (isset($conn)) $conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คลังเกมทั้งหมด</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#4F46E5', // Indigo-600
                        'secondary': '#F97316', // Orange-600
                        'background': '#1F2937', // Gray-800
                        'card': '#374151', // Gray-700
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
            background-image: url('https://m.media-amazon.com/images/S/pv-target-images/6fb04fc002b005a28a0d2b2bc1a1e9ca06c9dd05a7e5d006033776c05a44d706.jpg');
            background-size: cover;
            background-position: center;
        }
        
        .game-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

    <header class="sticky top-0 z-50 bg-background/90 backdrop-blur-sm shadow-lg">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-primary">
                <a href="index.php">
                Stun<span class="text-secondary">Shop</span>
                </a>
            </div>
            <div class="hidden md:flex space-x-8 text-lg font-medium items-center">
                <a href="index.php" class="hover:text-primary transition duration-150">หน้าแรก</a>
                <a href="allgame.php" class="hover:text-primary transition duration-150">เกมทั้งหมด</a>
                <a href="contact.php" class="hover:text-primary transition duration-150">ติดต่อห</a>
    
                <button id="open-cart-btn" class="relative text-gray-300 hover:text-secondary p-2 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span id="cart-item-count" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-secondary rounded-full">0</span>
                </button>
    
                <div id="auth-status-container">
                    <?php if ($is_logged_in): ?>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-white/80 hidden lg:block">สวัสดี, <?= $current_username ?></span>
                            <a href="allgame.php?logout=1" class="px-4 py-2 bg-gray-600 rounded-full text-white font-semibold hover:bg-gray-700 transition duration-300">
                                ออกจากระบบ
                            </a>
                        </div>
                    <?php else: ?>
                        <button id="auth-button-desktop" class="px-4 py-2 bg-secondary rounded-full text-white font-semibold hover:bg-orange-700 transition duration-300" onclick="window.location.href='login.php'">
                            เข้าสู่ระบบ / สมัคร
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <button id="menu-button" class="md:hidden focus:outline-none p-2 rounded-lg hover:bg-card">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
            
            <div id="mobile-menu" class="hidden md:hidden bg-card/95 py-2">
                <a href="index.php" class="block px-4 py-2 text-sm hover:bg-gray-600 transition duration-150">หน้าแรก</a>
                <a href="allgame.php" class="block px-4 py-2 text-sm text-primary font-bold hover:bg-gray-600 transition duration-150">เกมทั้งหมด</a>
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-600 transition duration-150">บทความ</a>
                <div id="auth-mobile-status" class="px-4 py-2">
                    <?php if ($is_logged_in): ?>
                        <div class="text-sm font-medium text-white/80 mb-2 text-center">สวัสดี, <?= $current_username ?></div>
                        <a href="allgame.php?logout=1" class="w-full block text-center px-4 py-2 bg-gray-600 rounded-full text-white font-semibold hover:bg-gray-700 transition duration-300">
                            ออกจากระบบ
                        </a>
                    <?php else: ?>
                        <button id="auth-button-mobile" class="w-full px-4 py-2 bg-secondary rounded-full text-white font-semibold hover:bg-orange-700 transition duration-300" onclick="window.location.href='login.php'">
                            เข้าสู่ระบบ / สมัคร
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <h1 class="text-4xl md:text-5xl font-extrabold text-center mb-6 text-white">
            <span class="text-secondary">คลัง</span>เกมทั้งหมด
        </h1>
        <p class="text-center text-gray-400 max-w-2xl mx-auto mb-16">
            สำรวจคอลเลกชันเกมทั้งหมดของเรา ซึ่งคัดสรรมาอย่างดีเพื่อความสวยงามและการเล่นที่น่าสนใจ
        </p>

        <div class="flex flex-col md:flex-row justify-center items-center gap-4 mb-12">
            <input type="text" placeholder="ค้นหาตามชื่อเกม..." class="w-full md:w-80 px-4 py-3 bg-card border border-gray-600 rounded-full text-white focus:ring-primary focus:border-primary transition duration-150">
            <select class="w-full md:w-48 px-4 py-3 bg-card border border-gray-600 rounded-full text-white focus:ring-primary focus:border-primary appearance-none transition duration-150">
                <option value="">-- กรองตามประเภท --</option>
                <option value="Action">แอ็กชัน</option>
                <option value="Adventure">ผจญภัย</option>
                <option value="Simulation">จำลอง</option>
                <option value="Strategy">กลยุทธ์</option>
                <option value="Indie">อินดี้</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    
        <?php if (!empty($games)): ?>
            <?php foreach ($games as $game): ?>
                <?php 
                    $game_id = htmlspecialchars($game['id']);
                    $game_title = htmlspecialchars($game['title']);
                    $game_desc = htmlspecialchars($game['description']);
                    $game_genre = htmlspecialchars($game['genre']);
                    // ราคาสำหรับส่งให้ JS
                    $game_price_float = (float)$game['price']; 
                    $game_image = empty($game['image_url']) ? 'https://placehold.co/400x250/374151/ffffff?text=No+Image' : htmlspecialchars($game['image_url']);
                    
                    $genre_class = 'bg-primary/20 text-primary'; 
                    if (strpos($game_genre, 'Survival') !== false) $genre_class = 'bg-secondary/20 text-secondary';
                    if (strpos($game_genre, 'Adventure') !== false) $genre_class = 'bg-green-500/20 text-green-500';
                    if (strpos($game_genre, 'Racing') !== false) $genre_class = 'bg-yellow-500/20 text-yellow-500';
                    if (strpos($game_genre, 'Strategy') !== false) $genre_class = 'bg-amber-600/20 text-amber-600';
                ?>
                
                <div class="game-card bg-card rounded-xl overflow-hidden shadow-2xl block relative">
                    <a href="game_detail.php?id=<?= $game_id ?>">
                        <img src="<?= $game_image ?>" alt="" class="w-full h-48 object-cover">
                        <div class="p-5">
                            <h3 class="text-xl font-bold text-white mb-2"><?= $game_title ?></h3>
                            <p class="text-gray-400 text-sm mb-3 truncate"><?= $game_desc ?></p>
                            <span class="inline-block <?= $genre_class ?> text-xs font-semibold px-3 py-1 rounded-full">
                                <?= $game_genre ?>
                            </span>
                        </div>
                    </a>
                    </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 col-span-full text-center py-10">ไม่พบเกมที่พร้อมใช้งานในหน้านี้ (กรุณาตรวจสอบฐานข้อมูล)</p>
        <?php endif; ?>
    
        </div>
    </main>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-16 flex justify-center">
        <nav class="flex items-center space-x-2" aria-label="Pagination">
            
            <?php
            $prev_page = $current_page - 1;
            $prev_class = ($current_page <= 1) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-600';
            $prev_href = ($current_page <= 1) ? '#' : "allgame.php?page={$prev_page}";
            ?>
            <a href="<?= $prev_href ?>" class="px-3 py-2 rounded-lg text-gray-400 bg-card transition duration-150 border border-gray-600 <?= $prev_class ?>">
                <span class="sr-only">Previous</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div class="flex space-x-2">
                <?php 
                // Loop แสดงตัวเลขหน้า 1 ถึง $total_pages (ซึ่งถูกจำกัดไว้ที่ 5)
                for ($i = 1; $i <= $total_pages; $i++): 
                    $page_class = ($i == $current_page) ? 'text-white bg-primary font-bold shadow-lg' : 'text-gray-300 bg-card hover:bg-gray-600';
                    $page_href = "allgame.php?page={$i}";
                ?>
                    <a href="<?= $page_href ?>" 
                        aria-current="<?= ($i == $current_page) ? 'page' : 'false' ?>" 
                        class="px-4 py-2 rounded-lg transition duration-150 <?= $page_class ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

            <?php
            $next_page = $current_page + 1;
            $next_class = ($current_page >= $total_pages) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-600';
            $next_href = ($current_page >= $total_pages) ? '#' : "allgame.php?page={$next_page}";
            ?>
            <a href="<?= $next_href ?>" class="px-3 py-2 rounded-lg text-gray-400 bg-card transition duration-150 border border-gray-600 <?= $next_class ?>">
                <span class="sr-only">Next</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </nav>
    </div>
    
    <footer class="bg-card border-t border-gray-700 mt-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-gray-400">
            <div class="flex flex-col md:flex-row justify-center space-y-2 md:space-y-0 md:space-x-8 mb-4">
                <a href="#" class="hover:text-primary transition duration-150">นโยบายความเป็นส่วนตัว</a>
                <a href="#" class="hover:text-primary transition duration-150">ข้อกำหนดการใช้งาน</a>
            </div>
            <p>&copy; 2025 โลกแห่งเกมอันงดงาม (AESTHETIC.GAMES) | สงวนลิขสิทธิ์</p>
        </div>
    </footer>
    
    <div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-80 z-[110] hidden flex items-center justify-center p-4 h-screen">
        <div class="bg-card w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 rounded-xl shadow-2xl relative border border-secondary/50">
            
            <button id="close-cart-modal-btn" class="absolute top-4 right-4 text-gray-400 hover:text-white transition duration-150">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h2 class="text-3xl font-bold text-secondary mb-6 text-center">🛒 รายการสั่งซื้อ (Cart)</h2>
            
            <div id="cart-items-list" class="space-y-4 min-h-[100px]">
                <p class="text-center text-gray-500 py-10">ตะกร้าว่างเปล่า</p>
            </div>

            <div id="cart-summary" class="mt-8 pt-4 border-t border-gray-700">
                <div class="flex justify-between items-center text-xl font-bold mb-4">
                    <span class="text-white">ราคารวม:</span>
                    <span id="cart-total-amount" class="text-secondary">฿0.00</span>
                </div>
                <button id="checkout-btn" class="w-full px-4 py-3 bg-primary rounded-lg text-white font-bold hover:bg-indigo-700 transition duration-300 disabled:opacity-50" disabled>
                    ดำเนินการชำระเงิน
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // **************** Cart UI & Logic Variables ****************
        const cartModal = document.getElementById('cart-modal');
        const openCartBtn = document.getElementById('open-cart-btn');
        const closeCartModalBtn = document.getElementById('close-cart-modal-btn');
        const cartItemCount = document.getElementById('cart-item-count');
        const cartItemsList = document.getElementById('cart-items-list');
        const cartTotalAmount = document.getElementById('cart-total-amount');
        const checkoutBtn = document.getElementById('checkout-btn');
        const addToCartBtnCards = document.querySelectorAll('.add-to-cart-btn-card'); // สำหรับปุ่มบน Card (ถ้าเปิดใช้งาน)

        // 1. ดึง/อัปเดตสถานะตะกร้าจาก Local Storage
        const getCartFromStorage = () => {
            const cartString = localStorage.getItem('game_cart');
            return cartString ? JSON.parse(cartString) : [];
        };

        const saveCartToStorage = (cart) => {
            localStorage.setItem('game_cart', JSON.stringify(cart));
        };
        
        // 2. ฟังก์ชัน Render Cart
        const renderCart = (cart) => {
            let total = 0;
            cartItemsList.innerHTML = '';

            if (cart.length === 0) {
                cartItemsList.innerHTML = '<p class="text-center text-gray-500 py-10">ตะกร้าว่างเปล่า</p>';
                checkoutBtn.disabled = true;
            } else {
                checkoutBtn.disabled = false;
                cart.forEach(item => {
                    const price = item.price ? parseFloat(item.price) : 0.00; 
                    total += price;
                    
                    const itemHtml = `
                        <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg border border-gray-600">
                            <span class="text-white font-medium">${item.title}</span>
                            <div class="flex items-center space-x-3">
                                <span class="text-secondary font-bold">฿${price.toFixed(2)}</span>
                                <button data-id="${item.id}" class="remove-from-cart-btn text-red-400 hover:text-red-500 transition duration-150" title="ลบออกจากตะกร้า">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    `;
                    cartItemsList.innerHTML += itemHtml;
                });
            }
            
            // อัปเดตจำนวนสินค้าบนไอคอน
            cartItemCount.textContent = cart.length > 99 ? '99+' : cart.length.toString(); 
            cartTotalAmount.textContent = `฿${total.toFixed(2)}`;
            attachRemoveListeners(); 
        };

        // 3. ฟังก์ชันเพิ่มสินค้าลงตะกร้า (Local Storage)
            e.preventDefault();
            e.stopPropagation(); // สำคัญมาก เพื่อไม่ให้คลิกปุ่มแล้ววิ่งไปหน้า game_detail
            
            const button = e.currentTarget;
            const gameId = button.dataset.id;
            const gameTitle = button.dataset.title;
            const gamePrice = button.dataset.price;
            
            const newItem = {
                id: gameId,
                title: gameTitle,
                price: gamePrice
            };
            
            let cart = getCartFromStorage();

            const exists = cart.some(item => item.id === gameId);


        // 4. จัดการการลบสินค้า (Local Storage)
        const handleRemove = (e) => {
            e.preventDefault();
            e.stopPropagation(); 
            const removeBtn = e.currentTarget;
            const gameId = removeBtn.dataset.id;
            
            if (confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าใช่หรือไม่?')) {
                let cart = getCartFromStorage();
                cart = cart.filter(item => item.id !== gameId);
                saveCartToStorage(cart);
                renderCart(cart); 
            }
        };

        // 5. แนบ Event Listener ให้ปุ่มลบ
        const attachRemoveListeners = () => {
            document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
                button.removeEventListener('click', handleRemove); 
                button.addEventListener('click', handleRemove);
            });
        };
            
        // **************** Event Listeners และ Initialization ****************
        document.addEventListener('DOMContentLoaded', () => {

            const menuButton = document.getElementById('menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            menuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Event: เปิด Modal ตะกร้า
            if (openCartBtn) {
                openCartBtn.addEventListener('click', () => {
                    renderCart(getCartFromStorage()); 
                    if(cartModal) cartModal.classList.remove('hidden');
                });
            }
            
            // Event: ปิด Modal ตะกร้า
            if (closeCartModalBtn) {
                closeCartModalBtn.addEventListener('click', () => {
                    if(cartModal) cartModal.classList.add('hidden');
                });
            }
            
            // Event: ปิด Modal ตะกร้า เมื่อคลิกนอกกรอบ
            if (cartModal) {
                cartModal.addEventListener('click', (e) => {
                    if (e.target === cartModal) {
                        cartModal.classList.add('hidden');
                    }
                });
            }
            
            // Event: Add to Cart สำหรับปุ่มบน Card (ถ้าเปิดใช้งาน)
            // ถ้าคุณเปิดใช้ปุ่ม Add to Cart บน Card (ตามโค้ดที่ผม comment ไว้) ให้แนบ Event นี้ด้วย
            addToCartBtnCards.forEach(button => {
                button.addEventListener('click', handleAddToCart);
            });

            // *** สำคัญ: เรียกใช้ฟังก์ชันนี้เมื่อโหลดหน้าเสร็จเพื่อแสดงจำนวนสินค้าตั้งแต่แรก ***
            renderCart(getCartFromStorage());
            // Event สำหรับปุ่ม Checkout
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => {
                const cart = getCartFromStorage(); // ตรวจสอบตะกร้าอีกครั้งก่อนไป
                if (cart.length > 0) {
                    window.location.href = 'checkout.php'; // <--- เปลี่ยนให้ Redirect ไปหน้า checkout
                } else {
                    alert('ตะกร้าสินค้าว่างเปล่า ไม่สามารถดำเนินการชำระเงินได้!');
                }
            });
        } 
        });
    </script>
</body>
</html>