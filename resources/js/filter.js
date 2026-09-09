document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-multiselect]').forEach(initMultiselect);
    document.querySelectorAll('[data-filter-form]').forEach(initFilterForm);
});

/* ============================================================
   BAGIAN 1: dropdown multiselect (buka/tutup dropdown + render chip)
   ============================================================ */
function initMultiselect(root) {
    const toggle = root.querySelector('[data-multiselect-toggle]');
    const chipsWrap = root.querySelector('[data-multiselect-chips]');
    const placeholder = root.querySelector('[data-multiselect-placeholder]');
    const inputs = root.querySelectorAll('[data-multiselect-input]');
    const form = root.closest('form');

    function renderChips() {
        const checked = Array.from(inputs).filter((i) => i.checked);
        chipsWrap.innerHTML = '';

        if (checked.length === 0) {
            chipsWrap.appendChild(placeholder);
            return;
        }

        checked.forEach((input) => {
            const chip = document.createElement('span');
            chip.className = 'app-chip';
            chip.innerHTML = `
                <span class="app-chip__label">${input.dataset.label}</span>
                <button type="button" class="app-chip__remove" aria-label="Hapus ${input.dataset.label}">
                    <i class="bi bi-x"></i>
                </button>
            `;

            chip.querySelector('.app-chip__remove').addEventListener('click', (e) => {
                e.stopPropagation(); // biar dropdown gak ke-toggle pas klik x
                input.checked = false;
                renderChips();
                if (form) submitFilterForm(form); // sesuai request: hapus chip -> langsung update data
            });

            chipsWrap.appendChild(chip);
        });
    }

    inputs.forEach((input) => input.addEventListener('change', () => {
        renderChips();
        if (form) submitFilterForm(form); // sesuai request: centang/uncentang -> langsung update data
    }));

    toggle.addEventListener('click', () => {
        const isOpen = root.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen);
    });

    document.addEventListener('click', (e) => {
        if (!root.contains(e.target)) {
            root.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    renderChips(); // render pertama kali dari state awal (default: semua kecentang)
}

/* ============================================================
   BAGIAN 2: submit form filter — AJAX kalau ada ajax-target,
   fallback submit biasa (reload) kalau enggak ada.
   Ini generic, jadi cuma perlu ditulis SEKALI, dipake di semua halaman
   yang pake komponen <x-filter.bar>.
   ============================================================ */
function initFilterForm(form) {
    // tombol "Search" -> submit manual (buat trigger kolom text)
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        submitFilterForm(form);
    });

    // enter di kolom text search -> submit juga
    form.querySelectorAll('[data-filter-search-input]').forEach((input) => {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitFilterForm(form);
            }
        });
    });
}

function submitFilterForm(form) {
    const targetSelector = form.dataset.filterAjaxTarget;

    // kalau prop ajax-target gak di-set di <x-filter.bar>, jatuhin ke
    // submit form biasa (reload full page) — tetep jalan tanpa AJAX.
    if (!targetSelector) {
        form.submit();
        return;
    }

    const target = document.querySelector(targetSelector);
    const params = new URLSearchParams(new FormData(form));
    const url = `${form.action}?${params.toString()}`;

    if (target) target.classList.add('is-loading');
    form.classList.add('is-loading');

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then((res) => {
            if (!res.ok) throw new Error('Request gagal');
            return res.text();
        })
        .then((html) => {
            if (target) target.innerHTML = html;
            // biar URL ikut kesinkron, refresh manual & tombol back browser tetep bener
            window.history.pushState({}, '', url);
        })
        .catch(() => {
            // fallback kalau fetch gagal (network/server error): reload biasa aja
            form.submit();
        })
        .finally(() => {
            if (target) target.classList.remove('is-loading');
            form.classList.remove('is-loading');
        });
}