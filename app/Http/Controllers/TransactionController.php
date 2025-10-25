<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }

    public function history()
    {
        $transactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('transactions.history', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,qris'
        ]);

        $transaction = Transaction::create([
            'transaction_code' => 'TRX-' . time() . '-' . rand(1000, 9999),
            'items' => $request->items,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'transaction' => $transaction,
            'message' => 'Transaksi berhasil disimpan!'
        ]);
    }

    public function getTransactionsApi()
    {
        $transactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($transactions);
    }
}