<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    public function index() {
        return MahasiswaModel::all();
    }

    public function store(Request $request) {
        $mahasiswa = MahasiswaModel::create($request->all());
        return response()->json($mahasiswa, 201);
    }

    public function show(MahasiswaModel $mahasiswa) {
        return MahasiswaModel::find($mahasiswa);
    }

    public function update(Request $request, $id)
{
    Log::info('Request body:', $request->all());
    // Validate the input data
    $request->validate([
        'mahasiswa_noHp' => 'required|string|max:15', // Adjust max length as needed
    ]);

    // Find the Mahasiswa record by ID
    $mahasiswa = MahasiswaModel::find($id);

    // Check if Mahasiswa exists
    if (!$mahasiswa) {
        return response()->json([
            'message' => 'Mahasiswa not found'
        ], 404);
    }

    // Update the mahasiswa_noHp field
    $mahasiswa->mahasiswa_noHp = $request->input('mahasiswa_noHp');
    
    // Save the changes
    $mahasiswa->save();

    // Return a success response
    return response()->json([
        'message' => 'Data successfully updated',
        'data' => $mahasiswa
    ], 200);
}


    public function destroy(MahasiswaModel $mahasiswa) {
        $mahasiswa->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
