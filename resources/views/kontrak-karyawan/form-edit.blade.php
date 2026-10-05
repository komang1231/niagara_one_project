<x-offcanvas.form id="offcanvas-kontrak-edit" title="Edit Kontrak"
    description="Nomor kontrak dan karyawan tidak dapat diubah." size="md">
    <form id="offcanvas-kontrak-edit-form" method="POST" data-edit-form novalidate data-kontrak-form
        data-durasi-bulan="{{ $durasiBulan }}">
        @csrf
        @method('PATCH')

        <x-form.input name="nomor_kontrak" id="kt_edit_nomor" label="Nomor Kontrak" disabled />

        <x-form.input name="karyawan_nama" id="kt_edit_karyawan" label="Karyawan" disabled />

        <x-form.select name="status_kepegawaian_id" id="kt_edit_status_kepegawaian_id" label="Status Kepegawaian"
            :options="$statusKepegawaianOptions" nullable required />

        <x-form.input-date name="tanggal_mulai" id="kt_edit_tanggal_mulai" label="Tanggal Mulai" required />

        <x-form.input name="tanggal_berakhir" id="kt_edit_tanggal_berakhir" label="Tanggal Berakhir" disabled
            data-kontrak-akhir />

        <p class="text-muted small mb-0">
            Tanggal berakhir ditentukan otomatis oleh sistem. Untuk memperpanjang kontrak, gunakan aksi Perpanjang di
            tabel.
        </p>
    </form>
</x-offcanvas.form>
