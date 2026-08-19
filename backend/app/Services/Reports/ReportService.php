<?php

namespace App\Services\Reports;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ReportService
{    
    /**
     * Mengambil data Laporan Profit & Loss Matriks Bulanan
     */
    public function getProfitLossMonthly(string $startDate = '2022-01-01', string $endDate = '2022-03-31')
    {
        // Dapatkan daftar bulan secara otomatis berdasarkan rentang tanggal
        $period = CarbonPeriod::create($startDate, '1 month', $endDate);
        
        $selectColumns = [
            'c.nama as category',
            'c.tipe'
        ];

        // SUM(CASE WHEN ...) dinamis per bulan
        foreach ($period as $date) {
            $month = $date->format('Y-m');
            $selectColumns[] = DB::raw("
                SUM(
                    CASE WHEN DATE_FORMAT(t.tanggal, '%Y-%m') = '{$month}' THEN 
                        CASE 
                            WHEN c.tipe = 'income' THEN COALESCE(t.credit, 0) - COALESCE(t.debit, 0)
                            ELSE COALESCE(t.debit, 0) - COALESCE(t.credit, 0) 
                        END 
                    ELSE 0 
                    END
                ) AS `{$month}`
            ");
        }

        // Eksekusi Raw Query Builder
        return DB::table('categories as c')
            ->leftJoin('chart_of_accounts as coa', 'c.id', '=', 'coa.id_kategori')
            ->leftJoin('transactions as t', function ($join) use ($startDate, $endDate) {
                $join->on('coa.kode', '=', 't.kode_coa')
                     ->whereBetween('t.tanggal', [$startDate, $endDate]);
            })
            ->select($selectColumns)
            ->groupBy('c.id', 'c.nama', 'c.tipe')
            ->orderByRaw("FIELD(c.tipe, 'income', 'expense'), c.id")
            ->get();
    }
}
