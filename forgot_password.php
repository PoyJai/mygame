<?php
session_start();
require_once 'db_config.php'; 

// --- ส่วนประมวลผล Logic (คงเดิมแต่เพิ่มการ Redirect ที่แม่นยำ) ---
if (isset($_POST['reset_password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $pass1 = mysqli_real_escape_string($conn, $_POST['password_1']);
    $pass2 = mysqli_real_escape_string($conn, $_POST['password_2']);

    if ($pass1 !== $pass2) {
        $_SESSION['error'] = "รหัสผ่านไม่ตรงกัน กรุณาลองใหม่ ❌";
        header("location: forgot.php");
        exit();
    }

    $user_check = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $user_check);
    
    if (mysqli_num_rows($result) > 0) {
        $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "เปลี่ยนรหัสผ่านสำเร็จ! เข้าสู่ระบบได้เลย 🎉";
            header("location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }
    } else {
        $_SESSION['error'] = "ไม่พบชื่อผู้ใช้งานนี้ในระบบ 🧐";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กู้คืนรหัสผ่าน | YoToy ✨</title>
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
            background-image: radial-gradient(#FFB4B4 0.8px, transparent 0.8px);
            background-size: 28px 28px;
            overflow: hidden;
        }

        .toy-card {
            background: white;
            border: 4px solid #000;
            box-shadow: 12px 12px 0px #000;
            border-radius: 2rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
            background-color: #000;
            color: #FFB4B4;
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0px #FFB4B4;
        }

        .btn-toy:active {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px #000;
        }

        /* ของตกแต่งลอยไปมา */
        .floating-key {
            position: fixed;
            z-index: -1;
            animation: keyFloat 8s ease-in-out infinite;
            opacity: 0.4;
        }

        @keyframes keyFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(20deg); }
            66% { transform: translate(-20px, 20px) rotate(-15deg); }
        }

        .shake { animation: shake 0.5s ease-in-out; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="floating-key top-20 left-10 text-6xl">🗝️</div>
    <div class="floating-key bottom-20 right-20 text-5xl" style="animation-delay: -3s;">✨</div>
    <div class="floating-key top-1/2 left-1/4 text-4xl" style="animation-delay: -5s;">🔓</div>

    <div class="w-full max-w-md" data-aos="zoom-in-up">
        <div class="text-center mb-8">
            <a href="index.php" class="text-6xl font-black tracking-tighter italic inline-block hover:scale-110 transition-transform">
                <span class="text-toy-pink">STUN</span><span class="text-toy-blue">SHOP</span>
            </a>
            <p class="text-black/40 mt-3 font-black uppercase text-[10px] tracking-[0.4em]">Recovery Mode</p>
        </div>

        <form action="forgot.php" method="post" class="toy-card p-10 relative bg-white <?php echo isset($_SESSION['error']) ? 'shake' : ''; ?>">
            <div class="absolute -bottom-6 -right-6 w-16 h-16 bg-toy-pink border-4 border-black rounded-full -z-10 animate-pulse"></div>
            
            <h2 class="text-3xl font-black text-black mb-8 italic uppercase leading-none">
                Reset <br><span class="text-toy-pink">Password</span>
            </h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-6 p-4 bg-toy-pink border-4 border-black text-black rounded-2xl font-black text-xs flex items-center gap-3">
                    <span class="text-xl">⚠️</span> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="space-y-5">
                <div data-aos="fade-up" data-aos-delay="100">
                    <label class="block text-xs font-black mb-2 ml-1 uppercase tracking-widest text-black/50">Username\</label>
                    <input type="text" name="username" required 
                           class="input-toy w-full px-5 py-4 font-bold text-black" 
                           placeholder="ชื่อผู้ใช้งานของคุณ">
                </div>

                <div data-aos="fade-up" data-aos-delay="200">
                    <label class="block text-xs font-black mb-2 ml-1 uppercase tracking-widest text-black/50">New Password\รหัสผ่านใหม่</label>
                    <input type="password" name="password_1" required 
                           class="input-toy w-full px-5 py-4 font-bold text-black" 
                           placeholder="••••••••">
                </div>

                <div data-aos="fade-up" data-aos-delay="300">
                    <label class="block text-xs font-black mb-2 ml-1 uppercase tracking-widest text-black/50">Confirm Password\ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" name="password_2" required 
                           class="input-toy w-full px-5 py-4 font-bold text-black" 
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" name="reset_password"
                    class="btn-toy w-full mt-10 py-5 bg-toy-pink text-black font-black text-2xl rounded-2xl uppercase italic tracking-tighter">
                confirm 🛠️
            </button>

            <div class="mt-8 text-center border-t-4 border-black/5 pt-6">
                <a href="login.php" class="font-black text-xs text-toy-blue hover:text-black transition-all flex items-center justify-center gap-2 group">
                    <span class="group-hover:-translate-x-1 transition-transform">←</span> BACK TO LOGIN
                </a>
            </div>
        </form>

        <div class="text-center mt-10" data-aos="fade-up" data-aos-delay="400">
            <p class="font-black text-[10px] text-gray-300 uppercase tracking-widest">
                StunShop.Toy System &copy; 2026
            </p>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>