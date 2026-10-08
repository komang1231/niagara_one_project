<script>
document.addEventListener('DOMContentLoaded', function () {

    const swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    document.addEventListener('submit', function (event) {

        const form = event.target;

        if (!form.matches('form')) return;

        const methodInput = form.querySelector('input[name="_method"]');

        // Kalau form tidak menggunakan method spoofing, abaikan
        if (!methodInput) return;

        const method = methodInput.value.toUpperCase();
        const action = form.getAttribute('action') || '';

        let popupType = null;


        // ==========================================
        // HAPUS PERMANEN
        // ==========================================
        if (method === 'DELETE' && action.includes('/force-delete')) {

            popupType = 'force-delete';

        }


        // ==========================================
        // HAPUS BIASA
        // ==========================================
        else if (method === 'DELETE') {

            popupType = 'delete';

        }


        // ==========================================
        // RESTORE
        // ==========================================
        else if (method === 'PATCH' && action.includes('/restore')) {

            popupType = 'restore';

        }


        // Kalau bukan delete, restore, atau force delete
        if (!popupType) return;

        event.preventDefault();


        let popupConfig = {};


        // ==========================================
        // POPUP HAPUS BIASA
        // ==========================================
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


        // ==========================================
        // POPUP RESTORE
        // ==========================================
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


        // ==========================================
        // POPUP HAPUS PERMANEN
        // ==========================================
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


        // ==========================================
        // TAMPILKAN POPUP KONFIRMASI
        // ==========================================
        swalWithBootstrapButtons.fire({

            ...popupConfig,

            customClass: {
                confirmButton: "btn btn-success popup-confirm",
                cancelButton: "btn btn-danger popup-cancel"
            }

        }).then((result) => {


            // ==========================================
            // JIKA DIKONFIRMASI
            // ==========================================
            if (result.isConfirmed) {

                form.submit();

            }


            // ==========================================
            // JIKA DIBATALKAN
            // ==========================================
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
       TOMBOL KONFIRMASI
       ========================================== */

    .popup-confirm {
        margin-left: 8px !important;
    }


    /* ==========================================
       TOMBOL BATAL
       ========================================== */

    .popup-cancel {
        margin-right: 8px !important;
    }


    /* ==========================================
       TOMBOL OK
       ========================================== */

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

</style>
