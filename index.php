<?php
require_once 'api/config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | ระบบจองห้องประชุม รพ.พาน</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS v4 -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Outfit', 'Sarabun', sans-serif;
            
            /* Earth Tone Palette */
            --color-primary: #6A5243;
            --color-primary-hover: #523E32;
            --color-secondary: #D4B59D;
            --color-accent: #E6D6BD;
            
            --color-background: #EBE6DA;
            --color-surface: #F3F0E6;
            --color-surface-muted: #E6D6BD;
            
            --color-text-main: #6A5243;
            --color-text-muted: #A79A8B;
            --color-border: #D4B59D;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--color-background);
            color: var(--color-text-main);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden bg-[url('assets/images/poster1.png')] bg-cover bg-center bg-no-repeat before:content-[''] before:absolute before:inset-0 before:bg-white/80 before:backdrop-blur-md">
    
    <div class="w-full max-w-md bg-white/90 backdrop-blur-xl p-10 rounded-3xl shadow-xl shadow-primary/10 border border-border relative z-10">
        
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                <i class="fas fa-seedling text-2xl"></i>
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-2xl font-bold text-primary tracking-tight">Smart Office</span>
                <span class="text-sm font-medium text-secondary">โรงพยาบาลพาน</span>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-text-main mb-2">ยินดีต้อนรับกลับมา</h1>
            <p class="text-text-muted text-sm">เข้าสู่ระบบเพื่อจัดการห้องประชุม</p>
        </div>
        
        <form id="loginForm" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-medium text-text-main mb-1.5">ชื่อผู้ใช้งาน (ชื่อจริง)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-muted">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <input type="text" id="username" placeholder="เช่น Somchai" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border bg-white focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-text-main mb-1.5">รหัสผ่าน (เลขบัตรประชาชน/CID)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-muted">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" id="password" placeholder="••••••••" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border bg-white focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                </div>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium shadow-md shadow-primary/20 transition-all flex items-center justify-center gap-2 group">
                <span>เข้าสู่ระบบ</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="test_connection.php" class="text-sm font-medium text-secondary hover:text-accent transition-colors flex items-center justify-center gap-1">
                <i class="fas fa-stethoscope"></i> ตรวจสอบสถานะการเชื่อมต่อ
            </a>
        </div>
    </div>

    <script src="js/app.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            const btn = e.target.querySelector('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังตรวจสอบ...';
            btn.disabled = true;

            try {
                const response = await fetch('api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    localStorage.setItem('user', JSON.stringify(result.user));
                    window.location.href = 'dashboard.php';
                } else {
                    alert(result.message || 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
