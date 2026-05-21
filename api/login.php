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
    <title>Login - Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #1a3a52; font-size: 28px; margin: 0;">Admin Dashboard</h1>
                <p style="color: #7f8c8d; margin-top: 5px;">Sistem Manajemen Toko</p>
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
                <button type="submit" class="login-btn" id="loginBtn">Login</button>
            </form>
        </div>
    </div>

    <script>
        async function handleLogin(event) {
            event.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('errorMessage');
            const btn = document.getElementById('loginBtn');

            btn.textContent = 'Loading...';
            btn.disabled = true;
            errorDiv.classList.remove('show');

            const res = await fetch('api.php?action=login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });

            const data = await res.json();

            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                errorDiv.textContent = data.message;
                errorDiv.classList.add('show');
                btn.textContent = 'Login';
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>