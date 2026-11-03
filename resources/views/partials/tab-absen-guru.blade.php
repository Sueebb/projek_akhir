       <!-- TAB 2: ABSEN GURU (Hanya Admin) -->
        <div id="tabAbsenGuru"
            class="hidden absolute inset-0 p-4 md:p-6 overflow-auto flex flex-col tab-content">
            <div class="bg-white rounded-2xl shadow-sm border flex flex-col min-h-full">
                <!-- HEADER DAN FILTER TANGGAL -->
                <div class="p-6 border-b bg-slate-50/50 flex justify-between items-center rounded-t-2xl">
                    <div>
                        <h2 class="text-lg font-black text-slate-800">
                            Kehadiran Guru Piket
                        </h2>
                        <p class="text-xs text-slate-500">
                            Daftar guru yang bertugas berdasarkan waktu login dan absen.
                        </p>
                    </div>
                    <div class="flex gap-2 items-center">
                        <!-- FILTER TANGGAL -->
                        <form method="GET" action="{{ route('dashboard') }}">
                            <input type="hidden" name="tab" value="tabAbsenGuru">
                            <input
                                type="date"
                                name="tanggal"
                                value="{{ $tanggal }}"
                                onchange="this.form.submit()"
                                class="px-4 py-2 border rounded-lg text-sm font-semibold focus:border-amber-500 outline-none"
                            >
                        </form>
                        <!-- TOMBOL EKSPOR EXCEL -->
                        <a href="{{ route('export', ['jenis' => 'guru', 'tanggal' => $tanggal]) }}"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 transition-colors"
                            title="Ekspor ke Excel">
                                <i class="fa-solid fa-file-excel"></i>
                        </a>
                    </div>
                </div>
                <!-- TABEL KEHADIRAN -->
                <div class="flex-1 overflow-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase text-xs">
                                    Tanggal & Waktu Absen
                                </th>
                                <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase text-xs">
                                    Nama Guru Piket
                                </th>
                                <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase text-xs">
                                    Username Akun
                                </th>
                                <th class="px-6 py-3 text-center font-bold text-slate-500 uppercase text-xs">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="guruTableBody" class="divide-y divide-slate-100 bg-white">
                            @forelse($kehadiran as $k)
                                <tr class="border-b hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-xs font-bold text-slate-600"><i class="fa-regular fa-clock mr-1"></i> {{ substr($k->waktu, 0, 8) }}</td>
                                    <td class="px-6 py-4 text-sm font-black text-slate-800">{{ $k->user->name }}</td>
                                    <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $k->user->username }}</td>
                                    <td class="px-6 py-4 text-center text-xl">
                                        @if(auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('kehadiran.destroy', $k) }}" onsubmit="return confirm('Yakin ingin menghapus data absen guru ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors p-2"><i class="fa-solid fa-circle-xmark"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-6 text-center text-slate-400 italic">Belum ada absen guru pada tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
