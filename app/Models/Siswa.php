<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = ['kelas_id', 'nama'];
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function laporan() { return $this->hasMany(Laporan::class); }
}
