<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $income = $user->transactions()
            ->where('type', 'income')
            ->sum('amount');

        $expenses = $user->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $income - $expenses;

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $spent = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $budget = $user->budgets()->sum('amount');


        $budgetLeft = $budget - $spent;

        $categorySpend = $user->transactions()
            ->selectRaw('category_id, SUM(amount) as total')
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('home', [
            'balance' => $balance,
            'spent' => $spent,
            'budget' => $budget,
            'budgetLeft' => $budgetLeft,
            'categorySpend' => $categorySpend,
        ]);
    }
}
