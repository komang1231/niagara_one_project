{{--
    Offcanvas lihat/edit Surat Peringatan.
    Alur (logic-nya ada di resources/js/surat-peringatan.js):
      1. Dibuka dari ikon pensil -> semua field TERKUNCI (mode lihat), Simpan nonaktif.
      2. Footer kiri: [Edit] [Lihat Surat]. Klik Edit -> field terbuka, tombol Edit hilang,
         Simpan aktif. [Lihat Surat] tetap ada di kedua mode.
--}}
<x-offcanvas.form id="offcanvas-sp-edit" title="Detail Surat Peringatan"
    description="Lihat surat, atau klik Edit untuk memperbarui data." size="lg">
    <form id="offcanvas-sp-edit-form" method="POST" enctype="multipart/form-data" data-edit-form data-sp-form
        novalidate>
        @csrf
        @method('PUT')

        {{-- Penanda form mana yang disubmit (dipakai untuk membuka lagi offcanvas kalau validasi gagal) --}}
        <input type="hidden" name="_form" value="offcanvas-sp-edit">

        <x-form.input name="kode" id="sp_edit_kode" label="Kode Surat" readonly data-sp-locked />

        <x-form.select name="karyawan_id" id="sp_edit_karyawan_id" label="Karyawan" :options="$karyawanOptions" nullable
            required />

        <div class="row">
            <div class="col-md-6">
                <x-form.select name="jenis_surat" id="sp_edit_jenis_surat" label="Jenis Surat" :options="$jenisOptions"
                    nullable required />
            </div>

            <div class="col-md-6">
                {{-- Data lama yang sudah lewat tetap tampil (ditangani input-date.js), hanya pilihan baru dibatasi min today --}}
                <x-form.input-date name="masa_berlaku" id="sp_edit_masa_berlaku" label="Masa Berlaku" required />
            </div>
        </div>

        {{-- File saat ini (diisi JS) --}}
        <div class="sp-current-file mb-3 d-none" data-sp-current-file>
            <span class="sp-current-file__icon"><i class="bi bi-file-earmark-text"></i></span>
            <div class="sp-current-file__info">
                <span class="sp-current-file__label">File saat ini</span>
                <span class="sp-current-file__name" data-sp-file-name></span>
            </div>
        </div>

        <x-form.file-upload name="file" id="sp_edit_file" label="Ganti File Surat (opsional)"
            accept=".pdf,.jpg,.jpeg,.png" :max-size="5" hint="Kosongkan jika file tidak diganti · PDF, JPG, PNG · Maks. 5 MB" />

        <x-form.switch name="status" id="sp_edit_status" label="Status Aktif" :checked="true" />
    </form>

    {{-- Tombol di kiri footer, berseberangan dengan Batal & Simpan --}}
    <x-slot:extra>
        <div class="d-flex gap-2">
            <x-button variant="outline" icon="bi-pencil" data-sp-action="edit">Edit</x-button>
            <x-button variant="outline" icon="bi-file-earmark-text" href="#" target="_blank" rel="noopener"
                data-sp-file data-sp-action="view-file">Lihat Surat</x-button>
        </div>
    </x-slot:extra>
</x-offcanvas.form>
