/* Tambahan untuk beranda resmi sekolah.
   Logika piket (laporan, login, absen guru, CSV, export) tetap di index.html dan tidak diubah. */

/* QR code selalu mengarah ke form laporan siswa (?mode=form).
   - Saat situs dibuka lewat alamat online, QR otomatis memakai alamat itu.
   - Saat dibuka dari komputer (file:// atau localhost), QR memakai urlCadangan.
   - formUrl: isi hanya jika ingin alamat dikunci (misalnya domain sekolah); kosongkan agar otomatis.
   Data kontak lain boleh dikosongkan; yang kosong tidak akan ditampilkan. */
const SCHOOL_CONFIG = {
    formUrl: window.FORM_URL || '',
    urlCadangan: window.FORM_URL || (location.origin + '/lapor'),
    
    // --- SILAKAN UBAH DATA KONTAK DI BAWAH INI ---
    alamat: 'Jl. Karunrung Raya No.37, Kota Makassar',
    telepon: '(0411) 867086', 
    email: 'info@sman9makassar.sch.id', 
    website: 'sman9makassar.sch.id',
    
    // --- DATA SOSIAL MEDIA ---
    instagram: '@sman9makassar', 
    tiktok: '@sman9.makassar', 
    youtube: 'SMAN 9 Makassar Official'
};

(function () {
    const elDate = document.getElementById('portalDate');
    const elClock = document.getElementById('portalClock');
    const elStatus = document.getElementById('portalClockStatus');

    function tick() {
        const now = new Date();
        if (elDate) elDate.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        if (elClock) elClock.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
        if (elStatus) {
            // Batas masuk sama dengan aturan di index.html (07:30)
            const late = now.getHours() > 7 || (now.getHours() === 7 && now.getMinutes() >= 30);
            elStatus.textContent = late ? 'Sudah lewat batas masuk 07.30' : 'Belum lewat batas masuk 07.30';
            elStatus.className = 'sch-status ' + (late ? 'is-late' : 'is-ok');
        }
    }
    tick();
    setInterval(tick, 1000);

    // Pengaturan mapping icon sosial media menggunakan FontAwesome
    const rows = [
        ['fa-solid fa-location-dot', SCHOOL_CONFIG.alamat],
        ['fa-solid fa-phone', SCHOOL_CONFIG.telepon],
        ['fa-solid fa-envelope', SCHOOL_CONFIG.email],
        ['fa-solid fa-globe', SCHOOL_CONFIG.website],
        ['fa-brands fa-instagram', SCHOOL_CONFIG.instagram],
        ['fa-brands fa-tiktok', SCHOOL_CONFIG.tiktok],
        ['fa-brands fa-youtube', SCHOOL_CONFIG.youtube]
    ].filter(r => r[1]); // Baris yang kosong tidak akan ditampilkan
    
    const list = document.getElementById('schoolContact');
    if (list) {
        list.innerHTML = rows.map(r => `<li><i class="${r[0]} text-base w-5 text-center"></i><span></span></li>`).join('');
        list.querySelectorAll('span').forEach((s, i) => { s.textContent = rows[i][1]; });
    }

    // Alamat form untuk QR
    function alamatForm() {
        if (SCHOOL_CONFIG.formUrl) return { url: SCHOOL_CONFIG.formUrl, cadangan: false };
        const host = location.hostname;
        const online = (location.protocol === 'http:' || location.protocol === 'https:') && host && host !== 'localhost' && host !== '127.0.0.1';
        if (online) {
            const u = new URL(location.href);
            u.pathname = u.pathname.replace(/index\.html$/, '');
            u.search = '?mode=form';
            u.hash = '';
            return { url: u.toString(), cadangan: false };
        }
        return { url: SCHOOL_CONFIG.urlCadangan, cadangan: true };
    }

    // QR code: warna cokelat logo, koreksi kesalahan tinggi (H) agar tetap terbaca dengan logo di tengah
    const qrImg = document.getElementById('qrImage');
    if (qrImg) {
        const qrTxt = document.getElementById('qrUrlText');
        const t = alamatForm();
        qrImg.onerror = () => {
            qrImg.style.visibility = 'hidden';
            if (qrTxt) qrTxt.textContent = 'QR gagal dimuat. Pastikan komputer terhubung ke internet. Alamat form: ' + t.url;
        };
        qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=480x480&ecc=H&margin=0&color=3a2119&bgcolor=ffffff&data=' + encodeURIComponent(t.url);
        if (qrTxt) qrTxt.textContent = t.url + (t.cadangan ? '  (alamat cadangan, karena situs dibuka dari komputer)' : '');
    }

    const yr = document.getElementById('footYear');
    if (yr) yr.textContent = new Date().getFullYear();
})();