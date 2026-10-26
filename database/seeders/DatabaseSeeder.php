<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create(['name' => 'SUPER ADMIN', 'username' => 'admin', 'password' => bcrypt('123'), 'role' => 'admin']);
        foreach (['Hanya Terlambat','Rambut','Baju','Celana','Make Up','Sepatu'] as $p){ \App\Models\Pelanggaran::create(['nama' => $p]); }
        $kelas = \App\Models\Kelas::create(['nama_kelas' => 'X-1']);
        $kelas->siswa()->createMany([['nama' => 'Contoh Siswa 1'], ['nama' => 'Contoh Siswa 2']]);
    }
}
