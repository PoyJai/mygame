<?php 
session_start();
// ตรวจสอบการเชื่อมต่อ (แม้จะไม่มีการเรียกใช้ $conn โดยตรง แต่จำเป็นต้อง include เพื่อให้มีการกำหนดค่า)
include('server.php'); 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครบัญชีใหม่</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #18181b;
            background-image: url('https://m.media-amazon.com/images/S/pv-target-images/6fb04fc002b005a28a0d2b2bc1a1e9ca06c9dd05a7e5d006033776c05a44d706.jpg');
            background-size: cover;
            background-position: center; 
        }
        .colored-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
        
<body class="flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 m-4 colored-card rounded-xl shadow-2xl transition duration-500 ease-in-out transform hover:scale-[1.01] border-t-4 border-indigo-300">
        
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">
                สมัครบัญชีใหม่
            </h1>
            <p class="text-indigo-200 text-sm">
                กรุณากรอกข้อมูลเพื่อสร้างบัญชีใหม่
            </p>
        </div>

        <?php if (isset($_SESSION['error_messages']) && is_array($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])): ?>
            <div class="mb-4 p-3 bg-red-500/20 text-red-300 rounded-lg">
                <ul class="list-disc list-inside">
                    <?php foreach ($_SESSION['error_messages'] as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['error_messages']); // ล้างข้อผิดพลาดเมื่อแสดงผลแล้ว ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-4 p-3 bg-green-500/20 text-green-300 rounded-lg text-center">
                <?= $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="register_db.php" method="post">
            
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-indigo-100 mb-2">ชื่อผู้ใช้งาน</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="ตั้งชื่อผู้ใช้งานที่คุณต้องการ" 
                    required
                    class="w-full px-4 py-3 bg-white/20 text-white border border-transparent rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-300 placeholder-indigo-200 transition duration-150"
                    value="<?= isset($_SESSION['temp_data']['username']) ? htmlspecialchars($_SESSION['temp_data']['username']) : '' ?>"
                >
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-indigo-100 mb-2">อีเมล</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="กรอกอีเมลที่ใช้งานได้ของคุณ" 
                    required
                    class="w-full px-4 py-3 bg-white/20 text-white border border-transparent rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-300 placeholder-indigo-200 transition duration-150"
                    value="<?= isset($_SESSION['temp_data']['email']) ? htmlspecialchars($_SESSION['temp_data']['email']) : '' ?>"
                >
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-indigo-100 mb-2">รหัสผ่าน</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="ตั้งรหัสผ่าน" 
                    required
                    class="w-full px-4 py-3 bg-white/20 text-white border border-transparent rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-300 placeholder-indigo-200 transition duration-150"
                >
            </div>

            <div class="mb-6">
                <label for="confirm-password" class="block text-sm font-medium text-indigo-100 mb-2">ยืนยันรหัสผ่าน</label>
                <input 
                    type="password" 
                    id="confirm-password" 
                    name="confirm-password" 
                    placeholder="กรอกรหัสผ่านซ้ำ" 
                    required
                    class="w-full px-4 py-3 bg-white/20 text-white border border-transparent rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-300 placeholder-indigo-200 transition duration-150"
                >
            </div>

            <button 
                type="submit" 
                name="reg_user"
                class="
                    w-full py-3 px-6 text-lg font-semibold text-white rounded-xl
                    bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500
                    shadow-lg shadow-indigo-500/40 transition duration-300 ease-in-out
                    hover:scale-[1.03] hover:shadow-xl hover:shadow-pink-500/50 active:scale-95
                    focus:outline-none focus:ring-4 focus:ring-indigo-300/50
                "
            >
                ✨ สมัครสมาชิก ✨
            </button>
        </form>

        <div class="mt-6 text-center text-sm space-x-4">
            <?php unset($_SESSION['temp_data']); // ล้างข้อมูลชั่วคราวหลังจากแสดงผล ?>
            <a href="login.php" class="text-indigo-300 hover:text-indigo-100 font-medium transition duration-150">
                มีบัญชีอยู่แล้ว? เข้าสู่ระบบ
            </a>
        </div>
    </div>
</body>
</html>