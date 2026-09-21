<x-offcanvas.detail id="offcanvas-karyawan-detail" size="lg">

    <x-slot:header>
        <div class="karyawan-detail__header-content">

            <div class="karyawan-detail__avatar" id="detail-avatar">
                -
            </div>

            <div class="karyawan-detail__summary">
                <div class="karyawan-detail__name-row">

                    <h4 id="detail-nama">-</h4>

                    <span class="karyawan-detail__status">
                        <span class="karyawan-detail__status-dot"></span>
                        Aktif
                    </span>

                </div>

                <div class="karyawan-detail__meta">

                    <span id="detail-nip">
                        NIP : -
                    </span>

                    <span class="karyawan-detail__meta-divider"></span>

                    <span id="detail-jabatan">
                        -
                    </span>

                </div>
            </div>

        </div>
    </x-slot:header>


    <x-slot:tabs>

        <ul
            class="karyawan-detail__tabs nav"
            role="tablist"
        >

            <li class="nav-item">

                <button
                    class="nav-link active"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-data-pribadi"
                    type="button"
                >
                    Data Pribadi
                </button>

            </li>

            <li class="nav-item">

                <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-penempatan"
                    type="button"
                >
                    Penempatan & Jabatan
                </button>

            </li>

            <li class="nav-item">

                <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-rekening"
                    type="button"
                >
                    Rekening & Legal
                </button>

            </li>

        </ul>

    </x-slot:tabs>


    <div class="tab-content">


        {{-- =========================================================
            TAB DATA PRIBADI
        ========================================================== --}}

        <div
            class="tab-pane fade show active"
            id="tab-data-pribadi"
            role="tabpanel"
        >

            <div class="karyawan-detail__section">

                <div class="karyawan-detail__section-heading">

                    <span class="karyawan-detail__section-title">
                        Informasi pribadi
                    </span>

                    <span class="karyawan-detail__section-description">
                        Informasi dasar karyawan
                    </span>

                </div>


                {{-- TANGGAL DATA DIBUAT --}}

                <div class="karyawan-detail__join-box">

                    <div>

                        <span class="karyawan-detail__eyebrow">
                            Data dibuat pada
                        </span>

                        <strong id="detail-data-dibuat">
                            -
                        </strong>

                    </div>

                </div>


                {{-- DATA PRIBADI --}}

                <div class="karyawan-detail__field-grid">


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Email
                        </span>

                        <span
                            class="value"
                            id="detail-email"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            No. Telepon
                        </span>

                        <span
                            class="value"
                            id="detail-no-tlp"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            NIK
                        </span>

                        <span
                            class="value value-number"
                            id="detail-nik"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Jenjang Pendidikan
                        </span>

                        <span
                            class="value"
                            id="detail-jenjang-pendidikan"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Status Pernikahan
                        </span>

                        <span
                            class="value"
                            id="detail-status-kawin"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Agama
                        </span>

                        <span
                            class="value"
                            id="detail-agama"
                        >
                            -
                        </span>

                    </div>


                </div>

            </div>

        </div>



        {{-- =========================================================
            TAB PENEMPATAN & JABATAN
        ========================================================== --}}

        <div
            class="tab-pane fade"
            id="tab-penempatan"
            role="tabpanel"
        >

            <div class="karyawan-detail__section">

                <div class="karyawan-detail__section-heading">

                    <span class="karyawan-detail__section-title">
                        Penempatan & jabatan
                    </span>

                    <span class="karyawan-detail__section-description">
                        Posisi dan struktur organisasi karyawan
                    </span>

                </div>


                {{-- PENEMPATAN --}}

                <div class="karyawan-detail__placement">

                    <span class="karyawan-detail__eyebrow">
                        Penempatan
                    </span>

                    <strong id="detail-penempatan">
                        -
                    </strong>

                </div>


                <div class="karyawan-detail__field-grid">


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Job Position · Level
                        </span>

                        <span
                            class="value"
                            id="detail-job-position-level"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Cabang Kantor
                        </span>

                        <span
                            class="value"
                            id="detail-cabang-kantor"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Status Kepegawaian
                        </span>

                        <span
                            class="value"
                            id="detail-status-kepegawaian-full"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Status Aktif
                        </span>

                        <span
                            class="karyawan-detail__status-inline"
                            id="detail-status-aktif"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            Gaji
                        </span>

                        <span
                            class="value"
                            id="detail-gaji"
                        >
                            -
                        </span>

                    </div>


                </div>

            </div>

        </div>



        {{-- =========================================================
            TAB REKENING & LEGAL
        ========================================================== --}}

        <div
            class="tab-pane fade"
            id="tab-rekening"
            role="tabpanel"
        >

            <div class="karyawan-detail__section">

                <div class="karyawan-detail__section-heading">

                    <span class="karyawan-detail__section-title">
                        Rekening & legal
                    </span>

                    <span class="karyawan-detail__section-description">
                        Informasi rekening dan dokumen administrasi
                    </span>

                </div>


                {{-- BANK --}}

                <div class="karyawan-detail__bank">

                    <div>

                        <span class="karyawan-detail__eyebrow">
                            Bank
                        </span>

                        <strong id="detail-bank">
                            -
                        </strong>

                    </div>


                    <div class="karyawan-detail__bank-account">

                        <span class="label">
                            Nomor rekening
                        </span>

                        <strong
                            class="value-number"
                            id="detail-no-rekening"
                        >
                            -
                        </strong>

                        <span
                            class="sub"
                            id="detail-nama-bank"
                        >
                            -
                        </span>

                    </div>

                </div>


                {{-- LEGAL --}}

                <div class="karyawan-detail__field-grid">


                    <div class="karyawan-detail__field">

                        <span class="label">
                            NPWP
                        </span>

                        <span
                            class="value value-number"
                            id="detail-no-npwp"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            BPJS Ketenagakerjaan
                        </span>

                        <span
                            class="value value-number"
                            id="detail-no-bpjs-tk"
                        >
                            -
                        </span>

                    </div>


                    <div class="karyawan-detail__field">

                        <span class="label">
                            BPJS Kesehatan
                        </span>

                        <span
                            class="value value-number"
                            id="detail-no-bpjs-kes"
                        >
                            -
                        </span>

                    </div>


                </div>

            </div>

        </div>


    </div>

</x-offcanvas.detail>



<script>

(function () {

    const offcanvasEl =
        document.getElementById(
            'offcanvas-karyawan-detail'
        );

    if (!offcanvasEl) return;


    /* =========================================================
       LOAD DETAIL
    ========================================================== */

    offcanvasEl.addEventListener(
        'show.bs.offcanvas',
        function (event) {

            const trigger =
                event.relatedTarget;

            if (!trigger) return;


            const url =
                trigger.getAttribute(
                    'data-detail-url'
                );

            if (!url) return;


            fetch(url)

                .then(res => {

                    if (!res.ok) {
                        throw new Error(
                            'Gagal mengambil data karyawan.'
                        );
                    }

                    return res.json();

                })

                .then(data => {


                    /* ================================
                       HEADER
                    ================================= */

                    document.getElementById(
                        'detail-avatar'
                    ).textContent =
                        data.inisial;


                    document.getElementById(
                        'detail-nama'
                    ).textContent =
                        data.nama;


                    document.getElementById(
                        'detail-nip'
                    ).textContent =
                        'NIP : ' + data.nip;


                    document.getElementById(
                        'detail-jabatan'
                    ).textContent =
                        data.job_position +
                        ' · ' +
                        data.status_kepegawaian;



                    /* ================================
                       DATA PRIBADI
                    ================================= */

                    document.getElementById(
                        'detail-data-dibuat'
                    ).textContent =
                        data.data_dibuat;


                    document.getElementById(
                        'detail-email'
                    ).textContent =
                        data.email;


                    document.getElementById(
                        'detail-no-tlp'
                    ).textContent =
                        data.no_tlp;


                    document.getElementById(
                        'detail-nik'
                    ).textContent =
                        data.nik;


                    document.getElementById(
                        'detail-jenjang-pendidikan'
                    ).textContent =
                        data.jenjang_pendidikan;


                    document.getElementById(
                        'detail-status-kawin'
                    ).textContent =
                        data.status_kawin;


                    document.getElementById(
                        'detail-agama'
                    ).textContent =
                        data.agama;



                    /* ================================
                       PENEMPATAN
                    ================================= */

                    document.getElementById(
                        'detail-penempatan'
                    ).textContent =
                        data.penempatan_breadcrumb;


                    document.getElementById(
                        'detail-job-position-level'
                    ).textContent =
                        data.job_position_level;


                    document.getElementById(
                        'detail-cabang-kantor'
                    ).textContent =
                        data.cabang_kantor;


                    document.getElementById(
                        'detail-status-kepegawaian-full'
                    ).textContent =
                        data.status_kepegawaian_full;


                    document.getElementById(
                        'detail-status-aktif'
                    ).textContent =
                        data.status_aktif;


                    document.getElementById(
                        'detail-gaji'
                    ).textContent =
                        data.gaji;



                    /* ================================
                       REKENING & LEGAL
                    ================================= */

                    document.getElementById(
                        'detail-bank'
                    ).textContent =
                        data.bank;


                    document.getElementById(
                        'detail-no-rekening'
                    ).textContent =
                        data.no_rekening;


                    document.getElementById(
                        'detail-nama-bank'
                    ).textContent =
                        data.nama_bank !== '-'
                            ? 'a.n. ' + data.nama_bank
                            : '-';


                    document.getElementById(
                        'detail-no-npwp'
                    ).textContent =
                        data.no_npwp;


                    document.getElementById(
                        'detail-no-bpjs-tk'
                    ).textContent =
                        data.no_bpjs_ketenagakerjaan;


                    document.getElementById(
                        'detail-no-bpjs-kes'
                    ).textContent =
                        data.no_bpjs_kesehatan;



                    /* ================================
                       STATUS HEADER
                    ================================= */

                    const status =
                        data.status_aktif;


                    const statusElement =
                        document.querySelector(
                            '.karyawan-detail__status'
                        );


                    if (statusElement) {

                        statusElement.innerHTML = `
                            <span class="karyawan-detail__status-dot"></span>
                            ${status}
                        `;


                        statusElement.classList.toggle(
                            'is-inactive',
                            status !== 'Aktif'
                        );

                    }



                    /* ================================
                       STATUS INLINE
                    ================================= */

                    const inlineStatus =
                        document.getElementById(
                            'detail-status-aktif'
                        );


                    if (inlineStatus) {

                        inlineStatus.classList.toggle(
                            'is-inactive',
                            status !== 'Aktif'
                        );

                    }

                })

                .catch(err => {

                    console.error(
                        'Gagal ambil detail karyawan:',
                        err
                    );

                });

        }
    );



    /* =========================================================
       RESET TAB SAAT OFFCANVAS DITUTUP
    ========================================================== */

    offcanvasEl.addEventListener(
        'hidden.bs.offcanvas',
        function () {

            const firstTabTrigger =
                offcanvasEl.querySelector(
                    '.karyawan-detail__tabs .nav-link'
                );


            const firstTabPane =
                offcanvasEl.querySelector(
                    '.tab-pane'
                );


            offcanvasEl
                .querySelectorAll(
                    '.karyawan-detail__tabs .nav-link'
                )
                .forEach(el =>
                    el.classList.remove(
                        'active'
                    )
                );


            offcanvasEl
                .querySelectorAll(
                    '.tab-pane'
                )
                .forEach(el =>
                    el.classList.remove(
                        'show',
                        'active'
                    )
                );


            firstTabTrigger?.classList.add(
                'active'
            );


            firstTabPane?.classList.add(
                'show',
                'active'
            );

        }
    );


})();

</script>