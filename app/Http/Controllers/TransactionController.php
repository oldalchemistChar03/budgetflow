<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function createExpense()
    {
        $categories = Category::all();
        return view('transactions.form', [
            'type' => 'expense',
            'categories' => $categories,
        ]);
    }


    public function createIncome()
    {
        $categories = Category::all();
        return view('transactions.form', [
            'type' => 'income',
            'categories' => $categories,
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,category_id',
            'date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->withErrors('You must be logged in.');
        }


        $user->transactions()->create([
            'amount' => $request->amount,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'payment_method' => $request->payment_method,
            'subcategory' => $request->subcategory,
            'notes' => $request->notes,
        ]);

        return redirect()->route('home')->with('success', 'Transaction added successfully!');
    }
    public function history()
    {
        $user = Auth::user();

        $transactions = $user->transactions()
            ->with('category')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->date->format('Y-m-d');
            });

        $budget = $user->budgets()->sum('amount');

        $spent = $user->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $totalBudget = $budget;

        return view('transactions.history', [
            'groupedTransactions' => $transactions,
            'budget' => $budget,
            'spent' => $spent,
            'totalBudget' => $totalBudget,
        ]);
    }
    public function download()
    {
        $user = Auth::user();

        $transactions = $user->transactions()->with('category')->orderBy('date', 'desc')->get();

        $filename = 'transactions.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['Date', 'Type', 'Amount', 'Category', 'Payment Method', 'Subcategory', 'Notes'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->date->format('Y-m-d H:i'),
                    ucfirst($transaction->type),
                    $transaction->amount,
                    $transaction->category->name ?? 'N/A',
                    $transaction->payment_method ?? 'N/A',
                    $transaction->subcategory ?? 'N/A',
                    $transaction->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


}
