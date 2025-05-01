<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Budgets</title>
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

        .budget-card {
            background: #1a1a1d;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .budget-info {
            flex: 1;
        }

        .budget-title {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .delete-form {
            margin-left: 20px;
        }

        .delete-form button {
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            font-weight: bold;
        }

        .add-budget {
            text-align: right;
            margin-bottom: 20px;
        }

        .add-budget button {
            background: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #1a1a1d;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .modal-content h3 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .modal-content label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: none;
            background: #2a2a2e;
            color: white;
        }

        .modal-content button {
            margin-top: 20px;
            background: #7366ff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 18px;
            cursor: pointer;
            color: #aaa;
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
            <div class="budget-amount">${{ number_format($totalBudget, 2) }}</div>
            <div class="progress-bar">
                <div class="progress"
                    style="width: {{ $totalBudget > 0 ? ($spendingPerCategory->sum() / $totalBudget) * 100 : 0 }}%">
                </div>
            </div>
            <small>${{ number_format($totalBudget - $spendingPerCategory->sum(), 2) }} left</small>
        </div>

        <div class="add-budget">
            <button onclick="document.getElementById('addBudgetModal').style.display='flex'">➕ Add Budget</button>
        </div>

        @foreach($budgets as $budget)
                @php
                    $spent = $spendingPerCategory[$budget->category_id] ?? 0;
                    $remaining = $budget->amount - $spent;
                    $percentage = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
                @endphp

                <div class="budget-card">
                    <div class="budget-info">
                        <div class="budget-title">{{ $budget->category->name }}</div>
                        <div class="budget-amount">$ {{ number_format($budget->amount, 2) }}</div>
                        <div class="progress-bar">
                            <div class="progress {{ $remaining < 0 ? 'over' : '' }}"
                                style="width: {{ min($percentage, 100) }}%">
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
                    <form action="{{ route('budgets.destroy', $budget->budget_id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit">🗑️</button>
                    </form>
                </div>
        @endforeach
    </div>

    <!-- MODAL -->
    <div class="modal" id="addBudgetModal">
        <div class="modal-content">
            <span class="close-btn"
                onclick="document.getElementById('addBudgetModal').style.display='none'">&times;</span>
            <h3>Add Budget</h3>
            <form method="POST" action="{{ route('budgets.store') }}">
                @csrf
                <label for="category_id">Category</label>
                <select name="category_id" required>
                    <option value="" disabled selected>Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <label for="amount">Amount</label>
                <input type="number" name="amount" step="0.01" required>

                <button type="submit">Add Budget</button>
            </form>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item"><span>🏠</span> Dashboard</a>
        <a href="{{ route('transaction.expense') }}" class="nav-item"><span>💸</span> Expenses</a>
        <a href="{{ route('budgets') }}" class="nav-item"><span>📊</span> Budget</a>
        <a href="{{ route('history') }}" class="nav-item"><span>📜</span> History</a>
    </div>

</body>

</html>