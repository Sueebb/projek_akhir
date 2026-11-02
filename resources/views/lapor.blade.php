@extends('layouts.app')

@section('title', 'Form Laporan - SMAN 9 Makassar')

@section('content')
<div class="min-h-screen bg-[#6e4e42]">
    <div class="mobile-view relative flex flex-col bg-[#f8fafc]">

        <header class="bg-[#6e4e42] text-white p-6 pb-12 rounded-b-[2rem] shadow-sm relative">
            <a href="{{ route('landing') }}" class="absolute top-6 right-6 w-8 h-8 bg-black/20 rounded-full flex justify-center items-center hover:bg-black/30">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="flex items-center gap-4 mb-2">
                <img src="{{ asset('img/sma9.jpg') }}" alt="Logo SMAN 9 Makassar" class="w-12 h-12 rounded-full bg-white p-0.5 shadow-sm">
                <div>
                    <h1 class="font-extrabold text-lg leading-tight">Form Laporan</h1>
                    <p class="text-xs text-amber-100">Buku Piket SMAN 9</p>
                </div>
            </div>
        </header>

        <div class="px-5 pb-6 flex-1 flex flex-col -mt-8 z-10">

            <div class="bg-[#1f2937] text-white p-5 rounded-2xl mb-6 shadow-md text-center border-b-[6px] border-[#e8a90c]">
                <p class="text-[11px] text-slate-300 font-bold mb-2">Waktu Saat Ini</p>
                <h2 id="liveClockSiswa" class="text-4xl font-black tracking-widest font-mono text-[#34d399]">00:00:00</h2>
                <p id="statusClockSiswa" class="text-[10px] font-bold mt-3 bg-white/10 py-1 px-3 rounded-full inline-block">WAKTU NORMAL</p>
            </div>

            @if(session('sukses'))
                <div class="p-4 mb-4 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-xl border border-emerald-100">
                    <i class="fa-solid fa-circle-check mr-1"></i> {{ session('sukses') }}
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 mb-4 bg-rose-50 text-rose-600 text-xs font-bold rounded-xl border border-rose-100 space-y-1">
                    @foreach($errors->all() as $e)<p><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $e }}</p>@endforeach
                </div>
            @endif

            <form id="mobileStudentForm" method="POST" action="{{ route('lapor.store') }}" class="space-y-5 flex-1 flex flex-col">
                @csrf

                <!-- DATA DIRI -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-sm font-black border-b border-slate-100 pb-3 mb-4 flex items-center"><i class="fa-solid fa-user-graduate text-[#e8a90c] mr-3"></i> Data Diri</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kelas</label>
                            <select name="kelas_id" id="mobileKelas" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-[#6e4e42] transition-colors">
                                <option value="" disabled selected>Pilih Kelas...</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama</label>
                            <select name="siswa_id" id="mobileNama" required disabled class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-[#6e4e42] transition-colors">
                                <option value="" disabled selected>Pilih Kelas Dulu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI LAPORAN -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-sm font-black border-b border-slate-100 pb-3 mb-4 flex items-center"><i class="fa-solid fa-clipboard-list text-[#34d399] mr-3"></i> Kategori Laporan</h3>

                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative"><input type="radio" name="kategori" value="Terlambat" class="peer sr-only" required><div class="p-3 text-center bg-slate-50 border border-slate-200 rounded-xl peer-checked:bg-orange-50 peer-checked:border-orange-500 transition-colors"><span class="text-xs font-bold text-slate-600">Terlambat</span></div></label>
                        <label class="relative"><input type="radio" name="kategori" value="Izin Keluar" class="peer sr-only"><div class="p-3 text-center bg-slate-50 border border-slate-200 rounded-xl peer-checked:bg-blue-50 peer-checked:border-blue-500 transition-colors"><span class="text-xs font-bold text-slate-600">Izin Keluar</span></div></label>
                    </div>

                    <div id="mobileDynArea" class="hidden mt-5">
                        <div id="mobileAreaTL" class="hidden mb-4">
                            <label class="block text-xs font-bold text-slate-700 mb-2">Pilih Atribut (Bisa >1)</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($pelanggaran as $p)
                                    <label class="flex items-center gap-2 p-2 bg-slate-50 border border-slate-200 rounded-lg has-[:checked]:bg-orange-50 has-[:checked]:border-orange-200 transition-colors cursor-pointer">
                                        <input type="checkbox" name="pelanggaran[]" value="{{ $p->id }}" class="w-3.5 h-3.5 accent-orange-600">
                                        <span class="text-[11px] font-bold text-slate-700">{{ $p->nama }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div id="mobileAreaIZ" class="hidden mb-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Keperluan</label>
                            <select name="keperluan_izin" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] outline-none focus:border-blue-500 transition-colors">
                                @include('partials.opsi-izin')
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan / Alasan</label>
                            <textarea name="catatan" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-[#6e4e42] transition-colors resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-2">
                    <button type="submit" class="w-full py-4 bg-[#6e4e42] hover:bg-[#583e35] text-white font-black text-lg rounded-2xl shadow-lg shadow-amber-900/20 transition-all">
                        <i class="fa-solid fa-paper-plane mr-2"></i> KIRIM LAPORAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    pasangDropdownSiswa('mobileKelas', 'mobileNama');
    pasangKategori('mobileStudentForm', 'mobileDynArea', 'mobileAreaTL', 'mobileAreaIZ');

    function jam() {
        const n = new Date();
        document.getElementById('liveClockSiswa').textContent = n.toLocaleTimeString('id-ID').replace(/\./g, ':');
        const telat = n.getHours() > 7 || (n.getHours() === 7 && n.getMinutes() >= 30);
        document.getElementById('statusClockSiswa').textContent = telat ? 'MODE TERLAMBAT AKTIF' : 'WAKTU NORMAL';
    }
    jam(); setInterval(jam, 1000);
</script>
@endpush