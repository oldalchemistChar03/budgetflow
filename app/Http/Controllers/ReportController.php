<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Budget;
use PDF;

class ReportController extends Controller
{
    public function download()
    {
        $user = auth()->user();

        $transactions = $user->transactions()->with('category')->get();
        $budgets = $user->budgets()->with('category')->get();
        $income = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expenses;

        $data = [
            'user' => $user,
            'transactions' => $transactions,
            'budgets' => $budgets,
            'income' => $income,
            'expenses' => $expenses,
            'balance' => $balance,
        ];

        $pdf = PDF::loadView('reports.report', $data);

        return $pdf->download('financial_report.pdf');
    }
}
