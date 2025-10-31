<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException; // Import untuk menangani error DB

class TransactionController extends Controller
{
    public function index()
    {
        // View utama untuk halaman transaksi (tempat menu seharusnya dimuat)
        return view('transactions.index');
    }

    public function history()
    {
        $transactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Catatan: Pastikan di view 'transactions.history' Anda tidak lagi menggunakan json_decode() pada $transaction->items jika sudah menggunakan Model Casting.
        return view('transactions.history', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,qris'
        ]);

        try {
            // Kolom 'items' harus di-cast di model jika datanya JSON.
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

        } catch (QueryException $e) {
             \Log::error('Error storing transaction: ' . $e->getMessage());
             return response()->json(['success' => false, 'message' => 'Gagal menyimpan transaksi: Database error.'], 500);
        }
    }

    public function getTransactionsApi()
    {
        try {
            $transactions = Transaction::with('user')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json($transactions);
        } catch (QueryException $e) {
            // Ini akan mencatat error ke laravel.log jika query gagal
            \Log::error('Database Error in getTransactionsApi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data: Database error.'], 500);
        }
    }
}
