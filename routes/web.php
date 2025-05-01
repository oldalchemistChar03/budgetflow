<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportController;
Route::get('/', function () {
    return redirect()->route('login.view');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.view');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.view');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (requires login)
//Route::middleware('auth')->group(function () {
// Redirect /dashboard to /home
//  Route::get('/dashboard', fn() => redirect('/home'))->name('dashboard');

// Homepage
Route::get('/home', [HomepageController::class, 'index'])->name('home');

Route::get('/add-expense', [TransactionController::class, 'createExpense'])->name('transaction.expense');
Route::get('/add-income', [TransactionController::class, 'createIncome'])->name('transaction.income');
Route::post('/add-transaction', [TransactionController::class, 'store'])->name('transaction.store');



Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets');
Route::get('/history', [TransactionController::class, 'history'])->name('history');


Route::get('/budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
Route::post('/budgets/store', [BudgetController::class, 'store'])->name('budgets.store');
Route::get('/transactions/download', [TransactionController::class, 'download'])->name('transactions.download');
Route::get('/recurring/check', function () {
    $dueTransactions = \App\Models\Transaction::where('recurring', '!=', 'none')
        ->whereDate('next_due_date', '<=', now())
        ->get();

    foreach ($dueTransactions as $transaction) {

        $newTransaction = $transaction->replicate();
        $newTransaction->date = now();
        $newTransaction->next_due_date = match ($transaction->recurring) {
            'daily' => now()->addDay(),
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
            default => null,
        };
        $newTransaction->save();

    }

    return 'Recurring transactions processed ✅';
});


Route::get('/report/download', [ReportController::class, 'download'])->name('report.download');
Route::delete('/transactions/{id}', [App\Http\Controllers\TransactionController::class, 'destroy'])->name('transaction.destroy');
Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
