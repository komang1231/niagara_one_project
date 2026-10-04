/**
 * Attendance - Check-in / Check-out.
 *
 * Form (resources/views/attendance/partials/actions.blade.php) dikirim ke
 * route attendance.check-in / attendance.check-out. Backend mewajibkan latitude & longitude,
 * jadi sebelum submit kita ambil lokasi dari browser lalu isi hidden input.
 * Setelah itu halaman reload dan flash success/error ditampilkan popup global.
 *
 * Catatan: geolocation hanya jalan di HTTPS atau localhost.
 */

const GEO_MESSAGES = {
    1: 'Izin lokasi ditolak. Aktifkan izin lokasi untuk situs ini di browser, lalu coba lagi.',
    2: 'Lokasi perangkat tidak tersedia. Pastikan GPS/lokasi aktif, lalu coba lagi.',
    3: 'Pencarian lokasi terlalu lama. Coba lagi.',
};

const pad = (n) => String(n).padStart(2, '0');

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-server-clock]').forEach(initServerClock);
    document.querySelectorAll('[data-punch-form]').forEach(initPunchForm);
});

/* Jam berjalan mengikuti waktu SERVER (zona waktu aplikasi), bukan jam komputer user. */
function initServerClock(el) {
    const serverMs = Number(el.dataset.ts) * 1000;
    const offsetMs = Number(el.dataset.offset) * 60000;
    const loadedAt = Date.now();

    const tick = () => {
        const d = new Date(serverMs + (Date.now() - loadedAt) + offsetMs);
        el.textContent = `${pad(d.getUTCHours())}:${pad(d.getUTCMinutes())}:${pad(d.getUTCSeconds())}`;
    };

    tick();
    setInterval(tick, 1000);
}

function getPosition() {
    return new Promise((resolve, reject) => {
        if (!('geolocation' in navigator)) {
            reject({ code: 0, message: 'Browser ini tidak mendukung lokasi.' });
            return;
        }

        navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0,
        });
    });
}

const swalButtons = () => ({ buttonsStyling: false });

function showError(text) {
    return window.Swal.fire({
        ...swalButtons(),
        title: 'Lokasi tidak ditemukan',
        text,
        icon: 'error',
        confirmButtonText: 'OK',
        customClass: { confirmButton: 'btn btn-success popup-ok' },
    });
}

function setBusy(form, busy) {
    const btn = form.querySelector('button[type="submit"]');
    const icon = btn.querySelector('i');
    const label = btn.querySelector('span');

    if (busy) {
        btn.dataset.originalIcon = icon.className;
        btn.dataset.originalLabel = label.textContent;
        icon.className = 'bi bi-arrow-repeat attendance-spin';
        label.textContent = 'Mencari lokasi...';
        btn.disabled = true;
    } else {
        icon.className = btn.dataset.originalIcon;
        label.textContent = btn.dataset.originalLabel;
        btn.disabled = false;
    }
}

function initPunchForm(form) {
    form.addEventListener('submit', async (event) => {
        // form.submit() di bawah tidak memicu event ini lagi, jadi tidak ada loop.
        event.preventDefault();

        if (form.dataset.busy === '1') return;

        const btn = form.querySelector('button[type="submit"]');
        if (btn.disabled) return;

        // Konfirmasi hanya muncul saat ada peringatan (mis. check-out sebelum jam pulang shift).
        if (form.dataset.confirm) {
            const result = await window.Swal.fire({
                ...swalButtons(),
                title: 'Check-out sekarang?',
                text: form.dataset.confirm,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, check-out',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success popup-confirm',
                    cancelButton: 'btn btn-danger popup-cancel',
                },
            });

            if (!result.isConfirmed) return;
        }

        form.dataset.busy = '1';
        setBusy(form, true);

        try {
            const pos = await getPosition();

            form.elements.latitude.value = pos.coords.latitude.toFixed(8);
            form.elements.longitude.value = pos.coords.longitude.toFixed(8);

            // Kunci semua tombol absensi supaya tidak ada request ganda selama halaman berpindah.
            document.querySelectorAll('[data-punch-form] button[type="submit"]').forEach((b) => (b.disabled = true));

            form.submit();
        } catch (err) {
            form.dataset.busy = '0';
            setBusy(form, false);
            await showError(GEO_MESSAGES[err.code] || err.message || 'Lokasi tidak dapat dibaca. Coba lagi.');
        }
    });
}
