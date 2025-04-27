<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;

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
