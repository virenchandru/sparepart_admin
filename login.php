<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Admin Dashboard</title>
    <meta name="description" content="Login ke Sistem Manajemen Toko untuk mengelola produk, pelanggan, dan transaksi.">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        // Set theme immediately to prevent FOUC
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</head>
<body>
    <button id="themeToggleBtn" class="btn" style="position:fixed; top:20px; right:20px; z-index:100; border-radius:50%; width:45px; height:45px; padding:0; display:flex; align-items:center; justify-content:center; background:var(--surface); color:var(--text); box-shadow:var(--shadow);" aria-label="Toggle Theme">
        <i data-lucide="moon" id="themeIcon"></i>
    </button>
    <div class="login-container">
        <div class="login-box">
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="margin-bottom: 12px;"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
                <h1>Admin Dashboard</h1>
                <p class="subtitle">Sistem Manajemen Toko</p>
            </div>

            <div class="error-message" id="errorMessage"></div>

            <form onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Masukkan username Anda" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Masukkan password Anda" required>
                </div>
                <button type="submit" class="login-btn" id="loginBtn">
                    Login
                </button>
            </form>
        </div>
    </div>

    <script>
        // Theme toggle logic
        const toggleBtn = document.getElementById('themeToggleBtn');
        const themeIcon = document.getElementById('themeIcon');
        
        function updateThemeIcon() {
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                themeIcon.setAttribute('data-lucide', 'sun');
            } else {
                themeIcon.setAttribute('data-lucide', 'moon');
            }
            lucide.createIcons();
        }
        
        toggleBtn.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const target = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', target);
            localStorage.setItem('theme', target);
            updateThemeIcon();
        });
        
        // Init icon
        lucide.createIcons();
        updateThemeIcon();

        async function handleLogin(event) {
            event.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('errorMessage');
            const btn = document.getElementById('loginBtn');

            btn.textContent = 'Memproses...';
            btn.disabled = true;
            btn.style.opacity = '0.7';
            errorDiv.classList.remove('show');

            // ngefetch api buat ngecek password yang di input
            try {
                const res = await fetch('api.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });

                const data = await res.json();

                if (data.success) {
                    btn.textContent = '✓ Berhasil!';
                    btn.style.background = 'linear-gradient(135deg, var(--success), #34d399)';
                    setTimeout(() => { window.location.href = 'dashboard.php'; }, 500);
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.add('show');
                    btn.textContent = 'Login';
                    btn.disabled = false;
                    btn.style.opacity = '1';
                }
            } catch (err) {
                errorDiv.textContent = 'Terjadi kesalahan koneksi. Coba lagi.';
                errorDiv.classList.add('show');
                btn.textContent = 'Login';
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        }
    </script>
</body>
</html>