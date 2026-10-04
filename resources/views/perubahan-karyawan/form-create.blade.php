<x-offcanvas.form id="offcanvas-pk" title="Buat Perubahan Karyawan"
    description="Catat perubahan data organisasi karyawan (promosi, demosi, rotasi, mutasi)." size="xl">

    <form id="offcanvas-pk-form" action="{{ url('perubahan-karyawan') }}" method="POST"
        enctype="multipart/form-data" novalidate data-pk-form data-mock="0"
        data-url-divisi="{{ route('karyawan.get-divisi', ['departemen' => '__ID__']) }}"
        data-url-section="{{ route('karyawan.get-section', ['divisi' => '__ID__']) }}"
        data-url-posisi="{{ route('karyawan.get-job-position', ['section' => '__ID__']) }}">

            @csrf

            {{-- Penanda form mana yang disubmit (untuk membuka lagi offcanvas kalau validasi server gagal) --}}
            <input type="hidden" name="_form" value="offcanvas-pk">

            {{-- ===================== 1. KARYAWAN ===================== --}}
            <div class="mb-3">
                <label for="pk_create_karyawan_id" class="form-label fw-semibold">
                    Karyawan <span class="text-danger">*</span>
                </label>

                {{-- Ditulis manual (bukan <x-form.select>) karena tiap <option> membawa data karyawan saat ini (data-*) --}}
                <select name="karyawan_id" id="pk_create_karyawan_id" class="form-select select2" data-pk-karyawan
                    data-placeholder="Cari nama / NIP karyawan..." data-search-placeholder="Cari nama atau NIP...">
                    <option value=""></option>
                    @foreach ($karyawanOptions as $k)
                        <option value="{{ $k->id }}" data-departemen-id="{{ $k->departemen_id }}"
                            data-departemen="{{ $k->departemen?->nama }}" data-divisi-id="{{ $k->divisi_id }}"
                            data-divisi="{{ $k->divisi?->nama }}" data-section-id="{{ $k->section_id }}"
                            data-section="{{ $k->section?->nama }}" data-posisi-id="{{ $k->job_position_id }}"
                            data-posisi="{{ $k->jobPosition?->nama }}" data-level-id="{{ $k->job_level_id }}"
                            data-level="{{ $k->jobLevel?->nama }}" data-cabang-id="{{ $k->cabang_kantor_id }}"
                            data-cabang="{{ $k->cabangKantor?->nama }}">
                            {{ $k->nama }} · {{ $k->nip }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kosong sebelum karyawan dipilih --}}
            <div class="pk-empty" data-pk-empty>
                <i class="bi bi-person-lines-fill"></i>
                <span>Pilih karyawan untuk menampilkan data saat ini dan mengisi perubahan.</span>
            </div>

            {{-- Bagian di bawah ini baru muncul setelah karyawan dipilih --}}
            <div data-pk-after class="d-none">

                {{-- ===================== 2. DATA SAAT INI (selalu terkunci) ===================== --}}
                <div class="pk-section-title">
                    <i class="bi bi-lock-fill"></i> Data Saat Ini
                    <span class="pk-section-title__note">Hanya baca</span>
                </div>

                <div class="pk-card">
                    <div class="row">
                        <div class="col-md-4">
                            <x-form.input name="lama_departemen" id="pk_create_lama_departemen" label="Departemen"
                                placeholder="-" readonly disabled data-pk-lama="departemen" />
                        </div>
                        <div class="col-md-4">
                            <x-form.input name="lama_divisi" id="pk_create_lama_divisi" label="Divisi" placeholder="-"
                                readonly disabled data-pk-lama="divisi" />
                        </div>
                        <div class="col-md-4">
                            <x-form.input name="lama_section" id="pk_create_lama_section" label="Section" placeholder="-"
                                readonly disabled data-pk-lama="section" />
                        </div>
                        <div class="col-md-4">
                            <x-form.input name="lama_posisi" id="pk_create_lama_posisi" label="Job Position" placeholder="-"
                                readonly disabled data-pk-lama="posisi" />
                        </div>
                        <div class="col-md-4">
                            <x-form.input name="lama_level" id="pk_create_lama_level" label="Job Level" placeholder="-"
                                readonly disabled data-pk-lama="level" />
                        </div>
                        <div class="col-md-4">
                            <x-form.input name="lama_cabang" id="pk_create_lama_cabang" label="Cabang" placeholder="-"
                                readonly disabled data-pk-lama="cabang" />
                        </div>
                    </div>
                </div>

                {{-- ===================== 3. JENIS PERUBAHAN ===================== --}}
                <div class="pk-section-title"><i class="bi bi-arrow-left-right"></i> Jenis Perubahan</div>

                <x-form.select name="jenis_perubahan" id="pk_create_jenis_perubahan" label="Jenis Perubahan"
                    placeholder="Pilih Jenis Perubahan" :options="$jenisOptions" nullable required data-pk-jenis />

                <p class="pk-rule-hint" data-pk-rule-hint>
                    <i class="bi bi-info-circle"></i>
                    <span>Pilih jenis perubahan untuk menentukan data yang dapat diubah.</span>
                </p>

                {{-- ===================== 4. DATA PERUBAHAN ===================== --}}
                <div class="pk-section-title"><i class="bi bi-pencil-square"></i> Data Perubahan</div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form.select name="departemen_baru" id="pk_create_departemen_baru" label="Departemen Baru"
                            placeholder="Pilih Departemen" :options="$departemenOptions" nullable required disabled
                            data-pk-baru="departemen" />
                    </div>
                    <div class="col-md-6">
                        <x-form.select name="divisi_baru" id="pk_create_divisi_baru" label="Divisi Baru"
                            placeholder="Pilih Divisi" :options="[]" nullable required disabled
                            data-pk-baru="divisi" />
                    </div>
                    <div class="col-md-6">
                        <x-form.select name="section_baru" id="pk_create_section_baru" label="Section Baru"
                            placeholder="Pilih Section" :options="[]" nullable required disabled
                            data-pk-baru="section" />
                    </div>
                    <div class="col-md-6">
                        <x-form.select name="posisi_baru" id="pk_create_posisi_baru" label="Job Position Baru"
                            placeholder="Pilih Job Position" :options="[]" nullable required disabled
                            data-pk-baru="posisi" />
                    </div>
                    <div class="col-md-6">
                        <x-form.select name="level_baru" id="pk_create_level_baru" label="Job Level Baru"
                            placeholder="Pilih Job Level" :options="$levelOptions" nullable disabled data-pk-baru="level" />
                        <div class="pk-lock-note d-none" data-pk-lock-note="level">
                            <i class="bi bi-lock-fill"></i> <span></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-form.select name="cabang_baru" id="pk_create_cabang_baru" label="Cabang Baru"
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
                        <x-form.input name="nomor_sk" id="pk_create_nomor_sk" label="Nomor SK"
                            placeholder="Contoh: SK/HRD/001/X/2026" required />
                    </div>
                    <div class="col-md-6">
                        <x-form.input-date name="tanggal_efektif" id="pk_create_tanggal_efektif" label="Tanggal Efektif"
                            required />
                    </div>
                </div>

                {{-- File SK yang sudah tersimpan (diisi JS, hanya di form lihat/edit) --}}

                <x-form.file-upload name="file_sk" id="pk_create_file_sk" label="File SK" accept=".pdf,.jpg,.jpeg,.png"
                    :max-size="5" :required="true" hint="PDF, JPG, atau PNG · Maks. 5 MB" />

                {{-- ===================== 6. RINGKASAN PERUBAHAN ===================== --}}
                <div class="pk-summary" data-pk-summary>
                    <div class="pk-section-title mt-0"><i class="bi bi-clipboard-check"></i> Ringkasan Perubahan</div>
                    <dl class="pk-summary__list" data-pk-summary-list></dl>
                </div>
            </div>
        </form>
    </x-offcanvas.form>
