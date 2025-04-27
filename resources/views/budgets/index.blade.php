<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Budgets - BudgetFlow</title>
    <style>
        body {
            background-color: #0e0e11;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding-bottom: 80px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .budget-card {
            background: #1a1a1d;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .budget-title {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .budget-amount {
            font-size: 24px;
            font-weight: bold;
        }

        .progress-bar {
            background: #333;
            height: 10px;
            border-radius: 8px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress {
            height: 10px;
            background: #7366ff;
        }

        .progress.over {
            background: #ff4444;
        }

        .form-section {
            background: #1a1a1d;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            background: #2a2a2e;
            border: none;
            border-radius: 6px;
            margin-bottom: 20px;
            color: white;
        }

        button {
            width: 100%;
            background: #7366ff;
            color: white;
            padding: 12px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background: #6155d8;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #1c1c1f;
            padding: 12px 0;
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #222;
        }

        .nav-item {
            text-align: center;
            color: #999;
            font-size: 12px;
            text-decoration: none;
        }

        .nav-item span {
            display: block;
            font-size: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>Budgets</h2>
            <div class="budget-amount">$ {{ number_format($totalBudget, 2) }}</div>
            <div class="progress-bar">
                <div class="progress"
                    style="width: {{ $totalBudget > 0 ? ($spendingPerCategory->sum() / $totalBudget) * 100 : 0 }}%">
                </div>
            </div>
            <small>{{ $totalBudget - $spendingPerCategory->sum() }} left</small>
        </div>

        <!-- Add New Budget Form -->
        <div class="form-section">
            <h3 style="text-align:center;">➕ Add New Budget</h3>

            <form method="POST" action="{{ route('budgets.store') }}">
                @csrf

                <label>Category:</label>
                <select name="category_id" required>
                    <option disabled selected>-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <label>Budget Amount ($):</label>
                <input type="number" name="amount" step="0.01" placeholder="Enter amount" required>

                <button type="submit">Add Budget</button>
            </form>
        </div>


        @foreach($budgets as $budget)
                @php
                    $spent = $spendingPerCategory[$budget->category_id] ?? 0;
                    $remaining = $budget->amount - $spent;
                    $percentage = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
                @endphp
                <div class="budget-card">
                    <div class="budget-title">{{ $budget->category->name }}</div>
                    <div class="budget-amount">$ {{ number_format($budget->amount, 2) }}</div>
                    <div class="progress-bar">
                        <div class="progress {{ $remaining < 0 ? 'over' : '' }}" style="width: {{ min($percentage, 100) }}%">
                        </div>
                    </div>
                    <small>
                        @if($remaining >= 0)
                            ${{ number_format($remaining, 2) }} left
                        @else
                            <span style="color: #ff4444;">${{ number_format(abs($remaining), 2) }} overspending</span>
                        @endif
                    </small>
                </div>
        @endforeach
    </div>

    <div class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item">
            <span>🏠</span> Dashboard
        </a>
        <a href="{{ route('transaction.expense') }}" class="nav-item">
            <span>💸</span> Expenses
        </a>
        <a href="{{ route('budgets') }}" class="nav-item">
            <span>📊</span> Budget
        </a>
        <a href="{{ route('history') }}" class="nav-item">
            <span>📜</span> History
        </a>
    </div>

</body>

</html>