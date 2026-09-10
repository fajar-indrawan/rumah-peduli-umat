<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Donor::latest()->get());
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
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string',
        ]);

        $donor = Donor::create($validated);

        return response()->json(['message' => 'Data donatur berhasil ditambahkan', 'data' => $donor], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Donor $donor)
    {
        return response()->json($donor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donor $donor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string',
        ]);

        $donor->update($validated);

        return response()->json(['message' => 'Data donatur berhasil diperbarui', 'data' => $donor]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donor $donor)
    {
        $donor->delete();

        return response()->json(['message' => 'Data donatur berhasil dihapus']);
    }
}
