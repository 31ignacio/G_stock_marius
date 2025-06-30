<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url('supermarket_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            width: 400px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
        }

        .input-group input:focus {
            border: 2px solid #fff;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background: #ff9800;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-button:hover {
            background: #e68900;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        @if (Session::get('success_message'))
        <b style="font-size:15px; color:#fff">{{ Session::get('success_message') }}</b>
        @endif

        @if (Session::get('error_message'))
            <b style="font-size:15px; color:rgb(177, 0, 0)">{{ Session::get('error_message') }}</b>
        @endif
        <br><br>
        <hr>
        <br>

        <h2>Connexion</h2>
        <form method="post" action="{{ route('handleLogin') }}">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
                @error('email')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
                @error('password')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="login-button">Se connecter</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>