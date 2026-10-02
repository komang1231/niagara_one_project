/**
 * Jadwal Karyawan — garis merah (timeline cursor) yang bisa digeser.
 *
 * Sumbu waktu = seluruh kolom hari (7 / 14 hari) dibagi rata, tiap hari 24 jam.
 * Jadi posisi garis bisa dibaca sebagai "hari + jam" — label di atas garis
 * menampilkan jamnya, dan shift yang sedang berlangsung di jam itu di-highlight.
 *
 * Kontrol: drag mouse/touch, panah kiri/kanan (±15 menit), PageUp/PageDown (±1 hari),
 * Home/End, atau klik di header tanggal untuk lompat.
 */

const HARI = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const STEP = 5; // menit — hasil geser dibulatkan ke kelipatan ini
const MENIT_SEHARI = 1440;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-jadwal-grid]').forEach(initJadwalTimeline);
});

const pad = (n) => String(n).padStart(2, '0');
const clamp = (v, min, max) => Math.min(Math.max(v, min), max);

// 'YYYY-MM-DD' -> Date lokal (jangan pakai new Date(str): itu dibaca UTC & bisa geser sehari)
function parseDate(iso) {
    const [y, m, d] = iso.split('-').map(Number);
    return new Date(y, m - 1, d);
}

// 'YYYY-MM-DDTHH:mm' -> Date lokal
function parseDateTime(value) {
    const [date, time] = value.split('T');
    const [y, m, d] = date.split('-').map(Number);
    const [h, i] = time.split(':').map(Number);
    return new Date(y, m - 1, d, h, i);
}

function initJadwalTimeline(root) {
    const scroller = root.closest('[data-jadwal-scroller]');
    const overlay = root.querySelector('[data-timeline]');
    const cursor = root.querySelector('[data-timeline-cursor]');
    const label = root.querySelector('[data-timeline-label]');
    const summary = document.querySelector('[data-timeline-summary]');
    const dayHeads = Array.from(root.querySelectorAll('[data-day-head]'));

    if (!overlay || !cursor || !label) return;

    const days = Number(root.dataset.days);
    const start = parseDate(root.dataset.start);
    const total = days * MENIT_SEHARI;

    // Rentang waktu tiap chip (shift lintas hari: pulang = hari berikutnya)
    const chips = Array.from(root.querySelectorAll('.jadwal-chip')).map((el) => {
        const date = parseDate(el.dataset.date);
        const [mh, mm] = el.dataset.masuk.split(':').map(Number);
        const [ph, pm] = el.dataset.pulang.split(':').map(Number);

        const from = new Date(date);
        from.setHours(mh, mm, 0, 0);

        const to = new Date(date);
        to.setHours(ph, pm, 0, 0);
        if (el.dataset.lintas === '1' || to <= from) to.setDate(to.getDate() + 1);

        return { el, karyawan: el.dataset.karyawan, from: from.getTime(), to: to.getTime() };
    });

    let minutes = 0;

    function setMinutes(value) {
        minutes = clamp(Math.round(value / STEP) * STEP, 0, total - STEP);

        // Waktu wall-clock lokal: new Date(y, m, d, 0, menit) otomatis meluap ke hari berikutnya
        const at = new Date(start.getFullYear(), start.getMonth(), start.getDate(), 0, minutes);
        const jam = `${pad(at.getHours())}:${pad(at.getMinutes())}`;
        const ts = at.getTime();

        cursor.style.left = `${(minutes / total) * 100}%`;
        label.textContent = jam;
        cursor.setAttribute('aria-valuenow', String(minutes));
        cursor.setAttribute('aria-valuetext', `${HARI[at.getDay()]}, ${at.getDate()} ${BULAN[at.getMonth()]} pukul ${jam}`);

        // Highlight kolom hari yang sedang dilewati garis
        const dayIndex = Math.floor(minutes / MENIT_SEHARI);
        dayHeads.forEach((el, i) => el.classList.toggle('is-current', i === dayIndex));

        // Highlight shift yang sedang berlangsung di jam ini
        const aktif = new Set();
        chips.forEach((c) => {
            const on = ts >= c.from && ts < c.to;
            c.el.classList.toggle('is-on', on);
            if (on) aktif.add(c.karyawan);
        });

        if (summary) {
            summary.innerHTML =
                `<span><strong>${HARI[at.getDay()]}, ${at.getDate()} ${BULAN[at.getMonth()]} · ${jam}</strong></span>` +
                `<span>${aktif.size} karyawan sedang bertugas</span>`;
        }
    }

    function moveToClientX(clientX) {
        const rect = overlay.getBoundingClientRect();
        setMinutes(((clientX - rect.left) / rect.width) * total);
    }

    // Kalau di-drag mentok tepi (mode 2 minggu bisa di-scroll), geser scroller otomatis
    function autoScroll(clientX) {
        if (!scroller) return;
        const rect = scroller.getBoundingClientRect();
        const edge = 48;
        const frozen = overlay.offsetLeft; // lebar kolom karyawan yang sticky

        if (clientX > rect.right - edge) scroller.scrollLeft += 16;
        else if (clientX < rect.left + frozen + edge) scroller.scrollLeft -= 16;
    }

    // ---------- Drag (mouse + touch) ----------
    let dragging = false;

    cursor.addEventListener('pointerdown', (e) => {
        dragging = true;
        cursor.setPointerCapture(e.pointerId);
        cursor.classList.add('is-dragging');
        cursor.focus({ preventScroll: true });
        e.preventDefault();
    });

    cursor.addEventListener('pointermove', (e) => {
        if (!dragging) return;
        autoScroll(e.clientX);
        moveToClientX(e.clientX);
    });

    const stopDrag = (e) => {
        if (!dragging) return;
        dragging = false;
        cursor.classList.remove('is-dragging');
        if (cursor.hasPointerCapture(e.pointerId)) cursor.releasePointerCapture(e.pointerId);
    };
    cursor.addEventListener('pointerup', stopDrag);
    cursor.addEventListener('pointercancel', stopDrag);

    // ---------- Klik header tanggal = lompat ke posisi itu ----------
    dayHeads.forEach((el) => el.addEventListener('click', (e) => moveToClientX(e.clientX)));

    // ---------- Keyboard ----------
    cursor.addEventListener('keydown', (e) => {
        const keys = {
            ArrowLeft: () => setMinutes(minutes - 15),
            ArrowRight: () => setMinutes(minutes + 15),
            PageUp: () => setMinutes(minutes - MENIT_SEHARI),
            PageDown: () => setMinutes(minutes + MENIT_SEHARI),
            Home: () => setMinutes(0),
            End: () => setMinutes(total),
        };

        if (!keys[e.key]) return;
        e.preventDefault();
        keys[e.key]();
    });

    // ---------- Posisi awal (dari server) + pastikan kelihatan ----------
    setMinutes((parseDateTime(root.dataset.initial) - start) / 60000);

    if (scroller && scroller.scrollWidth > scroller.clientWidth) {
        const x = overlay.offsetLeft + (minutes / total) * overlay.offsetWidth;
        scroller.scrollLeft = Math.max(0, x - scroller.clientWidth / 2);
    }
}
