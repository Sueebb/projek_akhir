<?php

namespace App\Http\Controllers;

use App\Models\{Kelas, Laporan, Pelanggaran, Siswa};
use Illuminate\Http\Request;

class LaporanController extends Controller
{
     public function create()
    {
        return view('lapor', [
            'kelas'       => Kelas::orderBy('nama_kelas')->get(),
            'pelanggaran' => Pelanggaran::all(),
        ]);
    }
 
    public function siswaByKelas(Kelas $kelas)
    {
        return $kelas->siswa()->orderBy('nama')->get(['id', 'nama']);
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id'       => 'required|exists:siswas,id',
            'kategori'       => 'required|in:Terlambat,Izin Keluar',
            'pelanggaran'    => ['nullable', 'array', 'required_if:kategori,Terlambat'],
            'pelanggaran.*'  => 'exists:pelanggarans,id',
            'keperluan_izin' => ['nullable', 'string', 'required_if:kategori,Izin Keluar'],
            'catatan'        => ['nullable', 'string', 'max:500', 'required_if:kategori,Terlambat'],
        ], [
            'siswa_id.required'          => 'Pilih kelas dan nama siswa.',
            'kategori.required'          => 'Pilih kategori laporan.',
            'pelanggaran.required_if'    => 'Pilih minimal 1 atribut pelanggaran.',
            'catatan.required_if'        => 'Alasan wajib diisi.',
            'keperluan_izin.required_if' => 'Pilih keperluan izin keluar.',
        ]);
        $siswa    = Siswa::with('kelas')->findOrFail($data['siswa_id']);
        $sekarang = now();
        $batas    = $sekarang->copy()->setTime(7, 30, 0);
        $telat    = $sekarang->gt($batas);
 
        $laporan = Laporan::create([
            'siswa_id'           => $siswa->id,
            'siswa_nama'         => $siswa->nama,
            'kelas_nama'         => $siswa->kelas->nama_kelas,
            'user_id'            => auth()->id(), // kosong jika siswa lapor dari HP
            'kategori'           => $telat ? 'Terlambat' : $data['kategori'],
            'keperluan_izin'     => $data['keperluan_izin'] ?? null,
            'catatan'            => $data['catatan'] ?? null,
            'menit_terlambat'    => $telat ? (int) $batas->diffInMinutes($sekarang) : null,
            'otomatis_terlambat' => $telat,
            'tanggal'            => $sekarang->toDateString(),
            'waktu'              => $sekarang->toTimeString(),
        ]);
        $laporan->pelanggaran()->sync($data['pelanggaran'] ?? []);
        return back()->with('sukses', 'Laporan terkirim!');
    }
 
    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return back()->with('sukses', 'Laporan dihapus.');
    }
}