<?php

namespace App\Http\Controllers;

use App\Models\KehadiranGuru;
use Illuminate\Http\Request;

class KehadiranGuruController extends Controller
{
    public function store()
    {
        KehadiranGuru::firstOrCreate(
            ['user_id' => auth()->id(), 'tanggal' => now()->toDateString()],
            ['waktu'   => now()->toTimeString()]
        );
        return redirect()->route('dashboard', ['tab' => 'tabAbsenGuru'])
                        ->with('sukses', 'Kehadiran piket tercatat.');
    }

    public function destroy(KehadiranGuru $kehadiran)
    {
        $kehadiran->delete();
        return redirect()->route('dashboard', ['tab' => 'tabAbsenGuru'])
                        ->with('sukses', 'Data kehadiran dihapus.');
    }
}
