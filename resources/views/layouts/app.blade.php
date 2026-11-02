<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Sistem Terpadu Piket - SMAN 9 Makassar')</title>
        <meta name="theme-color" content="#3a2119">
        <link rel="icon" href="{{ asset('img/sma9.jpg') }}">

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Source+Serif+4:wght@600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#7c4a38',
                            secondary: '#fdf7e6',
                            blue: {
                                50: '#fdf7e6', 100: '#faedc4', 200: '#f4dc8e', 300: '#edc556',
                                400: '#e8b02a', 500: '#d9980b', 600: '#7c4a38', 700: '#653a2b',
                                800: '#4f2d22', 900: '#3a2119'
                            }
                        },
                        fontFamily: {
                            sans: ['"Public Sans"', 'system-ui', 'sans-serif'],
                            serif: ['"Source Serif 4"', 'Georgia', 'serif']
                        }
                    }
                }
            }
        </script>

        <style>
            .bg-pattern { background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px; }
            .mobile-view { max-width: 480px; margin: 0 auto; min-height: 100vh; background-color: #f8fafc; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
            .tab-btn { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
            .tab-btn:hover { transform: translateY(-3px); }
            .toast-enter { transform: translateX(100%); opacity: 0; }
            .toast-enter-active { transform: translateX(0); opacity: 1; transition: all 0.3s ease-out; }
            .toast-exit-active { transform: translateX(100%); opacity: 0; transition: all 0.3s ease-in; }
        </style>
    </head>
    <body class="bg-slate-100 text-slate-800 font-sans antialiased">

        @yield('content')

        <script>
            // Tombol mata (tampil/sembunyi password)
            function togglePasswordVisibility(inputId, iconId) {
                const i = document.getElementById(inputId), ic = document.getElementById(iconId);
                const show = i.type === 'password';
                i.type = show ? 'text' : 'password';
                ic.className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
            }

            // Dropdown Nama Siswa yang mengikuti pilihan Kelas
            function pasangDropdownSiswa(kelasId, namaId) {
                const kelas = document.getElementById(kelasId), nama = document.getElementById(namaId);
                kelas.addEventListener('change', async () => {
                    nama.innerHTML = '<option value="" disabled selected>Memuat...</option>';
                    const res  = await fetch("{{ url('/kelas') }}/" + kelas.value + "/siswa");
                    const data = await res.json();
                    nama.innerHTML = '<option value="" disabled selected>Pilih Siswa...</option>';
                    data.forEach(s => nama.appendChild(new Option(s.nama, s.id)));
                    nama.disabled = data.length === 0;
                });
            }

            // Tampilkan area atribut (Terlambat) atau keperluan (Izin Keluar)
            function pasangKategori(formId, dynId, tlId, izId) {
                document.querySelectorAll('#' + formId + ' input[name="kategori"]').forEach(r =>
                    r.addEventListener('change', () => {
                        const tl = r.value === 'Terlambat';
                        document.getElementById(dynId).classList.remove('hidden');
                        document.getElementById(tlId).classList.toggle('hidden', !tl);
                        document.getElementById(izId).classList.toggle('hidden', tl);
                    })
                );
            }
        </script>
        @stack('scripts')
    </body>
</html>