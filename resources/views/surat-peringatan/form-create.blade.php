<x-offcanvas.form id="offcanvas-sp" title="Buat Surat Peringatan"
    description="Terbitkan surat peringatan baru untuk karyawan." size="lg">
    <form id="offcanvas-sp-form" action="{{ route('surat-peringatan.store') }}" method="POST" enctype="multipart/form-data"
        novalidate>
        @csrf

        <x-form.input name="kode_preview" id="sp_create_kode" label="Kode Surat"
            value="Dibuat otomatis sesuai jenis surat" readonly />

        <x-form.select name="karyawan_id" id="sp_create_karyawan_id" label="Karyawan" :options="$karyawanOptions"
            nullable required />

        <div class="row">
            <div class="col-md-6">
                <x-form.select name="jenis_surat" id="sp_create_jenis_surat" label="Jenis Surat" :options="$jenisOptions"
                    nullable required />
            </div>

            <div class="col-md-6">
                {{-- min default "today": masa berlaku surat baru tidak boleh tanggal lampau --}}
                <x-form.input-date name="masa_berlaku" id="sp_create_masa_berlaku" label="Masa Berlaku" required />
            </div>
        </div>

        <x-form.file-upload name="file" id="sp_create_file" label="File Surat" accept=".pdf,.jpg,.jpeg,.png"
            :max-size="5" hint="PDF, JPG, atau PNG · Maks. 5 MB" required />

        <x-form.switch name="status" id="sp_create_status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>
