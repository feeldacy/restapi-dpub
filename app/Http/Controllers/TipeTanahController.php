<?php

namespace App\Http\Controllers;

use App\Models\tipeTanah;
use App\Http\Requests\StoretipeTanahRequest;
use App\Http\Requests\UpdatetipeTanahRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class TipeTanahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $tipeTanahID = IdGenerator::generate(['table' => 'tipe_tanah', 'length' => 9, 'prefix' => 'TG-']);

        $tipeTanahData = $request->validate([
            'nama_tipe_tanah' => 'required|string',
        ]);

        $tipeTanah = TipeTanah::create([
            'id' => $tipeTanahID,
            'nama_tipe_tanah' => $tipeTanahData['nama_tipe_tanah']
        ]);

        return response()->json([
            'message' => 'Tipe Tanah Created ',
            'status' => 'success'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(tipeTanah $tipeTanah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tipeTanah $tipeTanah)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateTipeTanah(Request $request,string $id)
    {
        $tipeTanah = TipeTanah::findOrFail($id);

        $tipeTanahData = $request->validate([
            'nama_tipe_tanah' => 'required|string',
        ]);

        $tipeTanah->update([
            'nama_tipe_tanah' => $tipeTanahData['nama_tipe_tanah'],
        ]);

        return response()->json([
            'message' => 'Tipe Tanah updated successfully',
            'status' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tipeTanah $tipeTanah)
    {
        //
    }
    
    public function getAllTipeTanah(): JsonResponse
    {
        $data = TipeTanah::all(); // Fetch all records
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
