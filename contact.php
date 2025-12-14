<?php
/**
 * ========================================
 * PHP LOGIC: Contact Page Processing
 * ========================================
 * จัดการ Session, การเชื่อมต่อฐานข้อมูล, การ Logout 
 * และการประมวลผลฟอร์มติดต่อเรา (Contact Form)
 */

session_start();
// ตรวจสอบและดึงไฟล์การตั้งค่า DB (สมมติว่าไฟล์นี้จัดการการเชื่อมต่อ)
require_once 'db_config.php'; 

// 1. ตรรกะการ Logout
if (isset($_GET['logout'])) {
    session_destroy(); 
    header('location: login.php'); 
    exit;
}

// 2. สถานะผู้ใช้
$is_logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$current_username = $is_logged_in ? htmlspecialchars($_SESSION["username"]) : "Guest"; 

// 3. ตัวแปรสำหรับแสดงผลสถานะ (Status Message)
$status_message = "";
$status_type = ""; 
$name = '';
$email = '';
$subject = '';
$message = '';

// 4. การประมวลผลฟอร์ม (POST Request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับและทำความสะอาดข้อมูล (Sanitize)
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
    $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
    $subject = isset($_POST['subject']) ? trim(htmlspecialchars($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';
    
    // ตรวจสอบความถูกต้องของข้อมูล (Validation)
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $status_message = "กรุณากรอกข้อมูลให้ครบถ้วนทุกช่อง";
        $status_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status_message = "รูปแบบอีเมลไม่ถูกต้อง";
        $status_type = "error";
    } else {
        // *** ส่วนนี้ควรมีการบันทึกข้อมูลลงฐานข้อมูล/ส่งอีเมลจริง ***
        // จำลองการส่งสำเร็จ
        $status_message = "ขอบคุณครับ! ข้อความของคุณถูกส่งเรียบร้อยแล้ว เราจะติดต่อกลับโดยเร็วที่สุด";
        $status_type = "success";

        // ล้างค่าฟอร์มหากส่งสำเร็จ
        if ($status_type == "success") {
            $name = $email = $subject = $message = '';
        }
    }
}

// 5. ปิดการเชื่อมต่อฐานข้อมูล (ถ้ามี)
if (isset($conn) && is_object($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดต่อเรา - โลกแห่งเกมอันงดงาม</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        /**
         * ========================================
         * Tailwind Custom Configuration
         * ========================================
         */
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#4F46E5',     // Indigo-600
                        'secondary': '#F97316',   // Orange-600
                        'dark-bg': '#1F2937',     // Gray-800
                        'dark-card': '#374151',   // Gray-700
                        'light-bg': '#F3F4F6',    // Gray-100
                        'light-card': '#FFFFFF',  // White
                    },
                    fontFamily: {
                        sans: ['Inter', 'Tahoma', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        /**
         * ========================================
         * Custom CSS for Background & Theme Overrides
         * ========================================
         */
        #page-body {
            /* ภาพพื้นหลัง (ปรับให้เป็นภาพที่เข้ากับธีมเกม/Aesthetic) */
            background-image: url('https://images.unsplash.com/photo-1542385152-32b55b9e0f6c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'); 
            background-size: cover;
            background-attachment: fixed; 
            background-position: center;
            position: relative;
        }
        
        /* Overlay เพื่อให้เนื้อหาหลักเด่นขึ้น */
        #page-body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            /* Dark Mode Overlay + Gradient Accent */
            background: linear-gradient(rgba(31, 41, 55, 0.95), rgba(31, 41, 55, 0.85)),
                        radial-gradient(circle at top right, rgba(79, 70, 229, 0.1), transparent 50%),
                        radial-gradient(circle at bottom left, rgba(249, 115, 22, 0.1), transparent 50%);
            z-index: -1;
            transition: background 0.5s ease;
        }
        
        /* Light Mode adjustment Overlay */
        .light#page-body::before {
            background: linear-gradient(rgba(243, 244, 246, 0.9), rgba(243, 244, 246, 0.95)), 
                        radial-gradient(circle at top right, rgba(79, 70, 229, 0.05), transparent 50%),
                        radial-gradient(circle at bottom left, rgba(249, 115, 22, 0.05), transparent 50%);
        }

        /* ปุ่มหลัก (Primary Button Styles) */
        .btn-primary {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.5), 0 4px 6px -4px rgba(79, 70, 229, 0.5);
        }

        /* Input Field Base Styles (Dark Mode Default) */
        .theme-input {
            background-color: rgba(255, 255, 255, 0.05); 
            border-color: rgba(255, 255, 255, 0.1);
            color: #F3F4F6;
            transition: all 0.2s ease;
        }
        .theme-input:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 1px #4F46E5;
        }
        /* Input Field Light Mode Overrides */
        .light .theme-input {
            background-color: #F9FAFB;
            border-color: #E5E7EB;
            color: #1F2937;
        }
        .light .theme-input:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 1px #4F46E5;
        }
    </style>
</head>
<body id="page-body" class="bg-dark-bg text-gray-100 relative">

    <header class="sticky top-0 z-50 bg-dark-bg/90 backdrop-blur-sm shadow-lg" data-theme-bg="header">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-primary">
                AESTHETIC<span class="text-secondary">.GAMES</span>
            </div>
            
            <div class="hidden md:flex space-x-8 text-lg font-medium items-center">
                <a href="index.php" class="hover:text-primary transition duration-150">หน้าแรก</a>
                <a href="allgame.php" class="hover:text-primary transition duration-150">เกมทั้งหมด</a>
                <a href="#" class="hover:text-primary transition duration-150">บทความ</a>
                <a href="contact.php" class="text-primary font-bold transition duration-150">ติดต่อเรา</a> 

                <button id="theme-switch-btn" class="relative text-gray-300 hover:text-secondary p-2 transition duration-150" title="สลับธีม (Dark/Light)">
                    <svg id="theme-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
        
                <button id="open-cart-btn" class="relative text-gray-300 hover:text-secondary p-2 transition duration-150" title="ตะกร้าสินค้า">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span id="cart-item-count" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-secondary rounded-full">0</span>
                </button>
        
                <div id="auth-status-container">
                    <?php if ($is_logged_in): ?>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-white/80 hidden lg:block">สวัสดี, <?= $current_username ?></span>
                            <a href="contact.php?logout=1" class="px-4 py-2 bg-gray-600 rounded-full text-white font-semibold hover:bg-gray-700 transition duration-300">
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
            
            <button id="menu-button" class="md:hidden focus:outline-none p-2 rounded-lg hover:bg-dark-card">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
            
        </nav>
        
        <div id="mobile-menu" class="hidden md:hidden absolute w-full bg-dark-card/95 backdrop-blur-sm py-2 shadow-lg border-t border-gray-700" data-theme-bg="card">
            <a href="index.php" class="block px-4 py-2 text-sm text-white hover:bg-gray-600 transition duration-150">หน้าแรก</a>
            <a href="allgame.php" class="block px-4 py-2 text-sm text-white hover:bg-gray-600 transition duration-150">เกมทั้งหมด</a>
            <a href="#" class="block px-4 py-2 text-sm text-white hover:bg-gray-600 transition duration-150">บทความ</a>
            <a href="contact.php" class="block px-4 py-2 text-sm text-primary font-bold hover:bg-gray-600 transition duration-150">ติดต่อเรา</a>
            <div id="auth-mobile-status" class="px-4 py-2 border-t border-gray-700 mt-2 pt-2">
                <?php if ($is_logged_in): ?>
                    <div class="text-sm font-medium text-white/80 mb-2 text-center">สวัสดี, <?= $current_username ?></div>
                    <a href="contact.php?logout=1" class="w-full block text-center px-4 py-2 bg-gray-600 rounded-full text-white font-semibold hover:bg-gray-700 transition duration-300">
                        ออกจากระบบ
                    </a>
                <?php else: ?>
                    <button id="auth-button-mobile" class="w-full px-4 py-2 bg-secondary rounded-full text-white font-semibold hover:bg-orange-700 transition duration-300" onclick="window.location.href='login.php'">
                        เข้าสู่ระบบ / สมัคร
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 relative">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 text-white drop-shadow-lg [text-shadow:0_0_8px_rgba(79,70,229,0.8)]">
                    📞 ติดต่อ <span class="text-primary">AESTHETIC<span class="text-secondary">.GAMES</span></span>
                </h1>
                <p class="text-center text-gray-200 md:text-xl mb-12">
                    มีคำถาม ข้อเสนอแนะ หรือต้องการความช่วยเหลือ? ทีมงานของเราพร้อมให้บริการ
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div class="space-y-8 p-6 lg:p-0">
                    <h2 class="text-3xl font-bold text-primary border-b border-primary/50 pb-2">ช่องทางหลัก</h2>
                    
                    <div class="flex items-start space-x-4 p-4 rounded-xl bg-dark-card/50 hover:bg-dark-card transition duration-300 border border-gray-600" data-theme-bg="card">
                        <svg class="w-8 h-8 text-secondary flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <div>
                            <p class="font-bold text-white text-xl">อีเมล (ตลอด 24 ชม.)</p>
                            <a href="mailto:support@aesthetic.games" class="text-primary hover:underline transition">support@aesthetic.games</a>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-4 rounded-xl bg-dark-card/50 hover:bg-dark-card transition duration-300 border border-gray-600" data-theme-bg="card">
                        <svg class="w-8 h-8 text-secondary flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <div>
                            <p class="font-bold text-white text-xl">โทรศัพท์ (จ.-ศ. 9:00 - 17:00 น.)</p>
                            <a href="tel:+66123456789" class="text-gray-400 hover:text-white transition">(+66) 123-456-789</a>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-4 rounded-xl bg-dark-card/50 hover:bg-dark-card transition duration-300 border border-gray-600" data-theme-bg="card">
                        <svg class="w-8 h-8 text-secondary flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div>
                            <p class="font-bold text-white text-xl">ที่อยู่บริษัท</p>
                            <p class="text-gray-400">อาคาร AESTHETIC, 99/9 ถนนเกมเมอร์, กรุงเทพฯ 10330</p>
                        </div>
                    </div>

                    <div class="pt-6">
                        <h3 class="text-2xl font-bold text-white mb-4">ติดตามเรา</h3>
                        <div class="flex space-x-6">
                            <a href="#" class="text-gray-400 hover:text-blue-600 transition duration-150" title="Facebook">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.484 2 12.017a9.96 9.96 0 006.18 9.176v-7.143H5.257V12.017h2.923V9.309c0-2.91 1.764-4.254 4.123-4.254 1.18 0 2.22.215 2.504.308v3.013h-1.79c-1.393 0-1.666.666-1.666 1.637v2.195h3.328l-.532 3.328h-2.796v7.142A9.96 9.96 0 0022 12.017C22 6.484 17.523 2 12 2z"></path></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-cyan-400 transition duration-150" title="Twitter/X">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M23.954 4.569a10 10 0 01-2.825.775 4.932 4.932 0 002.16-2.723 9.927 9.927 0 01-3.13 1.196 4.928 4.928 0 00-8.384 4.496 13.921 13.921 0 01-10.14-5.114 4.928 4.928 0 001.52 6.574A4.912 4.912 0 01.442 7.15c0 0 0 0 0 .062a4.929 4.929 0 003.95 4.827 4.936 4.936 0 01-2.22.083 4.93 4.93 0 004.604 3.425 9.872 9.872 0 01-6.148 2.113 9.914 9.914 0 01-1.173-.068 13.959 13.959 0 007.545 2.211c9.052 0 13.99-7.496 13.99-13.99 0-.214-.005-.426-.014-.637a9.972 9.972 0 002.433-2.525z"></path></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-purple-500 transition duration-150" title="Discord/Twitch/Community">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4 11.2V16h-1.8v-2.3c0-1.4-.7-2.1-1.8-2.1-.8 0-1.3.4-1.6.8-.3.4-.3.9-.3 1.5V16H9.5v-6.5h1.7v1.1c.3-.5.7-.9 1.5-1.1.9-.3 1.6-.3 2.3 0 1.2.5 1.7 1.4 1.7 3.2z"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-dark-card p-8 rounded-2xl shadow-2xl border border-primary/30" data-theme-bg="card" id="contact-form-card">

                    <h2 class="text-3xl font-bold text-white mb-6">ส่งข้อความถึงเรา</h2>

                    <?php if ($status_message): ?>
                        <div class="p-4 mb-6 rounded-xl text-lg font-medium 
                            <?= $status_type == 'success' ? 'bg-green-500/20 text-green-400 border border-green-500' : 'bg-red-500/20 text-red-400 border border-red-500' ?>">
                            <?= $status_message ?>
                        </div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST" class="space-y-6">
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">ชื่อของคุณ</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required 
                                class="mt-1 block w-full rounded-lg border-gray-600 shadow-sm theme-input p-3 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300">อีเมล</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required 
                                class="mt-1 block w-full rounded-lg border-gray-600 shadow-sm theme-input p-3 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-300">หัวข้อ</label>
                            <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject) ?>" required 
                                class="mt-1 block w-full rounded-lg border-gray-600 shadow-sm theme-input p-3 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-300">ข้อความของคุณ</label>
                            <textarea id="message" name="message" rows="5" required 
                                class="mt-1 block w-full rounded-lg border-gray-600 shadow-sm theme-input p-3 focus:ring-primary focus:border-primary"><?= htmlspecialchars($message) ?></textarea>
                        </div>
                        
                        <div>
                            <button type="submit" class="w-full px-4 py-3 bg-primary rounded-lg text-white font-bold text-lg hover:bg-indigo-700 transition duration-300 btn-primary">
                                🚀 ส่งข้อความช่วยเหลือ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="bg-dark-card border-t border-gray-700 mt-12 relative z-10" data-theme-bg="card">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-gray-400">
            <div class="flex flex-col md:flex-row justify-center space-y-2 md:space-y-0 md:space-x-8 mb-4">
                <a href="#" class="hover:text-primary transition duration-150">นโยบายความเป็นส่วนตัว</a>
                <a href="#" class="hover:text-primary transition duration-150">ข้อกำหนดการใช้งาน</a>
            </div>
            <p>&copy; 2025 โลกแห่งเกมอันงดงาม (AESTHETIC.GAMES) | สงวนลิขสิทธิ์</p>
        </div>
    </footer>

    <div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-80 z-[110] hidden flex items-center justify-center p-4">
        <div id="cart-modal-content" class="bg-dark-card w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 rounded-xl shadow-2xl relative border border-secondary/50" data-theme-bg="card">
            
            <button id="close-cart-modal-btn" class="absolute top-4 right-4 text-gray-400 hover:text-white transition duration-150" title="ปิด">
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
                <button id="checkout-btn" class="w-full px-4 py-3 bg-primary rounded-lg text-white font-bold hover:bg-indigo-700 transition duration-300 disabled:opacity-50">
                    ดำเนินการชำระเงิน 
                </button>
            </div>
        </div>
    </div>

    <script>
        // **************** Cart UI & Logic Variables (ใช้ Local Storage) ****************
        const cartModal = document.getElementById('cart-modal');
        const openCartBtn = document.getElementById('open-cart-btn');
        const closeCartModalBtn = document.getElementById('close-cart-modal-btn');
        const cartItemCount = document.getElementById('cart-item-count');
        const cartItemsList = document.getElementById('cart-items-list');
        const cartTotalAmount = document.getElementById('cart-total-amount');
        const checkoutBtn = document.getElementById('checkout-btn');
        
        // **************** Theme Logic Variables ****************
        const pageBody = document.getElementById('page-body');
        const themeSwitchBtn = document.getElementById('theme-switch-btn');
        const themeIcon = document.getElementById('theme-icon');
        
        // Dark Mode Classes
        const darkClasses = {
            body: 'bg-dark-bg text-gray-100', 
            header: 'bg-dark-bg/90',
            card: 'bg-dark-card',
            text: 'text-white',
        };

        // Light Mode Classes
        const lightClasses = {
            body: 'bg-light-bg text-gray-900',
            header: 'bg-light-bg/90',
            card: 'bg-light-card',
            text: 'text-gray-900',
        };

        /**
         * 1. APPLY THEME: ใช้คลาส Theme ตามสถานะปัจจุบัน
         * @param {string} theme - 'dark' หรือ 'light'
         */
        const applyTheme = (theme) => {
            const isDark = theme === 'dark';
            const currentClasses = isDark ? darkClasses : lightClasses;
            const oldClasses = isDark ? lightClasses : darkClasses;
            
            // 1.1 เปลี่ยน Body
            pageBody.classList.remove(...oldClasses.body.split(' ')); 
            pageBody.classList.add(...currentClasses.body.split(' '));
            pageBody.classList.toggle('light', !isDark); 

            // 1.2 เปลี่ยนสี Header, Footer, Modal, Cards
            document.querySelectorAll('[data-theme-bg]').forEach(el => {
                const bgClass = el.dataset.themeBg; 
                
                // ลบคลาสเก่า (bg-, border-)
                el.classList.forEach(className => {
                    if (className.startsWith('bg-') && className.startsWith(oldClasses[bgClass].split('/')[0])) {
                        el.classList.remove(className);
                    }
                    if (className.startsWith('border-')) {
                        el.classList.remove('border-gray-200', 'border-gray-700', 'border-secondary/50', 'border-primary/30', 'border-gray-600');
                    }
                });

                // เพิ่มคลาสใหม่
                el.classList.add(...currentClasses[bgClass].split(' '));
                
                // จัดการสีขอบและเงาเฉพาะ
                if (el.id === 'contact-form-card' || el.id === 'cart-modal-content') {
                    if (isDark) {
                        el.classList.remove('shadow-lg');
                        el.classList.add('border-primary/30', 'shadow-2xl');
                        if (el.id === 'cart-modal-content') el.classList.add('border-secondary/50');
                    } else {
                        el.classList.add('border-gray-200', 'shadow-lg');
                        el.classList.remove('border-primary/30', 'border-secondary/50', 'shadow-2xl');
                    }
                }

                // เปลี่ยนสีพื้นหลังของ Contact Item
                if (el.classList.contains('bg-dark-card/50') || el.classList.contains('bg-light-card/50')) {
                    if (isDark) {
                        el.classList.remove('bg-light-card/50', 'border-gray-200');
                        el.classList.add('bg-dark-card/50', 'border-gray-600');
                    } else {
                        el.classList.remove('bg-dark-card/50', 'border-gray-600');
                        el.classList.add('bg-light-card/50', 'border-gray-200');
                    }
                }
            });

            // 1.3 เปลี่ยน Icon
            if (themeIcon) {
                const sunPath = "M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z";
                const moonPath = "M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z";
                themeIcon.querySelector('path').setAttribute('d', isDark ? sunPath : moonPath); // อัพเดท: Dark Mode ควรแสดง Moon/Crescent 
            }

            // 1.4 บันทึกค่า
            localStorage.setItem('theme', theme);
        };

        /**
         * 2. TOGGLE THEME: สลับระหว่าง Dark และ Light
         */
        const toggleTheme = () => {
            const currentTheme = localStorage.getItem('theme') || 'dark'; 
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        };

        // **************** Cart Helper Functions (Local Storage) ****************

        /**
         * 3. GET CART: ดึงข้อมูลตะกร้าจาก Local Storage
         * @returns {Array<Object>} รายการสินค้าในตะกร้า
         */
        const getCartFromStorage = () => {
            const cartString = localStorage.getItem('game_cart');
            // Mock data หากไม่มีข้อมูล
            if (!cartString) {
                return [
                    { id: 'gm-01', title: 'The Last of Us Part I', price: '1690.00' },
                    { id: 'gm-02', title: 'Cyberpunk 2077', price: '1250.00' }
                ];
            }
            return JSON.parse(cartString);
        };

        /**
         * 4. SAVE CART: บันทึกข้อมูลตะกร้าลง Local Storage
         * @param {Array<Object>} cart - รายการสินค้าในตะกร้า
         */
        const saveCartToStorage = (cart) => {
            localStorage.setItem('game_cart', JSON.stringify(cart));
        };

        /**
         * 5. RENDER CART: แสดงผลสินค้าใน Modal ตะกร้า
         * @param {Array<Object>} cart - รายการสินค้าในตะกร้า
         */
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
                        <div class="flex justify-between items-center bg-gray-700/80 p-3 rounded-lg border border-gray-600/50">
                            <span class="text-white font-medium truncate">${item.title}</span>
                            <div class="flex items-center space-x-3 flex-shrink-0">
                                <span class="text-secondary font-bold whitespace-nowrap">฿${price.toFixed(2)}</span>
                                <button data-id="${item.id}" class="remove-from-cart-btn text-red-400 hover:text-red-500 transition duration-150" title="ลบออกจากตะกร้า">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    `;
                    cartItemsList.innerHTML += itemHtml;
                });
            }
            
            cartItemCount.textContent = cart.length > 99 ? '99+' : cart.length.toString(); 
            cartTotalAmount.textContent = `฿${total.toFixed(2)}`;
            attachRemoveListeners(); 
        };

        /**
         * 6. REMOVE ITEM: จัดการการลบสินค้า
         */
        const handleRemove = (e) => {
            e.preventDefault();
            const removeBtn = e.currentTarget;
            const gameId = removeBtn.dataset.id; 
            
            if (confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าใช่หรือไม่?')) {
                let cart = getCartFromStorage();
                // ลบรายการแรกที่ตรงกับ ID
                const indexToRemove = cart.findIndex(item => item.id === gameId);
                if (indexToRemove > -1) {
                    cart.splice(indexToRemove, 1);
                }
                saveCartToStorage(cart);
                renderCart(cart); 
            }
        };

        /**
         * 7. ATTACH LISTENERS: แนบ Event Listener ให้ปุ่มลบ
         */
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

            // Toggle Mobile Menu
            menuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });

            // Event: สลับธีม
            if (themeSwitchBtn) {
                themeSwitchBtn.addEventListener('click', toggleTheme);
            }

            // ตรวจสอบธีมที่บันทึกไว้ใน Local Storage เมื่อโหลดหน้า (Default: dark)
            const savedTheme = localStorage.getItem('theme') || 'dark';
            applyTheme(savedTheme);

            // Event: เปิด Modal ตะกร้า
            if (openCartBtn) {
                openCartBtn.addEventListener('click', () => {
                    renderCart(getCartFromStorage()); 
                    if(cartModal) cartModal.classList.remove('hidden');
                });
            }
            
            // Event: ปิด Modal ตะกร้า ด้วยปุ่ม X
            if (closeCartModalBtn) {
                closeCartModalBtn.addEventListener('click', () => {
                    if(cartModal) cartModal.classList.add('hidden');
                });
            }
            
            // Event: ปิด Modal ตะกร้า เมื่อคลิกนอกกรอบ (ป้องกันการปิดเมื่อคลิกที่เนื้อหาใน Modal)
            if (cartModal) {
                cartModal.addEventListener('click', (e) => {
                    // หากไม่ได้คลิกที่ Cart Modal Content (หรือลูกหลาน)
                    if (e.target.id === 'cart-modal') {
                        cartModal.classList.add('hidden');
                    }
                });
            }

            // Event สำหรับปุ่ม Checkout
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', () => {
                    const cart = getCartFromStorage(); 
                    if (cart.length > 0) {
                        alert('จำลอง: ดำเนินการไปยังหน้าชำระเงิน (checkout.php)');
                        // window.location.href = 'checkout.php';
                    } else {
                        alert('ตะกร้าสินค้าว่างเปล่า ไม่สามารถดำเนินการชำระเงินได้!');
                    }
                });
            }

            // *** สำคัญ: เรียกใช้ฟังก์ชันนี้เมื่อโหลดหน้าเสร็จเพื่อแสดงจำนวนสินค้าตั้งแต่แรก ***
            renderCart(getCartFromStorage()); 
        });
        
    </script>
</body>
</html>