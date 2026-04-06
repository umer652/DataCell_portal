<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - NU DataCell Portal</title>
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

/* Container */
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

/* Heading */
.heading {
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

/* Button */
.register-btn {
    width: 100%;
    padding: 16px;
    background: #0f1b5c;
    color: #fff;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}
.register-btn:hover {
    background: #1c2d80;
    box-shadow: 0 7px 20px rgba(0,0,0,0.3);
    transform: translateY(-2px);
}

/* Messages */
.error-message { color: red; margin-bottom: 15px; font-size: 14px; }
.success-message { color: green; margin-bottom: 15px; font-size: 14px; }

/* Login link */
.login-link { 
    margin-top: 15px; 
    font-size: 14px; 
    text-align: center;
}
.login-link a { 
    color: #0f1b5c; 
    text-decoration: none; 
    font-weight: bold;
}
.login-link a:hover { 
    text-decoration: underline; 
}
</style>
</head>
<body>

<div class="login-container">
    <!-- Logo -->
    <img src="{{ asset('logo.png') }}" alt="NU Logo">

    <!-- Heading -->
    <div class="heading">
        Welcome to<br>
        Northern University<br>
        DataCell Portal
    </div>

    <!-- Error / Success Messages -->
    @if ($errors->any())
        <div class="error-message">{{ $errors->first() }}</div>
    @endif

    <!-- Register Form -->
    <form method="POST" action="">
        @csrf
        <div class="input-group">
            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
        </div>
        <div class="input-group">
            <input type="text" name="username" placeholder=" userName" value="{{ old('username') }}" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="register-btn">Register</button>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </form>
</div>

</body>
</html>
