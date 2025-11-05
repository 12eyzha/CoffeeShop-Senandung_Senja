<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromView, WithStyles, WithColumnWidths, ShouldAutoSize
{
    public $transactions;
    public $summary;
    public $products;

    public function __construct($transactions, $summary, $products)
    {
        $this->transactions = $transactions;
        $this->summary = $summary;
        $this->products = $products;
    }

    public function view(): View
    {
        return view('exports.report', [
            'transactions' => $this->transactions,
            'summary'      => $this->summary,
            'products'     => $this->products,
        ]);
    }

    // styles() and columnWidths() — boleh pakai yang sudah kamu punya
    public function styles(Worksheet $sheet)
    {
        // ... (style code yang sudah ada)
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, 'B' => 15, 'C' => 18, 'D' => 15, 'E' => 18, 'F' => 22,
        ];
    }
}
