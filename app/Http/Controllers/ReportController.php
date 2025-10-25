<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function daily()
    {
        $today = now()->format('Y-m-d');
        $transactions = Transaction::whereDate('created_at', $today)->get();
        
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        
        return view('reports.daily', compact('transactions', 'totalRevenue', 'totalTransactions'));
    }

    public function weekly()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $transactions = Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        
        return view('reports.weekly', compact('transactions', 'totalRevenue', 'totalTransactions'));
    }
}