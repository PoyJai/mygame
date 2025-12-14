<?php
session_start();
include('server.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            /* ใช้ฟอนต์ Inter หรือตามความต้องการ */
            font-family: 'Inter', sans-serif;
            /* พื้นหลังสีเข้ม */
            background: #18181b; 
            /* ใส่รูปพื้นหลังถ้าต้องการ */
            background-image: url('https://m.media-amazon.com/images/S/pv-target-images/6fb04fc002b005a28a0d2b2bc1a1e9ca06c9dd05a7e5d006033776c05a44d706.jpg');
            background-size: cover;
            background-position: center;
        }
        .login-bg {
            /* สีปุ่มไล่เฉด */
            background: linear-gradient(135deg, #a78bfa 0%, #6366f1 100%);
        }
        .colored-card {
            /* Glassmorphism Effect */
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <form action="login_db.php" method="post"
          class="w-full max-w-md p-8 m-4 colored-card rounded-xl shadow-2xl border-t-4 border-indigo-300">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2">เข้าสู่ระบบ</h1>
            <p class="text-indigo-200 text-sm">
                กรุณากรอกชื่อผู้ใช้งานและรหัสผ่าน
            </p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-3 bg-red-500/20 text-red-300 rounded-lg text-center">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']); // ลบ error ออกจาก session เมื่อแสดงผลแล้ว
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-4 p-3 bg-green-500/20 text-green-300 rounded-lg text-center">
                <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']); // ลบ success ออกจาก session เมื่อแสดงผลแล้ว
                ?>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-sm text-indigo-100 mb-2">ชื่อผู้ใช้งาน</label>
            <input type="text" name="username" required
                   class="w-full px-4 py-3 bg-white/20 text-white rounded-lg focus:ring-2 focus:ring-indigo-300 outline-none"
                   placeholder="กรอกชื่อผู้ใช้งาน">
        </div>

        <div class="mb-6">
            <label class="block text-sm text-indigo-100 mb-2">รหัสผ่าน</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 bg-white/20 text-white rounded-lg focus:ring-2 focus:ring-indigo-300 outline-none"
                   placeholder="กรอกรหัสผ่าน">
        </div>

        <button type="submit" name="login_user"
                class="w-full py-3 login-bg text-white font-semibold rounded-lg hover:opacity-90 transition">
            เข้าสู่ระบบ
        </button>

        <div class="mt-6 text-center text-sm">
            <p class="text-indigo-200">ยังไม่มีบัญชี?</p>
            <a href="register.php" class="text-indigo-300 hover:text-indigo-100 font-semibold">
                สร้างบัญชีใหม่
            </a>
        </div>

    </form>

</body>
</html>