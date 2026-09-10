<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Donor;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_donatur' => Donor::count(),
            'total_transaksi' => Donation::count(),
            'total_nominal' => Donation::sum('nominal'),
        ]);
    }
}
