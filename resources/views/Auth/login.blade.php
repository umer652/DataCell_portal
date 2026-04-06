<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - NU DataCell Portal</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh;
    background: url("nuimg.jpg") no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Login Box */
.login-container {
    background: rgba(255, 255, 255, 0.85);
    border-radius: 15px;
    padding: 50px 35px;
    width: 400px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
}

/* Logo */
.login-container img {
    width: 100px;
    border-radius: 50%;
    margin-bottom: 15px;
}

/* Multi-line Heading */
.login-container .heading {
    font-size: 20px;
    font-weight: bold;
    color: #0f1b5c;
    line-height: 1.3;
    margin-bottom: 25px;
}

/* Input Fields */
.input-group {
    margin-bottom: 20px;
    text-align: left;
}
.input-group input {
    width: 100%;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 16px;
    box-sizing: border-box;
}
.input-group input::placeholder {
    color: #555;
    font-size: 16px;
}

/* Password toggle */
.password-toggle {
    position: relative;
}
.password-toggle input {
    padding-right: 40px;
}
.password-toggle span {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
}

/* Remember & Forgot */
.remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    margin-bottom: 25px;
}
.remember-forgot label {
    display: flex;
    align-items: center;
    gap: 5px;
}
.remember-forgot a {
    text-decoration: none;
    color: #0f1b5c;
}
.remember-forgot a:hover {
    text-decoration: underline;
}

/* Button */
.login-btn {
    width: 100%;
    padding: 15px;
    background: #0f1b5c;
    color: #fff;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
}
.login-btn:hover {
    background: #1c2d80;
}

/* Register Link */
.register-link { margin-top: 15px; font-size: 14px; }
.register-link a { color: #0f1b5c; text-decoration: none; }
.register-link a:hover { text-decoration: underline; }

/* Toast / Popup Notifications */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: fadein 0.5s, fadeout 0.5s 4.5s;
}
.toast-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.toast-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

@keyframes fadein {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeout {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-20px); }
}
</style>
</head>
<body>

<div class="login-container">

    <!-- Logo -->
    <img src="{{ asset('logo.png') }}" alt="NU Logo">

    <!-- Multi-line Heading -->
    <div class="heading">
        Welcome to<br>
        Northern University<br>
        DataCell Portal
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('webLogin') }}">
        @csrf

        <div class="input-group">
            <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        </div>

        <div class="input-group password-toggle">
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span onclick="togglePassword()">👁</span>
        </div>

        <div class="remember-forgot">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="#">Forgot password?</a>
        </div>

        <button type="submit" class="login-btn">Log in</button>

        <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Register</a>
        </div>
    </form>
</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    password.type = password.type === "password" ? "text" : "password";
}

// Toast Notifications
@if (session('error'))
createToast("{{ session('error') }}", "error");
@endif

@if (session('success'))
createToast("{{ session('success') }}", "success");
@endif

function createToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'toast ' + (type === 'success' ? 'toast-success' : 'toast-error');
    toast.innerHTML = (type === 'success' ? '✅' : 'ℹ️') + ' ' + message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.remove(); }, 5000);
}
</script>

</body>
</html>
