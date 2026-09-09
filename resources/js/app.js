import 'bootstrap';
import './login';
import './filter';

// Ganti ikon chevron (down <-> up) mengikuti status collapse Bootstrap.
// Ini murni urusan buka/tutup dropdown - gak ada logic "active" di sini,
// karena status active/tidaknya menu udah ditentukan dari server (Blade),
// berdasarkan route yang sedang aktif.
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (trigger) {
    var target = document.querySelector(trigger.getAttribute('href'));
    var icon = trigger.querySelector('.chevron');

    if (!target || !icon) return;

    target.addEventListener('show.bs.collapse', function () {
      icon.classList.remove('bi-chevron-down');
      icon.classList.add('bi-chevron-up');
      trigger.setAttribute('aria-expanded', 'true');
    });

    target.addEventListener('hide.bs.collapse', function () {
      icon.classList.remove('bi-chevron-up');
      icon.classList.add('bi-chevron-down');
      trigger.setAttribute('aria-expanded', 'false');
    });
  });
});