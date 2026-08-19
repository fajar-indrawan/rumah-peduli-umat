<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChartOfAccountController;
use App\Http\Controllers\Api\Reports\ReportController;
use App\Http\Controllers\Api\TransactionController;

// Dapat diakses via: GET http://localhost:8000/api/categories
// Route::get('categories', CategoryController::class);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{category}', [CategoryController::class, 'show'])->whereNumber('category');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->whereNumber('category');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->whereNumber('category');


Route::get('/chart-of-accounts', [ChartOfAccountController::class, 'index']);
Route::post('/chart-of-accounts', [ChartOfAccountController::class, 'store']);
Route::get('/chart-of-accounts/{chart_of_account}', [ChartOfAccountController::class, 'show'])->whereNumber('chart_of_account');
Route::put('/chart-of-accounts/{chart_of_account}', [ChartOfAccountController::class, 'update'])->whereNumber('chart_of_account');
Route::delete('/chart-of-accounts/{chart_of_account}', [ChartOfAccountController::class, 'destroy'])->whereNumber('chart_of_account');

Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->whereNumber('transaction');
Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->whereNumber('transaction');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->whereNumber('transaction');

Route::get('/reports/profit-loss', [ReportController::class, 'index']);
Route::get('/reports/profit-loss/export', [ReportController::class, 'exportProfitLoss']);