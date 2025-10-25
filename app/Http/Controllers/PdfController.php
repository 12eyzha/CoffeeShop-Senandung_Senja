<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function printTransaction($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.transaction', compact('transaction'));
        
        return $pdf->download('invoice-' . $transaction->transaction_code . '.pdf');
    }
    
    public function printDailyReport()
    {
        $today = now()->format('Y-m-d');
        $transactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->get();
            
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        
        $pdf = Pdf::loadView('pdf.daily-report', compact('transactions', 'totalRevenue', 'totalTransactions'));
        
        return $pdf->download('laporan-harian-' . $today . '.pdf');
    }
}