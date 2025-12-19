<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กู้คืนรหัสผ่าน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #18181b; background-image: url('https://m.media-amazon.com/images/S/pv-target-images/6fb04fc002b005a28a0d2b2bc1a1e9ca06c9dd05a7e5d006033776c05a44d706.jpg'); background-size: cover; background-position: center; }
        .login-bg { background: linear-gradient(135deg, #a78bfa 0%, #6366f1 100%); }
        .colored-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <form action="forgot_db.php" method="post" class="w-full max-w-md p-8 m-4 colored-card rounded-xl shadow-2xl border-t-4 border-indigo-300">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2">ตั้งรหัสผ่านใหม่</h1>
            <p class="text-indigo-200 text-sm">กรอกชื่อผู้ใช้เพื่อเปลี่ยนรหัสผ่านใหม่</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-3 bg-red-500/20 text-red-300 rounded-lg text-center text-sm border border-red-500/50">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-sm text-indigo-100 mb-2">ชื่อผู้ใช้งาน</label>
            <input type="text" name="username" required class="w-full px-4 py-3 bg-white/20 text-white rounded-lg focus:ring-2 focus:ring-indigo-300 outline-none" placeholder="กรอกชื่อผู้ใช้">
        </div>

        <div class="mb-4">
            <label class="block text-sm text-indigo-100 mb-2">รหัสผ่านใหม่</label>
            <input type="password" name="password_1" required class="w-full px-4 py-3 bg-white/20 text-white rounded-lg focus:ring-2 focus:ring-indigo-300 outline-none" placeholder="รหัสผ่านใหม่">
        </div>

        <div class="mb-6">
            <label class="block text-sm text-indigo-100 mb-2">ยืนยันรหัสผ่านใหม่</label>
            <input type="password" name="password_2" required class="w-full px-4 py-3 bg-white/20 text-white rounded-lg focus:ring-2 focus:ring-indigo-300 outline-none" placeholder="ยืนยันรหัสผ่าน">
        </div>

        <button type="submit" name="reset_password" class="w-full py-3 login-bg text-white font-semibold rounded-lg hover:opacity-90 transition transform active:scale-95 shadow-lg">บันทึกรหัสผ่านใหม่</button>

        <div class="mt-6 text-center text-sm">
            <a href="login.php" class="text-indigo-300 hover:text-white font-semibold">กลับสู่หน้าเข้าสู่ระบบ</a>
        </div>
    </form>

</body>
</html>ห