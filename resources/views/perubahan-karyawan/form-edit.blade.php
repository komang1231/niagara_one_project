<x-offcanvas.form id="offcanvas-pk-edit" title="Detail Perubahan Karyawan"
    description="Lihat data perubahan, atau klik Edit untuk memperbaruinya." size="xl">
    <form id="offcanvas-pk-edit-form" method="POST" enctype="multipart/form-data" novalidate data-pk-form
        data-pk-edit-form data-mock="{{ $isMock ? 1 : 0 }}"
        data-url-divisi="{{ route('karyawan.get-divisi', ['departemen' => '__ID__']) }}"
        data-url-section="{{ route('karyawan.get-section', ['divisi' => '__ID__']) }}"
        data-url-posisi="{{ route('karyawan.get-job-position', ['section' => '__ID__']) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="_form" value="offcanvas-pk-edit">

        {{-- Banner mode (hanya di form lihat/edit) --}}
        <div class="pk-banner pk-banner--view" data-pk-banner>
            <i class="bi bi-eye" data-pk-banner-icon></i>
            <span data-pk-banner-text>Mode lihat — data terkunci. Klik Edit untuk mengubah.</span>
        </div>

        {{-- ===================== 1. KARYAWAN ===================== --}}
        <div class="mb-3">
            <label for="pk_edit_karyawan_id" class="form-label fw-semibold">
                Karyawan <span class="text-danger">*</span>
            </label>

            {{-- Ditulis manual (bukan <x-form.select>) karena tiap <option> membawa data karyawan saat ini (data-*) --}}
            <select name="karyawan_id" id="pk_edit_karyawan_id" class="form-select select2" data-pk-karyawan
                data-placeholder="Cari nama / NIP karyawan..." data-search-placeholder="Cari nama atau NIP...">
                <option value=""></option>
                @foreach ($karyawanOptions as $k)
                    <option value="{{ data_get($k, 'id') }}" data-departemen-id="{{ data_get($k, 'departemen_id') }}"
                        data-departemen="{{ data_get($k, 'departemen') }}"
                        data-divisi-id="{{ data_get($k, 'divisi_id') }}" data-divisi="{{ data_get($k, 'divisi') }}"
                        data-section-id="{{ data_get($k, 'section_id') }}" data-section="{{ data_get($k, 'section') }}"
                        data-posisi-id="{{ data_get($k, 'posisi_id') }}" data-posisi="{{ data_get($k, 'posisi') }}"
                        data-level-id="{{ data_get($k, 'level_id') }}" data-level="{{ data_get($k, 'level') }}"
                        data-cabang-id="{{ data_get($k, 'cabang_id') }}" data-cabang="{{ data_get($k, 'cabang') }}">
                        {{ data_get($k, 'nama') }} · {{ data_get($k, 'nip') }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="karyawan_id" id="pk_edit_karyawan_id_hidden">
        </div>

        {{-- Bagian di bawah ini baru muncul setelah karyawan dipilih --}}
        <div data-pk-after>

            {{-- ===================== 2. DATA SAAT INI (selalu terkunci) ===================== --}}
            <div class="pk-section-title">
                <i class="bi bi-lock-fill"></i> Data Saat Ini
                <span class="pk-section-title__note">Hanya baca</span>
            </div>

            <div class="pk-card">
                <div class="row">
                    <div class="col-md-4">
                        <x-form.input name="lama_departemen" id="pk_edit_lama_departemen" label="Departemen"
                            placeholder="-" readonly disabled data-pk-lama="departemen" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="lama_divisi" id="pk_edit_lama_divisi" label="Divisi" placeholder="-"
                            readonly disabled data-pk-lama="divisi" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="lama_section" id="pk_edit_lama_section" label="Section" placeholder="-"
                            readonly disabled data-pk-lama="section" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="lama_posisi" id="pk_edit_lama_posisi" label="Job Position" placeholder="-"
                            readonly disabled data-pk-lama="posisi" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="lama_level" id="pk_edit_lama_level" label="Job Level" placeholder="-"
                            readonly disabled data-pk-lama="level" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="lama_cabang" id="pk_edit_lama_cabang" label="Cabang" placeholder="-"
                            readonly disabled data-pk-lama="cabang" />
                    </div>
                </div>
            </div>

            {{-- ===================== 3. JENIS PERUBAHAN ===================== --}}
            <div class="pk-section-title"><i class="bi bi-arrow-left-right"></i> Jenis Perubahan</div>

            <x-form.select name="jenis_perubahan" id="pk_edit_jenis_perubahan" label="Jenis Perubahan"
                placeholder="Pilih Jenis Perubahan" :options="$jenisOptions" nullable required data-pk-jenis />

            <p class="pk-rule-hint" data-pk-rule-hint>
                <i class="bi bi-info-circle"></i>
                <span>Pilih jenis perubahan untuk menentukan data yang dapat diubah.</span>
            </p>

            {{-- ===================== 4. DATA PERUBAHAN ===================== --}}
            <div class="pk-section-title"><i class="bi bi-pencil-square"></i> Data Perubahan</div>

            <div class="row">
                <div class="col-md-6">
                    <x-form.select name="departemen_baru" id="pk_edit_departemen_baru" label="Departemen Baru"
                        placeholder="Pilih Departemen" :options="$departemenOptions" nullable required disabled
                        data-pk-baru="departemen" />
                </div>
                <div class="col-md-6">
                    <x-form.select name="divisi_baru" id="pk_edit_divisi_baru" label="Divisi Baru"
                        placeholder="Pilih Divisi" :options="[]" nullable required disabled
                        data-pk-baru="divisi" />
                </div>
                <div class="col-md-6">
                    <x-form.select name="section_baru" id="pk_edit_section_baru" label="Section Baru"
                        placeholder="Pilih Section" :options="[]" nullable required disabled
                        data-pk-baru="section" />
                </div>
                <div class="col-md-6">
                    <x-form.select name="posisi_baru" id="pk_edit_posisi_baru" label="Job Position Baru"
                        placeholder="Pilih Job Position" :options="[]" nullable required disabled
                        data-pk-baru="posisi" />
                </div>
                <div class="col-md-6">
                    <x-form.select name="level_baru" id="pk_edit_level_baru" label="Job Level Baru"
                        placeholder="Pilih Job Level" :options="$levelOptions" nullable disabled data-pk-baru="level" />
                    <div class="pk-lock-note d-none" data-pk-lock-note="level">
                        <i class="bi bi-lock-fill"></i> <span></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <x-form.select name="cabang_baru" id="pk_edit_cabang_baru" label="Cabang Baru"
                        placeholder="Pilih Cabang" :options="$cabangOptions" nullable disabled data-pk-baru="cabang" />
                    <div class="pk-lock-note d-none" data-pk-lock-note="cabang">
                        <i class="bi bi-lock-fill"></i> <span></span>
                    </div>
                </div>
            </div>

            {{--
                Select yang disabled TIDAK ikut terkirim saat submit. Supaya nilainya tetap sampai ke server
                (mis. Job Level & Cabang saat Rotasi), JS mengaktifkan hidden input ini kalau select-nya disabled.
            --}}
            @foreach (['departemen', 'divisi', 'section', 'posisi', 'level', 'cabang'] as $f)
                <input type="hidden" name="{{ $f }}_baru" value="" disabled
                    data-pk-keep="{{ $f }}">
            @endforeach

            {{-- ===================== 5. DOKUMEN PERUBAHAN ===================== --}}
            <div class="pk-section-title"><i class="bi bi-file-earmark-text"></i> Dokumen Perubahan</div>

            <div class="row">
                <div class="col-md-6">
                    <x-form.input name="kode" id="pk_edit_kode" label="Nomor SK" placeholder="Nomor SK"
                        readonly />
                </div>
                <div class="col-md-6">
                    <x-form.input-date name="tanggal_efektif" id="pk_edit_tanggal_efektif" label="Tanggal Efektif"
                        required />
                </div>
            </div>

            {{-- File SK yang sudah tersimpan (diisi JS, hanya di form lihat/edit) --}}
            <div class="pk-current-file mb-3" data-pk-current-file>
                <span class="pk-current-file__icon"><i class="bi bi-file-earmark-text"></i></span>
                <div class="pk-current-file__info">
                    <span class="pk-current-file__label">File SK saat ini</span>
                    <span class="pk-current-file__name" data-pk-file-name>Surat SK belum tersedia</span>
                </div>
            </div>

            <x-form.file-upload name="file_sk" id="pk_edit_file_sk" label="Ganti File SK (opsional)"
                accept=".pdf,.jpg,.jpeg,.png" :max-size="5" :required="false"
                hint="Kosongkan jika file tidak diganti · PDF, JPG, PNG · Maks. 5 MB" />

            {{-- ===================== 6. RINGKASAN PERUBAHAN ===================== --}}
            <div class="pk-summary" data-pk-summary>
                <div class="pk-section-title mt-0"><i class="bi bi-clipboard-check"></i> Ringkasan Perubahan</div>
                <dl class="pk-summary__list" data-pk-summary-list></dl>
            </div>
        </div>
    </form>

    {{-- Tombol di kiri footer, berseberangan dengan Batal & Simpan Perubahan (pola sama dengan Surat Peringatan) --}}
    <x-slot:extra>
        <div class="d-flex gap-2">
            <x-button variant="outline" icon="bi-file-earmark-text" href="#" target="_blank" rel="noopener"
                data-pk-action="view-file">Lihat Surat SK</x-button>
            <x-button variant="primary" icon="bi-pencil" data-pk-action="edit">Edit</x-button>
        </div>
    </x-slot:extra>
</x-offcanvas.form>
