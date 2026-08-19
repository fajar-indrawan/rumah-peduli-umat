<?php

namespace App\Exports;

use App\Services\Reports\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class ProfitLossExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $months;

    public function __construct(array $months)
    {
        $this->months = $months;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $reportService = new ReportService();

        $startDate = reset($this->months) . '-01';
        $endDate   = \Carbon\Carbon::parse(end($this->months))->endOfMonth()->toDateString();

        // Ambil kategori
        $rawData = collect($reportService->getProfitLossMonthly($startDate, $endDate));

        $incomes  = $rawData->where('tipe', 'income');
        $expenses = $rawData->where('tipe', 'expense');

        // Inisialisasi baris total
        $totalIncomeRow  = ['category' => 'Total Income', 'tipe' => ''];
        $totalExpenseRow = ['category' => 'Total Expense', 'tipe' => ''];
        $netIncomeRow    = ['category' => 'Net Income', 'tipe' => ''];

        // Hitung penjumlahan per bulan
        foreach ($this->months as $month) {
            $sumInc = $incomes->sum($month);
            $sumExp = $expenses->sum($month);

            $totalIncomeRow[$month]  = $sumInc;
            $totalExpenseRow[$month] = $sumExp;
            $netIncomeRow[$month]    = $sumInc - $sumExp;
        }

        // Susun urutan baris laporan Excel
        $exportData = collect();

        // Baris Income & Total Income
        foreach ($incomes as $inc) {
            $exportData->push($inc);
        }
        $exportData->push((object) $totalIncomeRow);

        // Baris Expense & Total Expense
        foreach ($expenses as $exp) {
            $exportData->push($exp);
        }
        $exportData->push((object) $totalExpenseRow);

        // Baris Net Income
        $exportData->push((object) $netIncomeRow);

        return $exportData;
    }

    // daftar header
    public function headings(): array
    {
        $headings = ['Kategori'];
        foreach ($this->months as $month) {
            $headings[] = $month;
        }
        return $headings;
    }

    // list rows
    public function map($row): array
    {
        $row = (object) $row;

        $mapped = [
            $row->category ?? '',
        ];

        foreach ($this->months as $month) {
            $mapped[] = $row->{$month} ?? 0;
        }

        return $mapped;
    }
}
