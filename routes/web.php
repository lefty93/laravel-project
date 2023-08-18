<?php

use App\Models\Expense;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $expenses = [];
    $totalAmount = [];
    if (auth()->check()) {
        $expenses = auth()->user()->usersExpenseRecords()->orderBy('expense_date', 'desc')->paginate(10);
        $totalAmount = auth()->user()->usersExpenseRecords()->sum('amount');
    }

    return view('home', ['expenses' => $expenses, 'totalAmount' => $totalAmount]);
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

//Route related to expenseController
Route::post('/create-expense', [ExpenseController::class, 'createExpense']);
Route::get('/edit-expense/{expense}', [ExpenseController::class, 'showEditScreen']);
Route::put('/edit-expense/{expense}', [ExpenseController::class, 'updateExpense']);
Route::delete('/delete-expense/{expense}', [ExpenseController::class, 'deleteExpense']);
