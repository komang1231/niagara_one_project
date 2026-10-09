document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-time-picker]').forEach(setupTimePicker);
});

function setupTimePicker(wrapper) {
    const input = wrapper.querySelector('[data-time-input]');
    const dropdown = wrapper.querySelector('[data-time-dropdown]');
    const step = parseInt(wrapper.dataset.step || '10', 10);

    for (let menit = 0; menit < 24 * 60; menit += step) {
        const jam = Math.floor(menit / 60);
        const mnt = menit % 60;
        const value = `${String(jam).padStart(2, '0')}:${String(mnt).padStart(2, '0')}`;

        const li = document.createElement('li');
        li.textContent = value;
        li.dataset.value = value;
        li.addEventListener('click', () => pilihWaktu(value));
        dropdown.appendChild(li);
    }

    function pilihWaktu(waktu) {
        input.value = waktu;
        input.dispatchEvent(new Event('change', { bubbles: true }));
        tutupDropdown();
    }

    function bukaDropdown() {
        wrapper.classList.add('is-open');
        const diketik = input.value;
        let cocok = null;
        dropdown.querySelectorAll('li').forEach(li => {
            li.classList.remove('is-active');
            if (!cocok && diketik && li.dataset.value.startsWith(diketik)) cocok = li;
        });
        if (cocok) cocok.classList.add('is-active');
        const active = dropdown.querySelector('li.is-active');
        if (active) active.scrollIntoView({ block: 'center' });
    }

    function tutupDropdown() {
        wrapper.classList.remove('is-open');
    }

    /**
     * Rapikan isian jam saat user mengetik, supaya selalu berbentuk HH:MM.
     * - hanya angka yang diterima, titik dua ditambah otomatis
     * - angka pertama jam 3-9 diberi awalan 0 ("9" -> "09")
     * - jam maksimal 23, menit maksimal 59 ("075" -> "07:05", "2" lalu "7" -> "23")
     */
    function formatKetik(raw, sedangHapus) {
        let d = String(raw).replace(/\D/g, '');

        if (d.length >= 1 && d[0] > '2') d = '0' + d;                       // 9   -> 09
        if (d.length >= 2 && d[0] === '2' && d[1] > '3') d = '23' + d.slice(2); // 27 -> 23
        if (d.length >= 3 && d[2] > '5') d = d.slice(0, 2) + '0' + d.slice(2);  // 097 -> 0907
        d = d.slice(0, 4);

        if (d.length < 2) return d;
        if (d.length === 2) return sedangHapus ? d : d + ':';
        return d.slice(0, 2) + ':' + d.slice(2);
    }

    // Lengkapi isian yang belum utuh ("9" -> "09:00", "9:5" -> "09:05", "930" -> "09:30").
    // Return string kosong kalau isian tidak bisa dijadikan jam yang valid.
    function lengkapiWaktu(raw) {
        const teks = String(raw).trim();
        if (!teks) return '';

        let jam, menit;
        if (teks.includes(':')) {
            const [j, m = ''] = teks.split(':');
            jam = j.replace(/\D/g, '');
            menit = m.replace(/\D/g, '');
        } else {
            const d = teks.replace(/\D/g, '');
            if (d.length <= 2) { jam = d; menit = ''; }
            else if (d.length === 3) { jam = d.slice(0, 1); menit = d.slice(1); }
            else { jam = d.slice(0, 2); menit = d.slice(2, 4); }
        }

        if (jam === '' || jam.length > 2 || menit.length > 2) return '';
        const j = parseInt(jam, 10);
        const m = menit === '' ? 0 : parseInt(menit, 10);
        if (j > 23 || m > 59) return '';

        return `${String(j).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }

    function rapikan() {
        const sebelum = input.value;
        const hasil = lengkapiWaktu(sebelum);
        input.value = hasil;
        if (hasil !== sebelum) input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    input.addEventListener('focus', bukaDropdown);
    input.addEventListener('click', bukaDropdown);

    input.addEventListener('input', function (e) {
        const sedangHapus = (e.inputType || '').startsWith('delete');
        const diAkhir = input.selectionStart === input.value.length;
        const baru = formatKetik(input.value, sedangHapus);

        if (baru !== input.value) {
            input.value = baru;
            if (diAkhir) input.setSelectionRange(baru.length, baru.length);
        }

        // sorot & gulung ke pilihan yang cocok selagi mengetik
        bukaDropdown();
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === 'Tab') {
            rapikan();
            tutupDropdown();
        }
    });

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) tutupDropdown();
    });

    input.addEventListener('blur', function () {
        rapikan();
    });
}