<?php

namespace App\Http\Controllers;

use App\Models\{KehadiranGuru, Laporan};
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export(Request $request, string $jenis)
{
    $tanggal = $request->input('tanggal', now()->toDateString());
    return response()->streamDownload(function () use ($jenis, $tanggal) {
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // supaya Excel membaca UTF-8
 
        if ($jenis === 'siswa') {
            fputcsv($out, ['Waktu', 'Nama', 'Kelas', 'Kategori', 'Detail', 'Catatan', 'Pencatat']);
            Laporan::with(['pencatat', 'pelanggaran'])->where('tanggal', $tanggal)->get()
                ->each(fn ($l) => fputcsv($out, [
                    $l->waktu, $l->siswa_nama, $l->kelas_nama, $l->kategori,
                    $l->pelanggaran->pluck('nama')->join(', ') ?: $l->keperluan_izin,
                    $l->catatan, $l->pencatat->name ?? 'Sistem Mandiri (HP)',
                ]));
        } else {
            fputcsv($out, ['Waktu', 'Nama Guru', 'Username']);
            KehadiranGuru::with('user')->where('tanggal', $tanggal)->get()
                ->each(fn ($k) => fputcsv($out, [$k->waktu, $k->user->name, $k->user->username]));
        }
        fclose($out);
    }, "rekap-$jenis-$tanggal.csv", ['Content-Type' => 'text/csv']);

}
}
