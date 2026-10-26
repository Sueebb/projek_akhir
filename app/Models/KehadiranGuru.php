<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KehadiranGuru extends Model
{
    protected $table = 'kehadiran_gurus';
    protected $fillable = ['user_id', 'tanggal', 'waktu'];
    public function user(){ return $this->belongsTo(User::class); }
}
