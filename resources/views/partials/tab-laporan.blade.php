       <!-- TAB 1: LAPORAN SISWA (Bisa diakses Guru & Admin) -->
        <div id="tabLaporan" class="absolute inset-0 p-4 md:p-6 overflow-auto flex flex-col tab-content">
            <div class="bg-white rounded-2xl shadow-sm border flex flex-col min-h-full">
                <div class="p-4 md:p-6 border-b bg-slate-50/50 flex flex-wrap justify-between items-center gap-4 rounded-t-2xl">
                    <div><h2 class="text-lg font-black text-slate-800">Riwayat Laporan Siswa</h2></div>
                    <div class="flex flex-wrap gap-2">
                        <form method="GET" action="{{ route('dashboard') }}">
                            <input type="hidden" name="tab" value="tabLaporan">
                            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                                class="px-4 py-2 bg-white border rounded-lg text-sm font-semibold outline-none focus:border-amber-500">
                        </form>
                        <button onclick="openManualModal()" class="px-4 py-2 bg-[#6e4e42] text-white rounded-lg text-sm font-bold shadow-sm hover:bg-[#583e35] transition-colors"><i class="fa-solid fa-user-plus mr-1"></i> Input Manual</button>
                        <a href="{{ route('export', ['jenis' => 'siswa', 'tanggal' => $tanggal]) }}"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-emerald-700 transition-colors">
                                <i class="fa-solid fa-file-excel mr-1"></i> Export
                        </a>
                    </div>
                </div>
                <div class="flex-1 overflow-auto relative">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 sticky top-0 shadow-sm z-10">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase text-xs">Waktu & Tanggal</th>
                                <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase text-xs">Siswa</th>
                                <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase text-xs">Status & Pencatat</th>
                                <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase text-xs">Detail Laporan</th>
                                <th class="px-6 py-3 text-center font-bold text-slate-500 uppercase text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody" class="divide-y divide-slate-100 bg-white">
                            @forelse($laporan as $l)
                                @php
                                    $telat = $l->kategori === 'Terlambat';
                                    $badge = $telat ? 'bg-orange-100 text-orange-700 border-orange-200' : 'bg-blue-100 text-blue-700 border-blue-200';
                                    $ikon  = $telat ? 'fa-clock' : 'fa-person-walking-arrow-right';
                                    $det   = $l->pelanggaran->pluck('nama')->join(', ') ?: $l->keperluan_izin;
                                    $catCls = $l->otomatis_terlambat ? 'text-rose-600 font-bold bg-rose-50 p-1 rounded inline-block mt-1' : 'text-slate-600 italic';
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-700">
                                        <div class="flex items-center"><i class="fa-regular fa-clock mr-2 text-slate-400"></i> {{ substr($l->waktu, 0, 5) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-black text-slate-800">{{ $l->siswa_nama }}</div>
                                        <div class="text-[10px] font-bold text-slate-500 bg-slate-100 inline-block px-1.5 py-0.5 rounded mt-0.5">Kls {{ $l->kelas_nama }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-[11px] font-bold rounded-md border {{ $badge }}"><i class="fa-solid {{ $ikon }} mr-1.5 mt-0.5"></i>{{ $l->kategori }}</span>
                                        <div class="text-[9px] mt-1 text-slate-400"><i class="fa-solid fa-user-pen mr-1"></i>{{ $l->pencatat->name ?? 'Sistem Mandiri (HP)' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        @if($det)
                                            <span class="font-bold text-[10px] px-2 py-1 bg-white border rounded shadow-sm inline-block mb-1">{{ $det }}</span><br>
                                        @endif
                                        <span class="{{ $catCls }} line-clamp-2 hover:line-clamp-none cursor-default break-words w-48">{{ $l->catatan ?: '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-xl">
                                        @if(auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('laporan.destroy', $l) }}" onsubmit="return confirm('Hapus log?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors"><i class="fa-solid fa-circle-xmark"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-6 text-center text-slate-400 italic">Belum ada laporan pada tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
