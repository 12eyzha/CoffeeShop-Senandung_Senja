<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PdfController; // TAMBAH INI
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PDF Routes (taruh di luar middleware jika ingin bisa diakses tanpa login, atau taruh di dalam jika butuh login)
Route::get('/pdf/transaction/{id}', [PdfController::class, 'printTransaction']);
Route::get('/pdf/daily-report', [PdfController::class, 'printDailyReport']);

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $todayRevenue = \App\Models\Transaction::whereDate('created_at', today())->sum('total_amount');
        $todayTransactions = \App\Models\Transaction::whereDate('created_at', today())->count();
        $availableMenus = \App\Models\Menu::where('is_available', true)->count();
        $recentTransactions = \App\Models\Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'todayRevenue', 
            'todayTransactions', 
            'availableMenus', 
            'recentTransactions'
        ));
    });
    
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/history', [TransactionController::class, 'history']);

    // Reports
    Route::get('/reports/daily', [ReportController::class, 'daily']);
    Route::get('/reports/weekly', [ReportController::class, 'weekly']);

    // API Routes for frontend
    Route::get('/api/menus', [MenuController::class, 'getMenusApi']);
    Route::get('/api/categories', [MenuController::class, 'getCategoriesApi']);
    Route::get('/api/transactions', [TransactionController::class, 'getTransactionsApi']);
    Route::post('/api/transactions', [TransactionController::class, 'store']);
});