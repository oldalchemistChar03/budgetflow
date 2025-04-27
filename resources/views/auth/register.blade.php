<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - BudgetFlow</title>
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
            color: white;
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
    </style>
</head>

<body>

    <div class="app-title">BudgetFlow</div>

    <div class="form-container">
        <h2>Create Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>

        <div class="link">
            <p>Already have an account? <a href="{{ route('login.view') }}">Login</a></p>
        </div>
    </div>

</body>

</html>