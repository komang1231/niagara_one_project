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
        dropdown.querySelectorAll('li').forEach(li => {
            li.classList.toggle('is-active', li.dataset.value === input.value);
        });
        const active = dropdown.querySelector('li.is-active');
        if (active) active.scrollIntoView({ block: 'center' });
    }

    function tutupDropdown() {
        wrapper.classList.remove('is-open');
    }

    input.addEventListener('focus', bukaDropdown);
    input.addEventListener('click', bukaDropdown);

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) tutupDropdown();
    });

    input.addEventListener('blur', function () {
        const valid = /^([01]\d|2[0-3]):[0-5]\d$/.test(input.value);
        if (!valid) input.value = '';
    });
}