<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;

class DosenApiController extends Controller
{
    public function getDosen()
    {
        try {
            $dosen = Dosen::select('nip as id', 'nama', 'jabatan_fungsional as jabatan')
                ->orderBy('nama')
                ->get();
                
            return response()->json($dosen);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data dosen: ' . $e->getMessage()], 500);
        }
    }
}