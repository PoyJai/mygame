<?php 
session_start();
require_once 'db_config.php'; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>สมัครสมาชิก | STUNSHOP ✨</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
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
                    },
                    fontFamily: { sans: ['Mitr', 'sans-serif'] },
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #FFFDF9;
            background-image: radial-gradient(#E5D1FA 1px, transparent 1px);
            background-size: 24px 24px;
            overflow-x: hidden;
            /* ลบสีไฮไลท์เวลาแตะปุ่มบนมือถือ */
            -webkit-tap-highlight-color: transparent;
        }

        .toy-card {
            background: white;
            border: 4px solid #000;
            box-shadow: 10px 10px 0px #000;
            border-radius: 2rem;
            transition: transform 0.2s;
        }

        /* ป้องกัน iOS Auto-zoom โดยใช้ Font 16px */
        .input-toy {
            border: 3px solid #000;
            border-radius: 1.2rem;
            font-size: 16px; 
            transition: all 0.2s;
        }

        .input-toy:focus {
            outline: none;
            background-color: #FDF7C3;
            box-shadow: 5px 5px 0px #000;
            transform: translateY(-2px);
        }

        .btn-toy {
            border: 3px solid #000;
            box-shadow: 6px 6px 0px #000;
            transition: all 0.1s ease;
            -webkit-user-select: none;
        }

        /* เอฟเฟกต์ตอนเอานิ้วจิ้มปุ่ม */
        .btn-toy:active {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px #000;
        }

        .float-decor {
            position: fixed;
            z-index: -1;
            pointer-events: none;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        /* ซ่อน Scrollbar */
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
        
<body class="flex flex-col items-center justify-start min-h-screen p-5 pt-10 sm:justify-center">

    <div class="float-decor top-10 right-10 text-4xl opacity-40">✨</div>
    <div class="float-decor bottom-20 left-10 text-4xl opacity-40" style="animation-delay: -3s;">🎮</div>

    <div class="w-full max-w-md" data-aos="zoom-in">
        <div class="text-center mb-8">
            <a href="index.php" class="text-6xl font-black tracking-tighter italic inline-block active:scale-90 transition-transform">
                <span class="text-toy-pink">STUN</span><span class="text-toy-blue">SHOP</span>
            </a>
            <p class="text-black/40 mt-1 font-black uppercase text-[10px] tracking-[0.4em]">Ready to Play?</p>
        </div>

        <div class="toy-card p-7 sm:p-10 relative bg-white">
            <h1 class="text-3xl font-black text-black mb-8 italic">SIGN UP <span class="text-toy-purple animate-pulse inline-block">!</span></h1>

            <?php if (isset($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])): ?>
                <div class="mb-6 p-4 bg-toy-pink border-4 border-black text-black rounded-2xl font-black text-xs">
                    <ul class="space-y-1">
                        <?php foreach ($_SESSION['error_messages'] as $error): ?>
                            <li class="flex items-center gap-2">⚠️ <?= htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['error_messages']); ?>
            <?php endif; ?>

            <form action="register_db.php" method="post" class="space-y-5">
                
                <div>
                    <label class="block text-[11px] font-black mb-1.5 ml-1 uppercase tracking-widest text-black/40 text-left">Username / ชื่อผู้ใช้</label>
                    <input type="text" name="username" required
                           class="input-toy w-full px-5 py-4 font-bold text-black"
                           placeholder="ชื่อเท่ๆ ของคุณ"
                           value="<?= isset($_SESSION['temp_data']['username']) ? htmlspecialchars($_SESSION['temp_data']['username']) : '' ?>">
                </div>

                <div>
                    <label class="block text-[11px] font-black mb-1.5 ml-1 uppercase tracking-widest text-black/40 text-left">Email / อีเมล</label>
                    <input type="email" name="email" required inputmode="email"
                           class="input-toy w-full px-5 py-4 font-bold text-black"
                           placeholder="yourname@mail.com"
                           value="<?= isset($_SESSION['temp_data']['email']) ? htmlspecialchars($_SESSION['temp_data']['email']) : '' ?>">
                </div>
                
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block text-[11px] font-black mb-1.5 ml-1 uppercase tracking-widest text-black/40 text-left">Password / รหัสผ่าน</label>
                        <input type="password" name="password" required
                               class="input-toy w-full px-5 py-4 font-bold text-black"
                               placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black mb-1.5 ml-1 uppercase tracking-widest text-black/40 text-left">Confirm / ยืนยันรหัส</label>
                        <input type="password" name="confirm-password" required
                               class="input-toy w-full px-5 py-4 font-bold text-black"
                               placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" name="reg_user"
                        class="btn-toy w-full mt-6 py-5 bg-toy-purple text-black font-black text-2xl rounded-2xl uppercase italic tracking-tighter">
                    CREATE ID 🚀
                </button>
            </form>

            <div class="relative flex py-8 items-center">
                <div class="flex-grow border-t-4 border-black/5"></div>
                <span class="flex-shrink mx-4 text-gray-300 font-black text-[10px] tracking-widest uppercase">Member?</span>
                <div class="flex-grow border-t-4 border-black/5"></div>
            </div>

            <div class="text-center">
                <?php unset($_SESSION['temp_data']); ?>
                <a href="login.php" class="btn-toy block w-full py-4 bg-toy-blue text-black font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-white transition-all">
                    LOG IN HERE
                </a>
            </div>
        </div>
        
        <div class="text-center mt-8 mb-10">
            <a href="index.php" class="font-black text-xs text-gray-400 hover:text-black transition-all flex items-center justify-center gap-2 group">
                <span class="group-active:-translate-x-2 transition-transform">←</span> BACK TO HOME
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ 
            duration: 800, 
            once: true,
            disable: 'mobile' // เลือกปิด animation บางตัวบนมือถือถ้าต้องการความเร็วสูงสุด
        });
        
        // บังคับให้หน้าจอเลื่อนขึ้นบนสุดเมื่อโหลด (แก้ปัญหาคีย์บอร์ดค้าง)
        window.scrollTo(0, 0);
    </script>
</body>
</html>