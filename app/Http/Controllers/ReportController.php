<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    /** 🔹 Laporan Harian */
    public function daily()
    {
        $today = now()->toDateString();

        $transactions = Transaction::whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = $this->getSummary($transactions);
        $products = $this->getProductSales($transactions);

        return view('reports.daily', compact(
            'transactions',
            'summary',
            'products',
            'today'
        ));
    }

    /** 🔹 Laporan Mingguan */
    public function weekly()
    {
        // Tentukan rentang minggu ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        // Ambil transaksi minggu ini
        $transactions = Transaction::with('user')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung ringkasan
        $totalTransactions = $transactions->count();
        $totalRevenue      = $transactions->sum('total_amount');
        $totalItemsSold    = $transactions->sum('total_items');

        // Kirim ke view
        return view('reports.weekly', compact(
            'startOfWeek',
            'endOfWeek',
            'transactions',
            'totalTransactions',
            'totalRevenue',
            'totalItemsSold'
        ));
    
}


    /** 🔹 Export Excel Harian */
    public function exportDailyExcel()
    {
        $today = now()->toDateString();

        $transactions = Transaction::whereDate('created_at', $today)->get();
        $summary = $this->getSummary($transactions);
        $products = $this->getProductSales($transactions);

        return Excel::download(
            new ReportExport($transactions, $summary, $products),
            'laporan-harian-' . now()->format('d-m-Y') . '.xlsx'
        );
    }

    /** 🔹 Export Excel Mingguan */
    public function exportWeeklyExcel()
    {
        $start = now()->startOfWeek()->toDateString();
        $end = now()->endOfWeek()->toDateString();

        $transactions = Transaction::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->get();
        $summary = $this->getSummary($transactions);
        $products = $this->getProductSales($transactions);

        return Excel::download(
            new ReportExport($transactions, $summary, $products),
            'laporan-mingguan-' . now()->format('d-m-Y') . '.xlsx'
        );
    }

    /** 🧩 Ringkasan Statistik */
    private function getSummary($transactions)
    {
        return [
            'total_transactions' => $transactions->count(),
            'total_income' => $transactions->sum('total_amount'),
            'total_items_sold' => $transactions->sum(fn($trx) =>
                collect($trx->items ?? [])->sum('quantity')
            ),
        ];
    }

    /** 🧩 Produk Terjual */
    private function getProductSales($transactions)
    {
        $sales = [];

        foreach ($transactions as $trx) {
            foreach ($trx->items ?? [] as $item) {
                $name = $item['name'] ?? '-';
                $category = $item['category'] ?? '-';
                $qty = $item['quantity'] ?? 0;
                $price = $item['price'] ?? 0;

                if (!isset($sales[$name])) {
                    $sales[$name] = [
                        'name' => $name,
                        'category' => $category,
                        'quantity' => 0,
                        'total' => 0,
                    ];
                }

                $sales[$name]['quantity'] += $qty;
                $sales[$name]['total'] += $qty * $price;
            }
        }

        return array_values($sales);
    }
}
