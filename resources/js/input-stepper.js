// Stepper untuk input angka (jumlah hari cuti, dsb)

document.querySelectorAll('.input-stepper').forEach((wrapper) => {
    const input = wrapper.querySelector('.input-stepper-field');

    wrapper.querySelectorAll('.stepper-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const stepValue = parseInt(btn.dataset.step, 10);
            const min = input.min !== '' ? parseInt(input.min, 10) : -Infinity;
            const max = input.max !== '' ? parseInt(input.max, 10) : Infinity;
            const current = parseInt(input.value, 10) || 0;

            // batasin biar gak lewat min/max
            const next = Math.min(max, Math.max(min, current + stepValue));
            input.value = next;

            // trigger event biar validasi/listener lain yang nempel di input tetep jalan
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });
    });

    // cegah angka berubah gak sengaja pas user scroll halaman sambil fokus di input
    input.addEventListener('wheel', (e) => {
        if (document.activeElement === input) e.preventDefault();
    }, { passive: false });
});