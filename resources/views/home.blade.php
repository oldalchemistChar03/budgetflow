<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BudgetFlow - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0e0e11;
            color: white;
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

        .card {
            background: #1a1a1d;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .balance {
            font-size: 32px;
            font-weight: bold;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .budget-progress {
            height: 10px;
            background: #333;
            border-radius: 8px;
            overflow: hidden;
            margin: 10px 0;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .chip {
            background: #2b2b31;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
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
            <h2>BudgetFlow</h2>
            <p>{{ now()->format('d M Y') }}</p>
        </div>

        <div class="card">
            <div class="section-title">Total Balance</div>
            <div class="balance">${{ number_format($balance, 2) }}</div>
        </div>

        <div class="card">
            <div class="section-title">Budget</div>
            <div style="font-size: 24px; color: #b0f;">${{ number_format($budgetLeft, 2) }} left</div>
            <small style="color: #777;">${{ number_format($spent, 2) }} spent this month</small>
            <div class="budget-progress">
                <div class="budget-progress-bar"
                    style="width: {{ $budget > 0 ? min(100, ($spent / $budget) * 100) : 0 }}%; background: #7366ff; height: 100%;">
                </div>
            </div>
            <div class="chips">
                @forelse($categorySpend as $c)
                    <div class="chip">
                        {{ $c->category->name }} - ${{ number_format($c->total, 2) }}
                    </div>
                @empty
                    <div style="color: #777;">No spending yet</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="section-title">Spending by Category (Donut)</div>
            <div class="chart-wrapper" style="height: 250px; display: flex; justify-content: center;">
                <canvas id="donutChart" width="250" height="250"></canvas>
            </div>

            <div style="text-align: center;">
                <button id="downloadDonutBtn"
                    style="margin-top: 20px; background: #7366ff; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">
                    ⬇️ Download Donut Chart
                </button>
            </div>
        </div>

        <div class="card">
            <div class="section-title">Spending by Category (Bar Graph)</div>
            <div class="chart-wrapper" style="height: 300px; display: flex; justify-content: center;">
                <canvas id="barChart" width="400" height="300"></canvas>
            </div>

            <div style="text-align: center;">
                <button id="downloadBarBtn"
                    style="margin-top: 20px; background: #7366ff; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">
                    ⬇️ Download Bar Chart
                </button>
            </div>
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

    <script>
        const categories = {!! json_encode($categorySpend->pluck('category.name')) !!};
        const amounts = {!! json_encode($categorySpend->pluck('total')) !!};

        const colors = [
            '#ff6384', '#36a2eb', '#4caf50', '#ffcd56',
            '#9966ff', '#00c8c8', '#ff9f40', '#e91e63',
            '#9c27b0', '#00bcd4'
        ];

        // Donut Chart
        const donutCtx = document.getElementById('donutChart').getContext('2d');
        const donutChart = new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: amounts,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#fff' }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Amount Spent ($)',
                    data: amounts,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        ticks: { color: '#ccc' }
                    },
                    y: {
                        ticks: { color: '#ccc', beginAtZero: true },
                        grid: { color: '#333' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Download buttons
        document.getElementById('downloadDonutBtn').addEventListener('click', function () {
            const link = document.createElement('a');
            link.download = 'donut_chart.png';
            link.href = document.getElementById('donutChart').toDataURL('image/png');
            link.click();
        });

        document.getElementById('downloadBarBtn').addEventListener('click', function () {
            const link = document.createElement('a');
            link.download = 'bar_chart.png';
            link.href = document.getElementById('barChart').toDataURL('image/png');
            link.click();
        });
    </script>
</body>

</html>