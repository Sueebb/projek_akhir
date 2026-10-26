<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = ['siswa_id', 'siswa_nama', 'kelas_nama', 'user_id', 'kategori', 'keperluan_izin', 'catatan', 'menit_pertama', 'otomatis_terlambat', 'tanggal', 'waktu'];
    public function siswa(){ return $this->belongsTo(Siswa::class); }
    public function pencatat(){ return $this->belongsTo(User::class, 'user_id'); }
    public function pelanggaran(){ return $this->belongsToMany(Pelanggaran::class. 'laporan_pelanggaran'); }
}
