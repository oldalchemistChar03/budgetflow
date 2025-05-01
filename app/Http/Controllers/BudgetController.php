<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $budgets = $user->budgets()->with('category')->get();


        $totalBudget = $budgets->sum('amount');

        $spendingPerCategory = $user->transactions()
            ->where('type', 'expense')
            ->selectRaw('category_id, SUM(amount) as total_spent')
            ->groupBy('category_id')
            ->pluck('total_spent', 'category_id');

        $categories = Category::all();

        return view('budgets.index', [
            'budgets' => $budgets,
            'totalBudget' => $totalBudget,
            'spendingPerCategory' => $spendingPerCategory,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        $user->budgets()->create([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        return redirect()->route('budgets')->with('success', 'Budget added successfully!');
    }
    public function destroy($id)
    {
        $budget = Budget::findOrFail($id);

        // Optional: Check if this budget belongs to the logged-in user
        if ($budget->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        $budget->delete();
        return redirect()->route('budgets')->with('success', 'Budget deleted successfully.');
    }


}
