<x-offcanvas.form id="offcanvas-rekrutmen-diterima" title="Data Karyawan Baru"
    description="Lengkapi data berikut untuk membuat akun karyawan dari kandidat yang diterima." size="xl">
    <form id="offcanvas-rekrutmen-diterima-form" action="#" method="POST" novalidate>
        @csrf

        {{-- Penanda form mana yang disubmit (dipakai buat buka lagi offcanvas kalau validasi gagal) --}}
        <input type="hidden" name="_form" value="offcanvas-rekrutmen-diterima">

        {{-- ================= KEPEGAWAIAN ================= --}}
        <h6 class="mb-3">Kepegawaian</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="status_kepegawaian_id" id="diterima_status_kepegawaian_id"
                    label="Status Kepegawaian" :options="$statusKepegawaians->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                {{-- Input currency: yang dikirim ke backend angka mentah tanpa titik --}}
                <x-form.currency name="gaji" label="Gaji" required />
            </div>

            <div class="col-md-4">
                <x-form.select name="role_id" id="diterima_role_id" label="Role" :options="$roles->pluck('nama', 'id')" nullable
                    required />
            </div>

        </div>


        {{-- ================= DATA PRIBADI ================= --}}
        <h6 class="mb-3 mt-4">Data Pribadi</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="nik" id="diterima_nik" label="NIK" inputmode="numeric"
                    placeholder="16 digit angka" required />
            </div>

            <div class="col-md-4">
                <x-form.select name="status_kawin_id" id="diterima_status_kawin_id" label="Status Kawin"
                    :options="$statusKawins->pluck('nama', 'id')" nullable required />
            </div>

            <div class="col-md-4">
                <x-form.select name="agama_id" id="diterima_agama_id" label="Agama" :options="$agamas->pluck('nama', 'id')" nullable
                    required />
            </div>

        </div>


        {{-- ================= ADMINISTRASI ================= --}}
        <h6 class="mb-3 mt-4">Administrasi</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.input name="no_bpjs_ketenagakerjaan" id="diterima_no_bpjs_ketenagakerjaan"
                    label="No. BPJS Ketenagakerjaan" inputmode="numeric" placeholder="11 digit angka" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_bpjs_kesehatan" id="diterima_no_bpjs_kesehatan" label="No. BPJS Kesehatan"
                    inputmode="numeric" placeholder="13 digit angka" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_npwp" id="diterima_no_npwp" label="No. NPWP" inputmode="numeric"
                    placeholder="16 digit angka" required />
            </div>

        </div>


        {{-- ================= DATA BANK ================= --}}
        <h6 class="mb-3 mt-4">Data Bank</h6>

        <div class="row">

            <div class="col-md-4">
                <x-form.select name="bank_id" id="diterima_bank_id" label="Bank" :options="$banks->pluck('nama', 'id')" nullable
                    required />
            </div>

            <div class="col-md-4">
                <x-form.input name="nama_bank" id="diterima_nama_bank" label="Nama Pemilik Rekening"
                    placeholder="Masukkan nama pemilik rekening" required />
            </div>

            <div class="col-md-4">
                <x-form.input name="no_rekening" id="diterima_no_rekening" label="No. Rekening" inputmode="numeric"
                    placeholder="Masukkan nomor rekening" required />
            </div>

        </div>


        {{-- ================= STATUS ================= --}}
        <div class="mt-4">
            <label class="form-label d-block">Status Aktif</label>

            {{-- Hidden input = nilai default kalau switch dimatikan --}}
            <input type="hidden" name="status" value="nonaktif">

            <label class="app-form-switch">
                <input type="checkbox" name="status" value="aktif" id="diterima_status"
                    @checked(old('status', 'aktif') === 'aktif')>
                <span class="app-form-switch__slider"></span>
            </label>
        </div>

    </form>
</x-offcanvas.form>