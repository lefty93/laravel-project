<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    //
    public function createExpense(Request $request)
    {
        $expenseInfo = $request->validate([
            'description' => ['required'],
            'amount' => ['required'],
            'category' => ['required'],
            'expense_date' => ['required']
        ]);
        $expenseInfo['description'] = strip_tags($expenseInfo['description']);
        $expenseInfo['amount'] = strip_tags($expenseInfo['amount']);
        $expenseInfo['category'] = strip_tags($expenseInfo['category']);
        $expenseInfo['expense_date'] = strip_tags($expenseInfo['expense_date']);

        $expenseInfo['user_id'] = auth()->id();
        Expense::create($expenseInfo);
        return redirect('/');
    }

    public function showEditScreen(Expense $expense)
    {
        if (auth()->user()->id !== $expense['user_id']) {
            return redirect('/');
        }
        return view('edit-expense', ['expense' => $expense]);
    }

    public function updateExpense(Expense $expense, Request $request)
    {
        if (auth()->user()->id !== $expense['user_id']) {
            return redirect('/');
        }
        $expenseInfo = $request->validate([
            'description' => ['required'],
            'amount' => ['required'],
            'category' => ['required'],
            'expense_date' => ['required']
        ]);

        $expense->update($expenseInfo);
        return redirect('/');
    }

    public function deleteExpense(Expense $expense)
    {
        if (auth()->user()->id === $expense['user_id']) {
            $expense->delete();
        }
        return redirect('/');
    }

    //showExpense chart
    public function showExpenseChart()
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::id();


        // Fetch data from the expenses table (e.g., group by category)
        $expensesData = Expense::select('category', DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $userId) // Filter by the user's ID
            ->groupBy('category')
            ->get();

        // Prepare data for the chart
        $labels = $expensesData->pluck('category');
        $data = $expensesData->pluck('total_amount');

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
