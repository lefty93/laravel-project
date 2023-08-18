<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

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

    public function deleteExpense(Expense $expense) {
        if (auth()->user()->id === $expense['user_id']) {
            $expense->delete();
        }
        return redirect('/');
    }
}
