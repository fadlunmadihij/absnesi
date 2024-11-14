<?php

namespace App\Http\Controllers;

use App\Models\RekapRequest;
use Illuminate\Http\Request;

class RekapRequestController extends Controller
{
    public function requestRekap(Request $request)
    {
        // Validasi input
        $request->validate([
            'kelas_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Simpan data rekap request
        $rekapRequest = RekapRequest::create([
            'kelas_id' => $request->kelas_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending', // status awal adalah pending
        ]);

        return response()->json(['success' => true, 'message' => 'Rekap request has been saved']);
    }

    public function getPendingRekap()
{
    $requests = RekapRequest::where('status', 'pending')->get();
    return response()->json(['requests' => $requests]);
}

public function updateRekapStatus(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
        'status' => 'required|string',
    ]);

    $rekapRequest = RekapRequest::find($request->id);
    if ($rekapRequest) {
        $rekapRequest->status = $request->status;
        $rekapRequest->save();
        return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Rekap request not found'], 404);
}



}