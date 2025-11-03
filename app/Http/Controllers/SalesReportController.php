<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Ambil transaksi berdasarkan tanggal
        $transactions = Transaction::with('user')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // 🔹 Ringkasan
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_income' => $transactions->sum('total_amount'),
            'total_items_sold' => $transactions->sum(function ($trx) {
                return collect($trx->items)->sum('quantity');
            }),
        ];

        // 🔹 Hitung total penjualan per produk
        $productSales = collect();
        foreach ($transactions as $trx) {
            foreach ($trx->items as $item) {
                $menuName = $item['name'] ?? '-';
                $category = $item['category'] ?? '-';
                $quantity = $item['quantity'] ?? 0;
                $total = ($item['price'] ?? 0) * $quantity;

                if ($productSales->has($menuName)) {
                    $existing = $productSales->get($menuName);
                    $productSales->put($menuName, [
                        'name' => $menuName,
                        'category' => $category,
                        'quantity' => $existing['quantity'] + $quantity,
                        'total' => $existing['total'] + $total,
                    ]);
                } else {
                    $productSales->put($menuName, [
                        'name' => $menuName,
                        'category' => $category,
                        'quantity' => $quantity,
                        'total' => $total,
                    ]);
                }
            }
        }

        $productSales = $productSales->sortByDesc('quantity');

        return view('reports.sales', compact(
            'transactions',
            'summary',
            'productSales',
            'startDate',
            'endDate'
        ));
    }
}
