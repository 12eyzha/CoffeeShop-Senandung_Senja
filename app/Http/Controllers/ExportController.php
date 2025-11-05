<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Exports\ReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * 🔹 Halaman Laporan Penjualan (Filter + Tabel + Grafik)
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', now()->endOfMonth()->toDateString());

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = $this->getSummary($transactions);
        $productSales = collect($this->getProductSales($transactions));

        return view('reports.sales', compact(
            'transactions',
            'summary',
            'productSales',
            'startDate',
            'endDate'
        ));
    }

    /**
     * 🔹 Export ke Excel (berdasarkan filter tanggal)
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', now()->endOfMonth()->toDateString());

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->get();

        $summary = $this->getSummary($transactions);
        $products = $this->getProductSales($transactions);

        return Excel::download(
            new ReportExport($transactions, $summary, $products),
            "laporan-penjualan-{$startDate}-sd-{$endDate}.xlsx"
        );
    }

    /**
     * 🧩 API JSON (optional)
     */
    public function getSalesApi(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', now()->endOfMonth()->toDateString());

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->get();

        return response()->json([
            'summary' => $this->getSummary($transactions),
            'products' => $this->getProductSales($transactions),
        ]);
    }

    /**
     * 🧩 Helper: Ringkasan
     */
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

    /**
     * 🧩 Helper: Data Produk Terjual
     */
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
