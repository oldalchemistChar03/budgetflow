<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - BudgetFlow</title>
    <style>
        body {
            margin: 0;
            background-color: #0e0e11;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .app-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #7366ff;
        }

        .form-container {
            background: #1a1a1d;
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            color: #fff;
        }

        input,
        button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            border: none;
            background: #2a2a2e;
            color: #fff;
            font-size: 14px;
        }

        input::placeholder {
            color: #bbb;
        }

        button {
            background-color: #7366ff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #5b52d6;
        }

        .link {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
        }

        .link a {
            color: #7366ff;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .message.success {
            color: #4caf50;
        }

        .message.error {
            color: #ff4d4f;
        }
    </style>
</head>

<body>

    <div class="app-title">BudgetFlow</div>

    <div class="form-container">
        <h2>Login</h2>


        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif


        @if($errors->any())
            <div class="message error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="link">
            <p>Don't have an account? <a href="{{ route('register.view') }}">Register</a></p>
        </div>
    </div>

</body>

</html>