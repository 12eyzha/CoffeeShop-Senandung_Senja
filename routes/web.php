<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalesReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PDF ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/pdf/transaction/{id}', [PdfController::class, 'printTransaction']);
Route::get('/pdf/daily-report', [PdfController::class, 'printDailyReport']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Hanya bisa diakses setelah login)
|--------------------------------------------------------------------------
*/
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
    })->name('dashboard');

    // Redirect root ke dashboard
    Route::get('/', fn() => redirect('/dashboard'));

    /*
    |--------------------------------------------------------------------------
    | TRANSACTIONS
    |--------------------------------------------------------------------------
    */
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');

    /*
    |--------------------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------------------
    */
   Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');


    // (opsional nanti jika laporan penjualan aktif)
    // Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');

    /*
    |--------------------------------------------------------------------------
    | DATA MASTER
    |--------------------------------------------------------------------------
    */
    Route::resource('customers', CustomerController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('menus', MenuController::class);

    /*
    |--------------------------------------------------------------------------
    | API ROUTES (untuk front-end dynamic data)
    |--------------------------------------------------------------------------
    */
    Route::get('/api/menus', [MenuController::class, 'getMenusApi']);
    Route::get('/api/categories', [MenuController::class, 'getCategoriesApi']);
    Route::get('/api/transactions', [TransactionController::class, 'getTransactionsApi']);
    Route::post('/api/transactions', [TransactionController::class, 'store']);

    // (Optional) API laporan penjualan untuk AJAX
    Route::get('/api/reports/sales', [SalesReportController::class, 'getSalesApi'])
        ->name('api.reports.sales');
});

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Halaman tidak ditemukan.');
});
