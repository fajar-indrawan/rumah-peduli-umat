<?php

namespace App\Http\Controllers\Api\Reports;

use App\Exports\ProfitLossExport;
use App\Http\Controllers\Api\BaseApiController;
use App\Services\Reports\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends BaseApiController
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    
    /**
     * Display a listing of the resource for grid report.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', '2022-01-01');
        $endDate   = $request->input('end_date', '2022-03-31');

        $data = $this->reportService->getProfitLossMonthly($startDate, $endDate);

        return response()->json([
            'success' => true,
            'message' => 'Laporan Profit Loss Berhasil Dimuat',
            'data'    => $data,
        ]);
    }
    
    public function exportProfitLoss(Request $request)
    {
        $months = $request->input('months', ['2022-01', '2022-02', '2022-03']);

        // Langsung panggil export dengan mengirimkan $months saja
        return Excel::download(
            new ProfitLossExport($months),
            'Laporan_Profit_Loss_' . date('Ymd_His') . '.xlsx'
        );
    }
}
