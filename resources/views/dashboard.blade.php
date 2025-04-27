<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - BudgetFlow</title>
</head>

<body>
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <p>Your role: {{ auth()->user()->role }}</p>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>

</html>