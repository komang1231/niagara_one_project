<x-offcanvas.form
    id="offcanvas-departemen"
    title="Tambah Departemen"
    description="Tambahkan departemen baru ke sistem."
    size="md"
>
    {{-- id form ini HARUS "{id-offcanvas}-form" supaya tombol Simpan di footer
         (yang letaknya di luar form ini) tetap bisa submit form ini --}}
    <form id="offcanvas-departemen-form">
        @csrf

        <x-form.input
            name="kode_departemen"
            label="Kode Departemen"
            value="BRND0010"
            readonly
        />

        <x-form.input
            name="nama_departemen"
            label="Nama Departemen"
            placeholder="Contoh: Human Resources"
            required
        />

        <x-form.switch
            name="status"
            label="Status Aktif"
            :checked="true"
        />
    </form>
</x-offcanvas.form>

<script>
    // Sementara: submit form belum disambungkan ke backend (belum ada store()).
    // Ini cuma mencegah reload halaman waktu tombol Simpan diklik.
    // Nanti bkaldiganti dengan request AJAX ke route store.
    document.getElementById('offcanvas-departemen-form')
        .addEventListener('submit', function (e) {
            e.preventDefault();
        });
</script>