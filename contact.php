<?php
session_start();
require_once 'db_config.php'; 

// 1. ตรรกะการ Logout
if (isset($_GET['logout'])) {
    session_destroy(); 
    header('location: login.php'); 
    exit;
}

$is_logged_in = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$current_username = $is_logged_in ? htmlspecialchars($_SESSION["username"]) : "Guest"; 

$status_message = "";
$status_type = ""; 
$name = $email = $subject = $message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $subject = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $status_message = "กรุณากรอกข้อมูลให้ครบถ้วนนะตัวเธอ! ✨";
        $status_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status_message = "รูปแบบอีเมลไม่ถูกต้อง ลองเช็คอีกทีนะ 🧐";
        $status_type = "error";
    } else {
        $sql = "INSERT INTO contacts (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['flash_message'] = "ส่งข้อความเรียบร้อย! ข้อมูลบันทึกลงฐานข้อมูลแล้ว 🚀";
            $_SESSION['flash_type'] = "success";
            header("Location: contact.php");
            exit;
        } else {
            $status_message = "Error: " . mysqli_error($conn);
            $status_type = "error";
        }
    }
}

if (isset($_SESSION['flash_message'])) {
    $status_message = $_SESSION['flash_message'];
    $status_type = $_SESSION['flash_type'];
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | StunShop ✨</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@300;400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'toy-pink': '#FFB4B4',
                        'toy-blue': '#B4E4FF',
                        'toy-yellow': '#FDF7C3',
                        'toy-purple': '#E5D1FA',
                        'toy-green': '#BFF6C3',
                        'pop-orange': '#FF7A00',
                    },
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Mitr', sans-serif;
            background-color: #FFFDF9; 
            background-image: radial-gradient(#B4E4FF 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            scroll-behavior: smooth;
        }
        .toy-card {
            background: white; border: 4px solid #000;
            box-shadow: 8px 8px 0px #000; border-radius: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toy-card:hover { transform: translateY(-10px) rotate(1deg); box-shadow: 15px 15px 0px #FFB4B4; }
        .input-toy { border: 3px solid #000; border-radius: 1rem; transition: all 0.3s ease; }
        .input-toy:focus { transform: scale(1.02); border-color: #FF7A00; background-color: #FDF7C3; outline: none; }
        .btn-toy { border: 3px solid #000; box-shadow: 4px 4px 0px #000; transition: all 0.2s; }
        .btn-toy:hover { transform: translate(-2px, -2px); box-shadow: 7px 7px 0px #000; }
        .btn-toy:active { transform: translate(2px, 2px); box-shadow: 0px 0px 0px #000; }
        
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-15px) rotate(2deg); } }
        .float-anim { animation: float 4s ease-in-out infinite; }
        
        /* สไตล์พิเศษสำหรับปุ่มตอนเลื่อนเมาส์ */
        .btn-toy:hover span { display: inline-block; animation: wave 0.5s infinite; }
        @keyframes wave { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(20deg); } }
    </style>
</head>
<body class="min-h-screen">

    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-sm border-b-4 border-black">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-3xl font-black italic tracking-tighter hover:scale-105 transition-transform">
                <span class="text-toy-pink">Stun</span><span class="text-toy-blue">Shop</span>
            </a>
            <div class="hidden md:flex items-center space-x-6 font-bold">
                <a href="index.php" class="hover:text-pop-orange transition-colors">หน้าแรก</a>
                <a href="contact.php" class="bg-toy-yellow border-2 border-black px-5 py-2 rounded-full shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] active:translate-y-[1px] transition-all">ติดต่อเรา</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-12">
        <div data-aos="fade-down" class="bg-pop-orange border-4 border-black p-10 mb-16 rounded-3xl shadow-[15px_15px_0px_#000] text-white overflow-hidden relative">
            <div class="relative z-10">
                <h1 class="text-5xl md:text-6xl font-black mb-4 [text-shadow:4px_4px_0px_#000]">Get In Touch ✨</h1>
                <p class="text-xl font-bold bg-white text-black inline-block px-4 py-1 border-2 border-black rotate-1">มีคำถาม? พิมพ์บอกเราได้เลย!</p>
            </div>
            <div class="absolute right-10 top-5 opacity-20 text-8xl float-anim">💬</div>
        </div>

        <div class="grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-5" data-aos="fade-right" data-aos-delay="200">
                <div class="toy-card p-8 bg-toy-blue/20 h-full">
                    <h3 class="text-2xl font-black mb-6 flex items-center gap-3">
                        <span class="text-3xl">📮</span> ข้อมูลติดต่อ
                    </h3>
                    <div class="space-y-6 font-bold text-lg">
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white transition-colors border-2 border-transparent hover:border-black">
                            <span class="text-2xl">📍</span>
                            <p>123 Gaming Street, <br><span class="text-pop-orange">Pastel City, Bangkok</span></p>
                        </div>
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white transition-colors border-2 border-transparent hover:border-black">
                            <span class="text-2xl">📧</span>
                            <p>support@stunshop.toy</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7" data-aos="fade-left" data-aos-delay="400">
                <div class="toy-card p-8 md:p-10 relative">
                    <h3 class="text-3xl font-black mb-8">ส่งข้อความหา <span class="underline decoration-toy-pink decoration-8">Staff</span></h3>

                    <?php if ($status_message): ?>
                        <div id="status-alert" class="<?= $status_type == 'success' ? 'bg-toy-green' : 'bg-toy-pink' ?> border-4 border-black p-4 rounded-2xl mb-8 font-black text-center animate-bounce">
                            <?= $status_message ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="font-black ml-1">ชื่อ-นามสกุล</label>
                                <input type="text" name="name" class="input-toy w-full px-4 py-3 font-bold" placeholder="น้องเกมเมอร์" required>
                            </div>
                            <div class="space-y-2">
                                <label class="font-black ml-1">อีเมลติดต่อ</label>
                                <input type="email" name="email" class="input-toy w-full px-4 py-3 font-bold" placeholder="gamer@email.com" required>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="font-black ml-1">หัวข้อเรื่อง</label>
                            <input type="text" name="subject" class="input-toy w-full px-4 py-3 font-bold" placeholder="แจ้งปัญหา / สอบถามข้อมูล" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-black ml-1">ข้อความของคุณ</label>
                            <textarea name="message" rows="4" class="input-toy w-full px-4 py-3 font-bold" placeholder="พิมพ์ข้อความที่นี่..." required></textarea>
                        </div>
                        <button type="submit" class="btn-toy w-full py-5 bg-black text-white rounded-2xl font-black text-xl hover:bg-toy-pink hover:text-black transition-all group">
                            SEND MESSAGE <span>🚀</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-32 pb-20 text-center">
            <h3 class="text-4xl font-black mb-16 italic" data-aos="zoom-in">Our <span class="text-toy-purple [text-shadow:2px_2px_0px_#000]">Creative</span> Team</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
                <?php
                $creators = [
                    ['seed' => 'Bear', 'name' => 'Name 1', 'color' => 'bg-toy-pink'],
                    ['seed' => 'Cat', 'name' => 'Name 2', 'color' => 'bg-toy-blue'],
                    ['seed' => 'Rabbit', 'name' => 'Name 3', 'color' => 'bg-toy-yellow'],
                    ['seed' => 'Fox', 'name' => 'Name 4', 'color' => 'bg-toy-purple'],
                    ['seed' => 'Koala', 'name' => 'Name 5', 'color' => 'bg-toy-green'],
                ];
                $delay = 0;
                foreach ($creators as $c): ?>
                    <div class="group" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                        <div class="relative mb-4 transition-all duration-500 group-hover:scale-110 group-hover:-rotate-3">
                            <div class="absolute inset-0 <?= $c['color'] ?> border-4 border-black rounded-3xl translate-x-2 translate-y-2 group-hover:translate-x-3 group-hover:translate-y-3 transition-transform"></div>
                            <div class="relative bg-white border-4 border-black rounded-3xl overflow-hidden p-2">
                                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=<?= $c['seed'] ?>" class="w-full">
                            </div>
                        </div>
                        <div class="bg-black text-white px-4 py-1 rounded-full text-sm font-black transform transition-transform group-hover:scale-110"><?= $c['name'] ?></div>
                    </div>
                <?php $delay += 100; endforeach; ?>
            </div>
        </div>
    </main>

    <footer class="mt-10 py-10 text-center border-t-4 border-black bg-white">
        <p class="font-black text-sm md:text-lg">STUNSHOP.TOY &copy; 2026</p>
        <p class="font-black text-sm md:text-lg">เว็บนี้สร้างไว้สำหรับส่งงานเท่านั้น<br>วิทยาลัยอาชีวศึกษาวิทยาลัยนครสวรรค์ &copy;</p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // ✅ เริ่มทำงาน AOS Animation
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // ✅ JavaScript สำหรับซ่อน Alert อัตโนมัติ (คงเดิม)
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('status-alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease, transform 0.5s ease";
                    alert.style.opacity = "0";
                    alert.style.transform = "translateY(-20px)";
                    setTimeout(() => alert.remove(), 500); 
                }, 3500); 
            }
        });
    </script>
</body>
</html>