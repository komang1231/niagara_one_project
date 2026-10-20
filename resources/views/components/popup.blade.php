```blade
<script>
document.addEventListener('DOMContentLoaded', function () {

    const swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    // ==========================================
    // KONFIRMASI HAPUS, RESTORE, DAN LOGOUT
    // ==========================================
    document.addEventListener('submit', function (event) {

        const form = event.target;

        if (!form.matches('form')) return;

        // Abaikan form logout karena ditangani terpisah
        if (form.id === 'topbar-logout-form') return;

        const methodInput = form.querySelector('input[name="_method"]');

        // Hanya proses form yang memakai method spoofing
        if (!methodInput) return;

        const method = methodInput.value.toUpperCase();
        const action = form.getAttribute('action') || '';

        let popupType = null;

        // Hapus permanen
        if (method === 'DELETE' && action.includes('/force-delete')) {
            popupType = 'force-delete';
        }

        // Hapus biasa
        else if (method === 'DELETE') {
            popupType = 'delete';
        }

        // Restore
        else if (method === 'PATCH' && action.includes('/restore')) {
            popupType = 'restore';
        }

        if (!popupType) return;

        event.preventDefault();

        let popupConfig = {};

        if (popupType === 'delete') {
            popupConfig = {
                title: "Apakah Anda yakin?",
                text: "Data akan dipindahkan ke Trash.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus data!",
                cancelButtonText: "Batal",
                reverseButtons: true
            };
        }

        else if (popupType === 'restore') {
            popupConfig = {
                title: "Pulihkan data?",
                text: "Data akan dikembalikan.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, pulihkan!",
                cancelButtonText: "Batal",
                reverseButtons: true
            };
        }

        else if (popupType === 'force-delete') {
            popupConfig = {
                title: "Hapus permanen?",
                text: "Data yang dihapus permanen tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus permanen!",
                cancelButtonText: "Batal",
                reverseButtons: true
            };
        }

        swalWithBootstrapButtons.fire({
            ...popupConfig,
            customClass: {
                confirmButton: "btn btn-success popup-confirm",
                cancelButton: "btn btn-danger popup-cancel"
            }
        }).then((result) => {

            if (result.isConfirmed) {
                // Kirim form asli tanpa memicu popup berulang
                HTMLFormElement.prototype.submit.call(form);
            }

            else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Dibatalkan",
                    text: "Tidak ada perubahan yang dilakukan.",
                    icon: "error",
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-danger popup-ok-danger"
                    }
                });
            }

        });

    });


    // ==========================================
    // KONFIRMASI LOGOUT TOPBAR
    // ==========================================
    document.addEventListener('click', function (event) {

        const logoutButton = event.target.closest(
            '[data-confirm-logout]'
        );

        if (!logoutButton) return;

        event.preventDefault();

        const logoutForm = document.getElementById('topbar-logout-form');

        if (!logoutForm) return;

        swalWithBootstrapButtons.fire({
            title: '',
            html: `
                <div class="logout-popup">
                    <div class="logout-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>

                    <h2>Logout</h2>

                    <p>
                        Anda harus login kembali untuk mengakses
                        akun ini setelah keluar.
                    </p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'BATAL',
            reverseButtons: false,
            focusCancel: true,
            customClass: {
                popup: 'logout-swal-popup',
                htmlContainer: 'logout-swal-content',
                actions: 'logout-swal-actions',
                confirmButton: 'logout-confirm-button',
                cancelButton: 'logout-cancel-button'
            }
        }).then((result) => {

            if (result.isConfirmed) {
                HTMLFormElement.prototype.submit.call(logoutForm);
            }

        });

    });


    // ==========================================
    // SUCCESS
    // ==========================================
    @if (session('success'))
        swalWithBootstrapButtons.fire({
            title: "Berhasil!",
            text: @json(session('success')),
            icon: "success",
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-success popup-ok"
            }
        });
    @endif


    // ==========================================
    // ERROR
    // ==========================================
    @if (session('error'))
        swalWithBootstrapButtons.fire({
            title: "Terjadi Kesalahan",
            text: @json(session('error')),
            icon: "error",
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-success popup-ok"
            }
        });
    @endif

});
</script>


<style>
/* ==========================================
   TOMBOL POPUP LAMA
   ========================================== */

.popup-confirm {
    margin-left: 8px !important;
}

.popup-cancel {
    margin-right: 8px !important;
}

.popup-ok,
.popup-ok:focus,
.popup-ok:focus-visible,
.popup-ok:hover,
.popup-ok:active {
    margin-top: 8px !important;
    padding: 8px 24px !important;
    min-width: 72px !important;
    height: 42px !important;
    border-radius: 6px !important;
    outline: none !important;
    box-shadow: none !important;
}


/* ==========================================
   POPUP LOGOUT
   ========================================== */

.logout-swal-popup {
    width: 420px !important;
    max-width: calc(100% - 24px) !important;
    padding: 28px 24px 20px !important;
    border-radius: 20px !important;
}

.logout-swal-content {
    margin: 0 !important;
}

.logout-popup .logout-icon {
    width: 92px;
    height: 92px;
    border: 4px solid #ed4852;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: #d71920;
    font-size: 48px;
}

.logout-popup h2 {
    font-size: 28px;
    font-weight: 600;
    color: #111;
    margin: 0 0 8px;
}

.logout-popup p {
    font-size: 17px;
    line-height: 1.35;
    color: #a3a3a3;
    margin: 0 0 20px;
}

.logout-swal-actions {
    display: flex !important;
    flex-direction: row-reverse !important;
    flex-wrap: nowrap !important;
    gap: 24px !important;
    width: 100%;
    margin: 0 !important;
}

.logout-confirm-button,
.logout-cancel-button {
    flex: 1;
    min-width: 0;
    height: 43px;
    border: none !important;
    border-radius: 8px !important;
    font-size: 16px !important;
    font-weight: 500 !important;
    cursor: pointer;
}

.logout-confirm-button {
    background: #ed4852 !important;
    color: #fff !important;
}

.logout-confirm-button:hover {
    background: #d93640 !important;
}

.logout-cancel-button {
    background: #d0d0d0 !important;
    color: #fff !important;
}

.logout-cancel-button:hover {
    background: #bdbdbd !important;
}
</style>
```