<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;

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

    public function update(Request $request, $mahasiswaId)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'mahasiswa_noHp' => 'required|string|max:15|regex:/^\d+$/'  // Assuming phone number has max length
    ]);

    try {
        // Find the specific mahasiswa (student) record by ID
        $mahasiswa = MahasiswaModel::findOrFail($mahasiswaId);

        // Update the phone number
        $mahasiswa->mahasiswa_noHp = $validatedData['mahasiswa_noHp'];

        // Save the updated record
        $mahasiswa->save();

        // Return a success response
        return response()->json([
            'message' => 'Phone number updated successfully',
            'mahasiswa' => $mahasiswa
        ], 200);

    } catch (\Exception $e) {
        // Handle any errors that might occur during the update process
        return response()->json([
            'message' => 'Error updating phone number',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy(MahasiswaModel $mahasiswa) {
        $mahasiswa->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
