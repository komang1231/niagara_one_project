// Filter rentang tanggal / bulan / tahun — dipakai <x-filter.date-range>.
//
// Kalender dibuat sendiri (tanpa library), gaya Traveloka: popup 2 panel, klik tanggal awal lalu tanggal akhir,
// rentang di-highlight pakai warna accent. Nilai yang dikirim ke server disimpan di 2 input hidden (ISO):
//   date  -> 2026-06-08     month -> 2026-06     year -> 2026
//
// Cara pakai di user:
//   1. Klik kotak "Dari" / "Sampai" -> popup kalender muncul.
//   2. Klik tanggal awal, lalu tanggal akhir (klik sebelum tanggal awal = jadi tanggal awal baru).
//   3. Klik "Terapkan" -> nilai masuk ke field. Klik di luar popup / Esc = batal.
//   4. Tekan tombol Search di filter bar untuk benar-benar memfilter.
// Rentang boleh sebagian (hanya "dari" atau hanya "sampai" -> tekan Terapkan tanpa memilih tanggal akhir).

const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const BULAN_PENDEK = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const HARI_PENDEK = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']; // minggu dimulai hari Senin
const ISI_TAHUN = 12; // jumlah tahun per panel (type year)
const TYPE_VALID = ['date', 'month', 'year'];
const SATU_HARI = 86400000;

const p2 = (n) => String(n).padStart(2, '0');

/* ============================================================
   FUNGSI MURNI (tanpa DOM)
   ============================================================ */

// "2026-06-08" | "2026-06" | "2026" -> Date lokal (null kalau kosong / tidak valid)
export function keDate(iso) {
    const m = String(iso ?? '').trim().match(/^(\d{4})(?:-(\d{2}))?(?:-(\d{2}))?/);
    if (!m) return null;
    return new Date(Number(m[1]), m[2] ? Number(m[2]) - 1 : 0, m[3] ? Number(m[3]) : 1);
}

// Date -> string ISO sesuai type
export function keIso(date, type) {
    if (!date) return '';
    if (type === 'year') return String(date.getFullYear());
    if (type === 'month') return `${date.getFullYear()}-${p2(date.getMonth() + 1)}`;
    return `${date.getFullYear()}-${p2(date.getMonth() + 1)}-${p2(date.getDate())}`;
}

// Date -> timestamp awal unit (hari / bulan / tahun) supaya bisa dibandingkan dengan angka. null kalau kosong.
export function kunci(date, type) {
    if (!date) return null;
    if (type === 'year') return new Date(date.getFullYear(), 0, 1).getTime();
    if (type === 'month') return new Date(date.getFullYear(), date.getMonth(), 1).getTime();
    return new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
}

// timestamp -> teks yang tampil di field & ringkasan
export function formatTampil(ts, type) {
    if (ts === null || ts === undefined) return '';
    const d = new Date(ts);
    if (type === 'year') return String(d.getFullYear());
    if (type === 'month') return `${BULAN_PENDEK[d.getMonth()]} ${d.getFullYear()}`;
    return `${p2(d.getDate())} ${BULAN_PENDEK[d.getMonth()]} ${d.getFullYear()}`;
}

// Aturan klik: state { from, to, target } + timestamp yang diklik -> state baru
//   target 'from' : klik = tanggal awal baru (rentang lama dibuang), lanjut pilih akhir
//   target 'to'   : klik sebelum awal = jadi awal baru; selain itu = tanggal akhir (rentang lengkap)
export function aturPilihan(state, ts) {
    if (state.target === 'to' && state.from !== null) {
        if (ts < state.from) return { from: ts, to: null, target: 'to' };
        return { from: state.from, to: ts, target: 'from' };
    }
    return { from: ts, to: null, target: 'to' };
}

/* ============================================================
   KOMPONEN
   ============================================================ */

function initDateRange(root) {
    if (root._dateRange) return; // jangan dobel
    root._dateRange = true;

    const type = TYPE_VALID.includes(root.dataset.rangeType) ? root.dataset.rangeType : 'date';
    const label = root.dataset.rangeLabel || 'Rentang';
    const kolom = type === 'date' ? 7 : type === 'month' ? 3 : 4;
    const nama = type === 'date' ? 'tanggal' : type === 'month' ? 'bulan' : 'tahun';

    const control = root.querySelector('[data-range-control]');
    const tombol = { from: root.querySelector('[data-range-btn="from"]'), to: root.querySelector('[data-range-btn="to"]') };
    const teks = { from: root.querySelector('[data-range-text="from"]'), to: root.querySelector('[data-range-text="to"]') };
    const nilai = { from: root.querySelector('[data-range-value="from"]'), to: root.querySelector('[data-range-value="to"]') };
    if (!control || !tombol.from || !tombol.to || !nilai.from || !nilai.to) return;

    const placeholder = { from: teks.from.textContent.trim() || 'Dari', to: teks.to.textContent.trim() || 'Sampai' };
    const hariIni = kunci(new Date(), type);

    let terbuka = false;
    let draft = { from: null, to: null, target: 'from' };
    let hover = null;
    let tampilan = new Date(); // acuan panel kiri

    /* ---------- popup ---------- */
    const popup = document.createElement('div');
    popup.className = `app-dr app-dr--${type}`;
    popup.hidden = true;
    popup.setAttribute('role', 'dialog');
    popup.setAttribute('aria-label', `Pilih ${label}`);
    popup.innerHTML = `
        <div class="app-dr__panels" data-dr-panels></div>
        <div class="app-dr__foot">
            <div class="app-dr__summary" data-dr-summary></div>
            <div class="app-dr__foot-actions">
                <button type="button" class="app-dr__reset" data-dr-reset>Reset</button>
                <button type="button" class="app-dr__apply" data-dr-apply>Terapkan</button>
            </div>
        </div>`;
    root.appendChild(popup);

    const wadahPanel = popup.querySelector('[data-dr-panels]');
    const ringkasan = popup.querySelector('[data-dr-summary]');

    /* ---------- nilai tersimpan (hidden input) <-> tampilan field ---------- */
    const bacaNilai = (sisi) => kunci(keDate(nilai[sisi].value), type);

    function tulisField() {
        ['from', 'to'].forEach((sisi) => {
            const ts = bacaNilai(sisi);
            teks[sisi].textContent = ts === null ? placeholder[sisi] : formatTampil(ts, type);
            tombol[sisi].classList.toggle('is-empty', ts === null);
        });
    }

    function simpanNilai() {
        const ubah = (sisi, ts) => {
            const baru = ts === null ? '' : keIso(new Date(ts), type);
            if (nilai[sisi].value !== baru) {
                nilai[sisi].value = baru;
                nilai[sisi].dispatchEvent(new Event('change', { bubbles: true }));
            }
        };
        ubah('from', draft.from);
        ubah('to', draft.to);
        tulisField();
    }

    /* ---------- navigasi ---------- */
    function aturTampilan() {
        const acuan = draft.from ?? draft.to ?? hariIni;
        const d = new Date(acuan);
        if (type === 'date') tampilan = new Date(d.getFullYear(), d.getMonth(), 1);
        else if (type === 'month') tampilan = new Date(d.getFullYear(), 0, 1);
        else tampilan = new Date(Math.floor(d.getFullYear() / ISI_TAHUN) * ISI_TAHUN, 0, 1);
    }

    function geser(arah) {
        const y = tampilan.getFullYear();
        const m = tampilan.getMonth();
        if (type === 'date') tampilan = new Date(y, m + arah, 1);
        else if (type === 'month') tampilan = new Date(y + arah, 0, 1);
        else tampilan = new Date(y + arah * ISI_TAHUN, 0, 1);
        render();
    }

    /* ---------- render ---------- */
    function bangunPanel(offset) {
        const y = tampilan.getFullYear();
        const m = tampilan.getMonth();
        let judul = '';
        let isi = '';
        let tambahan = '';

        if (type === 'date') {
            const awal = new Date(y, m + offset, 1);
            const th = awal.getFullYear();
            const bl = awal.getMonth();
            const jumlah = new Date(th, bl + 1, 0).getDate();
            const kosong = (awal.getDay() + 6) % 7; // Senin = 0

            judul = `${BULAN[bl]} ${th}`;
            tambahan = `<div class="app-dr__weekdays">${HARI_PENDEK.map((h) => `<span>${h}</span>`).join('')}</div>`;
            isi += '<span class="app-dr__blank"></span>'.repeat(kosong);

            for (let tgl = 1; tgl <= jumlah; tgl++) {
                const ts = new Date(th, bl, tgl).getTime();
                const kol = (kosong + tgl - 1) % 7;
                const tepi = (kol === 0 || tgl === 1 ? ' is-edge-first' : '') + (kol === 6 || tgl === jumlah ? ' is-edge-last' : '');
                isi += sel(ts, tgl, `${tgl} ${BULAN[bl]} ${th}`, tepi);
            }
        } else if (type === 'month') {
            const th = y + offset;
            judul = String(th);
            for (let i = 0; i < 12; i++) {
                const ts = new Date(th, i, 1).getTime();
                const tepi = (i % kolom === 0 ? ' is-edge-first' : '') + (i % kolom === kolom - 1 ? ' is-edge-last' : '');
                isi += sel(ts, BULAN_PENDEK[i], `${BULAN[i]} ${th}`, tepi);
            }
        } else {
            const mulai = y + offset * ISI_TAHUN;
            judul = `${mulai} – ${mulai + ISI_TAHUN - 1}`;
            for (let i = 0; i < ISI_TAHUN; i++) {
                const ts = new Date(mulai + i, 0, 1).getTime();
                const tepi = (i % kolom === 0 ? ' is-edge-first' : '') + (i % kolom === kolom - 1 ? ' is-edge-last' : '');
                isi += sel(ts, mulai + i, String(mulai + i), tepi);
            }
        }

        return `
            <div class="app-dr__panel app-dr__panel--${offset + 1}">
                <div class="app-dr__head">
                    <button type="button" class="app-dr__nav app-dr__nav--prev" data-dr-nav="-1" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                    <span class="app-dr__title">${judul}</span>
                    <button type="button" class="app-dr__nav app-dr__nav--next" data-dr-nav="1" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
                </div>
                ${tambahan}
                <div class="app-dr__grid" style="--dr-cols: ${kolom}">${isi}</div>
            </div>`;
    }

    function sel(ts, teksSel, labelSel, tepi) {
        const sekarang = ts === hariIni ? ' is-today' : '';
        return `<button type="button" class="app-dr__cell${tepi}${sekarang}" data-ts="${ts}" aria-label="${labelSel}"><span class="app-dr__num">${teksSel}</span></button>`;
    }

    function render() {
        wadahPanel.innerHTML = bangunPanel(0) + bangunPanel(1);
        paint();
    }

    // Update class sel (terpilih / rentang) tanpa membangun ulang DOM
    function paint() {
        const awal = draft.from;
        const akhir = draft.to ?? (hover !== null && awal !== null && hover > awal ? hover : null);
        const adaRentang = awal !== null && akhir !== null && akhir > awal;

        wadahPanel.querySelectorAll('[data-ts]').forEach((el) => {
            const ts = Number(el.dataset.ts);
            const terpilih = ts === awal || (draft.to !== null && ts === draft.to);

            el.classList.toggle('is-selected', terpilih);
            el.classList.toggle('is-band', adaRentang && ts > awal && ts < akhir);
            el.classList.toggle('is-band-start', adaRentang && ts === awal);
            el.classList.toggle('is-band-end', adaRentang && ts === akhir);
            el.classList.toggle('is-preview', draft.to === null && adaRentang && ts === akhir);
        });

        // segmen yang sedang diisi
        ['from', 'to'].forEach((sisi) => {
            tombol[sisi].classList.toggle('is-active', terbuka && draft.target === sisi);
        });

        // ringkasan di footer
        if (draft.from === null) {
            ringkasan.textContent = `Pilih ${nama} awal`;
        } else if (draft.to === null) {
            ringkasan.textContent = `${formatTampil(draft.from, type)} – pilih ${nama} akhir`;
        } else {
            const hari = type === 'date' ? ` (${Math.round((draft.to - draft.from) / SATU_HARI) + 1} hari)` : '';
            ringkasan.textContent = `${formatTampil(draft.from, type)} – ${formatTampil(draft.to, type)}${hari}`;
        }
    }

    /* ---------- buka / tutup ---------- */
    function posisikan() {
        popup.style.left = '0';
        popup.style.right = 'auto';
        const r = popup.getBoundingClientRect();
        if (r.right > window.innerWidth - 12) {
            popup.style.left = 'auto';
            popup.style.right = '0';
        }
    }

    function targetAwal(sisi) {
        return sisi === 'to' && draft.from !== null ? 'to' : 'from';
    }

    function buka(sisi) {
        if (terbuka) {
            draft.target = targetAwal(sisi);
            paint();
            return;
        }

        draft = { from: bacaNilai('from'), to: bacaNilai('to'), target: 'from' };
        draft.target = targetAwal(sisi);
        hover = null;
        aturTampilan();
        render();

        popup.hidden = false;
        terbuka = true;
        root.classList.add('is-open');
        Object.values(tombol).forEach((b) => b.setAttribute('aria-expanded', 'true'));
        paint();
        posisikan();
    }

    function tutup(fokus = false) {
        if (!terbuka) return;
        terbuka = false;
        hover = null;
        popup.hidden = true;
        root.classList.remove('is-open');
        Object.values(tombol).forEach((b) => {
            b.setAttribute('aria-expanded', 'false');
            b.classList.remove('is-active');
        });
        if (fokus) tombol.from.focus();
    }

    /* ---------- event ---------- */
    control.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-range-btn]');
        if (!btn && terbuka) tutup(); // klik ikon / "to" saat terbuka = tutup
        else buka(btn ? btn.dataset.rangeBtn : 'from');
    });

    popup.addEventListener('click', (e) => {
        const nav = e.target.closest('[data-dr-nav]');
        if (nav) {
            geser(Number(nav.dataset.drNav));
            return;
        }

        const cell = e.target.closest('[data-ts]');
        if (cell) {
            draft = aturPilihan(draft, Number(cell.dataset.ts));
            hover = null;
            paint();
            return;
        }

        if (e.target.closest('[data-dr-reset]')) {
            draft = { from: null, to: null, target: 'from' };
            hover = null;
            paint();
            return;
        }

        if (e.target.closest('[data-dr-apply]')) {
            simpanNilai();
            tutup(true);
        }
    });

    // Pratinjau rentang saat memilih tanggal akhir
    popup.addEventListener('mouseover', (e) => {
        if (draft.from === null || draft.to !== null) return;
        const cell = e.target.closest('[data-ts]');
        const ts = cell ? Number(cell.dataset.ts) : null;
        if (ts === hover) return;
        hover = ts;
        paint();
    });
    popup.addEventListener('mouseleave', () => {
        if (hover === null) return;
        hover = null;
        paint();
    });

    // Klik di luar = batal. composedPath() dipakai karena sel bisa sudah dihapus dari DOM (render ulang)
    // sebelum event sampai ke document, sehingga root.contains(e.target) bisa salah.
    document.addEventListener('click', (e) => {
        if (!terbuka) return;
        const jalur = typeof e.composedPath === 'function' ? e.composedPath() : [];
        if (jalur.includes(root) || root.contains(e.target)) return;
        tutup();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && terbuka) tutup(true);
    });

    tulisField();
}

function initSemua() {
    document.querySelectorAll('[data-date-range]').forEach(initDateRange);
}

if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initSemua);
    else initSemua();
}
