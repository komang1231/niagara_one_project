<x-offcanvas.form id="offcanvas-departemen" title="Tambah Departemen" description="Tambahkan departemen baru ke sistem."
    size="md">
    <form id="offcanvas-departemen-form" action="{{ route('departemen.store') }}" method="POST">
        @csrf
        <x-form.input name="kode_preview" label="Kode Departemen" value="{{ $previewKode }}" readonly />
        <p class="text-xs text-gray-400 mt-1">*Kode final digenerate otomatis saat data disimpan.</p>

        <x-form.input name="nama" label="Nama Departemen" placeholder="Contoh: Human Resources" required />

        <x-form.switch name="status" label="Status Aktif" :checked="true" />
    </form>
</x-offcanvas.form>



{{-- <script>
    // Sementara: submit form belum disambungkan ke backend (belum ada store()).
    // Ini cuma mencegah reload halaman waktu tombol Simpan diklik.
    // Nanti bkaldiganti dengan request AJAX ke route store.
    document.getElementById('offcanvas-departemen-form')
        .addEventListener('submit', function(e) {
            e.preventDefault();
        });
</script> --}}
