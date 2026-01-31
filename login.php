<?php
session_start();
require_once 'db_config.php'; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | YoToy ✨</title>
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
            background-image: radial-gradient(#B4E4FF 0.8px, transparent 0.8px);
            background-size: 30px 30px;
            overflow: hidden; /* กันของตกแต่งล้นหน้าจอ */
        }

        /* Neubrutalism Style */
        .toy-card {
            background: white;
            border: 4px solid #000;
            box-shadow: 12px 12px 0px #000;
            border-radius: 2rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toy-card:hover {
            transform: translate(-4px, -4px);
            box-shadow: 16px 16px 0px #FFB4B4;
        }

        .input-toy {
            border: 3px solid #000;
            border-radius: 1.2rem;
            transition: all 0.2s;
        }
        .input-toy:focus {
            outline: none;
            background-color: #FDF7C3;
            transform: scale(1.02);
            box-shadow: 6px 6px 0px #000;
        }

        .btn-toy {
            border: 3px solid #000;
            box-shadow: 6px 6px 0px #000;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .btn-toy:hover {
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0px #000;
        }
        .btn-toy:active {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px #000;
        }

        /* อนิเมชั่นของตกแต่งพื้นหลัง */
        .floating {
            animation: float 6s ease-in-out infinite;
            position: absolute;
            z-index: -1;
            opacity: 0.5;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="floating top-10 left-10 text-toy-pink">
        <svg width="100" height="100" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
    </div>
    <div class="floating bottom-20 right-10 text-toy-blue" style="animation-delay: -2s;">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
    </div>
    <div class="floating top-1/2 left-1/4 text-toy-yellow" style="animation-delay: -4s;">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
    </div>

    <div class="w-full max-w-md" data-aos="zoom-out-up">
        <div class="text-center mb-8">
            <a href="index.php" class="text-6xl font-black tracking-tighter italic inline-block hover:scale-110 transition-transform">
                <span class="text-toy-pink">STUN</span><span class="text-toy-blue">SHOP</span>
            </a>
            <p class="font-black text-black/30 mt-2 uppercase tracking-[0.3em] text-[10px]">Level Up Your Fun</p>
        </div>

        <form action="login_db.php" method="post" class="toy-card p-10 relative bg-white">
            <h2 class="text-4xl font-black text-black mb-8 italic">LOGIN <span class="text-toy-pink animate-pulse">!</span></h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="shake mb-6 p-4 bg-toy-pink border-4 border-black font-black text-sm text-black rounded-2xl flex items-center gap-3">
                    <span>⚠️</span> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <div data-aos="fade-left" data-aos-delay="200">
                    <label class="block text-xs font-black mb-2 ml-1 uppercase tracking-widest">Username\ชื่อผู้ใช้งาน</label>
                    <input type="text" name="username" required
                           class="input-toy w-full px-5 py-4 font-bold text-black placeholder:text-black/20"
                           placeholder="ชื่อผู้ใช้งานของคุณ">
                </div>

                <div data-aos="fade-left" data-aos-delay="400">
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="text-xs font-black uppercase tracking-widest">Password\รหัสผ่าน</label>
                        <a href="forgot_password.php" class="text-xs font-black text-gray-400 hover:text-toy-pink transition-colors">ลืมรหัส?</a>
                    </div>
                    <input type="password" name="password" required
                           class="input-toy w-full px-5 py-4 font-bold text-black placeholder:text-black/20"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" name="login_user"
                    class="btn-toy w-full mt-10 py-5 bg-toy-blue text-black font-black text-2xl rounded-2xl uppercase italic tracking-tighter">
                Let's Play! 🕹️
            </button>

            <div class="relative flex py-8 items-center">
                <div class="flex-grow border-t-4 border-black/5"></div>
                <span class="flex-shrink mx-4 text-gray-300 font-black text-[10px] tracking-widest uppercase">New Player?</span>
                <div class="flex-grow border-t-4 border-black/5"></div>
            </div>

            <a href="register.php" class="btn-toy block w-full py-4 bg-toy-green text-black font-black text-center rounded-2xl text-sm hover:rotate-1">
                สร้างบัญชีใหม่ ✨
            </a>
        </form>
        
        <div class="text-center mt-10" data-aos="fade-up" data-aos-delay="600">
            <a href="index.php" class="font-black text-xs text-gray-400 hover:text-black transition-all flex items-center justify-center gap-2 group">
                <span class="group-hover:-translate-x-2 transition-transform">←</span> BACK TO HOME
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>