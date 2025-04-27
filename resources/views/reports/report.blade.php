<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Financial Report - BudgetFlow</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .section {
            margin-bottom: 20px;
        }

        h2 {
            color: #7366ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>BudgetFlow - Financial Report</h1>
    <p>Generated on {{ now()->format('d M Y') }}</p>

    <div class="section">
        <h2>Overview</h2>
        <p><strong>Total Income:</strong> ${{ number_format($income, 2) }}</p>
        <p><strong>Total Expenses:</strong> ${{ number_format($expenses, 2) }}</p>
        <p><strong>Balance:</strong> ${{ number_format($balance, 2) }}</p>
    </div>

    <div class="section">
        <h2>Budgets</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgets as $budget)
                    <tr>
                        <td>{{ $budget->category->name }}</td>
                        <td>${{ number_format($budget->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Transactions</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>{{ $transaction->category->name }}</td>
                        <td>${{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>