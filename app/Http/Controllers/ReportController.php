<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * 🔹 Laporan Harian
     */
    public function daily()
    {
        $today = now()->format('Y-m-d');

        // Ambil transaksi hari ini beserta kasir (user)
        $transactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ringkasan
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalItemsSold = $transactions->sum(fn($trx) => collect($trx->items)->sum('quantity'));

        return view('reports.daily', compact(
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'totalItemsSold',
            'today'
        ));
    }

    /**
     * 🔹 Laporan Mingguan
     */
    public function weekly()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        // Ambil transaksi selama minggu ini
        $transactions = Transaction::with('user')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ringkasan
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalItemsSold = $transactions->sum(fn($trx) => collect($trx->items)->sum('quantity'));

        return view('reports.weekly', compact(
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'totalItemsSold',
            'startOfWeek',
            'endOfWeek'
        ));
    }
}
