<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add {{ ucfirst($type) }}</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0e0e11;
            color: #fff;
            padding-bottom: 80px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: #1a1a1d;
            max-width: 700px;

            margin: 40px auto;

            padding: 30px;

            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .tabs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .tabs a {
            flex: 1;
            text-align: center;
            padding: 10px;
            color: #aaa;
            border-bottom: 2px solid transparent;
            text-decoration: none;
        }

        .tabs a.active {
            color:
                {{ $type === 'expense' ? '#ff4444' : '#4caf50' }}
            ;
            border-color: currentColor;
        }

        .amount {
            font-size: 36px;
            text-align: center;
            margin: 20px 0;
            color:
                {{ $type === 'expense' ? '#ff4444' : '#4caf50' }}
            ;
        }

        label {
            display: block;
            margin-top: 20px;
            color: #bbb;
            font-weight: 500;
        }

        input,
        select,
        textarea {
            width: 100%;
            background: #2a2a2e;
            color: white;
            padding: 10px;
            border: none;
            margin-top: 5px;
            border-radius: 6px;
        }

        .subcats {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .pill {
            padding: 6px 14px;
            border-radius: 20px;
            background: #2f2f34;
            font-size: 12px;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .submit-btn {
            margin-top: 30px;
            width: 100%;
            background: #7366ff;
            border: none;
            padding: 14px;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
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
        <div class="card">
            <div class="tabs">
                <a href="{{ route('transaction.expense') }}"
                    class="{{ $type === 'expense' ? 'active' : '' }}">Expense</a>
                <a href="{{ route('transaction.income') }}" class="{{ $type === 'income' ? 'active' : '' }}">Income</a>
            </div>

            <form method="POST" action="{{ route('transaction.store') }}">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="amount">
                    {{ $type === 'expense' ? '-' : '+' }}$
                    <input type="number" name="amount" step="0.01" required
                        style="background: transparent; border: none; font-size: 36px; width: 200px; color: inherit;">
                </div>

                <label>Payment Method</label>
                <input type="text" name="payment_method" placeholder="e.g. Credit Card">

                <label>Category</label>
                <select name="category_id" required>
                    <option value="" disabled selected>— Select Category —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <label>Subcategory</label>
                <div class="subcats">
                    <div class="pill">Car</div>
                    <div class="pill">Plane</div>
                    <div class="pill">+ Add New</div>
                </div>
                <input type="text" name="subcategory" placeholder="Subcategory name">

                <label>Date & Time</label>
                <input type="datetime-local" name="date" value="{{ now()->format('Y-m-d\TH:i') }}" required>

                <label>Notes</label>
                <textarea name="notes" rows="2" placeholder="Optional note..."></textarea>

                <button class="submit-btn" type="submit">
                    Add {{ ucfirst($type) }}
                </button>
            </form>
        </div>
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