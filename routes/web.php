<?php

use App\Http\Controllers\FinanceExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/quests',    'quests')->name('quests.index');
    Route::view('/finance',   'finance')->name('finance.index');
    Route::view('/library',   'library')->name('library.index');

    // Finance Export
    Route::get('/finance/export/csv', [FinanceExportController::class, 'csv'])->name('finance.export.csv');
    Route::get('/finance/export/pdf', [FinanceExportController::class, 'pdf'])->name('finance.export.pdf');

    Route::middleware('auth')->group(function () {
        Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
