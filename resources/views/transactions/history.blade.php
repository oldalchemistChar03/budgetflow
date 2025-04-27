<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Transaction History - BudgetFlow</title>
    <style>
        body {
            background: #0e0e11;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            padding-bottom: 80px;
            margin: 0;
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

        .balance {
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

        .section-title {
            margin-top: 30px;
            font-size: 18px;
            font-weight: bold;
        }

        .transaction {
            background: #1a1a1d;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transaction-info {
            display: flex;
            flex-direction: column;
        }

        .transaction-amount {
            font-weight: bold;
            font-size: 18px;
        }

        .transaction-income {
            color: #4caf50;
        }

        .transaction-expense {
            color: #ff4444;
        }

        .transaction-category {
            font-size: 14px;
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
            <h2>Transaction History</h2>
            <div class="balance">$ {{ number_format($totalBudget, 2) }}</div>
            <div class="progress-bar">
                <div class="progress" style="width: {{ $budget > 0 ? min(($spent / $budget) * 100, 100) : 0 }}%"></div>
            </div>
            <small>{{ number_format($budget - $spent, 2) }} left</small>
        </div>

        <div class="section-title">Last Records</div>

        @foreach($groupedTransactions as $date => $transactions)
            <h4>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h4>

            @foreach($transactions as $transaction)
                <div class="transaction">
                    <div class="transaction-info">
                        <div>{{ $transaction->category->name ?? 'Unknown Category' }}</div>
                        <div class="transaction-category">{{ $transaction->payment_method ?? 'N/A' }}</div>
                    </div>
                    <div
                        class="transaction-amount {{ $transaction->type === 'income' ? 'transaction-income' : 'transaction-expense' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
    <a href="{{ route('transactions.download') }}"
        style="background: #7366ff; padding: 10px 20px; color: white; border-radius: 8px; text-decoration: none;">
        ⬇️ Download CSV
    </a>


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