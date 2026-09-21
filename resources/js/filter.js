document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-multiselect]').forEach(initMultiselect);
    document.querySelectorAll('[data-filter-form]').forEach(initFilterForm);
});

/* ============================================================
   BAGIAN 1: dropdown multiselect
   Centang/uncentang/hapus chip CUMA ubah tampilan doang.
   Gak ada submit di sini sama sekali — nunggu tombol Search diklik.
   ============================================================ */
function initMultiselect(root) {
    const toggle = root.querySelector('[data-multiselect-toggle]');
    const chipsWrap = root.querySelector('[data-multiselect-chips]');
    const placeholder = root.querySelector('[data-multiselect-placeholder]');
    const inputs = root.querySelectorAll('[data-multiselect-input]');

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
                <button type="button" class="app-chip__remove" aria-label="Hapus ${input.dataset.label}">×</button>
            `;

            chip.querySelector('.app-chip__remove').addEventListener('click', (e) => {
                e.stopPropagation(); // biar dropdown gak ke-toggle pas klik x
                input.checked = false;
                renderChips();
                // sengaja gak submit di sini, nunggu tombol Search
            });

            chipsWrap.appendChild(chip);
        });
    }

    inputs.forEach((input) => input.addEventListener('change', renderChips));

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

    renderChips(); // render state awal (default: semua kecentang)
}

/* ============================================================
   BAGIAN 2: submit form — CUMA jalan pas tombol Search diklik
   (atau tekan Enter di kolom search, itu perilaku bawaan form HTML).
   ============================================================ */
function initFilterForm(form) {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        submitFilterForm(form);
    });
}

function submitFilterForm(form) {
    const targetSelector = form.dataset.filterAjaxTarget;

    if (!targetSelector) {
        form.submit();
        return;
    }

    const target = document.querySelector(targetSelector);
    const params = new URLSearchParams(new FormData(form));
    const url = `${form.action}?${params.toString()}`;

    // Class dipisah: overlay+spinner cuma nempel ke tabel (target),
    // filter bar cuma dim tombol Search-nya (gak ada overlay di sini).
    if (target) target.classList.add('is-table-loading');
    form.classList.add('is-submitting');

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then((res) => {
            if (!res.ok) throw new Error('Request gagal');
            return res.text();
        })
        .then((html) => {
            if (target) target.innerHTML = html;
            window.history.pushState({}, '', url);
        })
        .catch(() => {
            form.submit();
        })
        .finally(() => {
            if (target) target.classList.remove('is-table-loading');
            form.classList.remove('is-submitting');
        });
}