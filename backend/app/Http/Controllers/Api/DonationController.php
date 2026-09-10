<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donations = Donation::with('donor')->latest()->get();

        return response()->json($donations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'tanggal_donasi' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'jenis_donasi' => 'required|in:Zakat,Infaq,Sedekah',
            'keterangan' => 'nullable|string',
        ]);

        $donation = Donation::create($validated);

        return response()->json(['message' => 'Transaksi donasi berhasil ditambahkan', 'data' => $donation], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        return response()->json($donation->load('donor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'tanggal_donasi' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'jenis_donasi' => 'required|in:Zakat,Infaq,Sedekah',
            'keterangan' => 'nullable|string',
        ]);

        $donation->update($validated);

        return response()->json(['message' => 'Transaksi donasi berhasil diperbarui', 'data' => $donation]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $donation->delete();

        return response()->json(['message' => 'Transaksi donasi berhasil dihapus']);
    }
}
