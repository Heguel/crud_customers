<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/customers/trash', [CustomerController::class, 'trashBin'])->name('customers.trash');
Route::get('/customers/restore/{customer}', [CustomerController::class, 'restore'])->name('customers.restore');
Route::delete('/customers/deletePermanently/{customer}', [CustomerController::class, 'deletePermanently'])->name('customers.deletePermanently');
Route::resource('/customers', CustomerController::class);