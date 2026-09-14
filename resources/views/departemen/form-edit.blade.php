<x-offcanvas.form
    id="offcanvas-departemen-edit"
    title="Edit Departemen"
    description="Perbarui data departemen."
    size="md"
>
    <form id="offcanvas-departemen-edit-form">
        @csrf
        <input type="hidden" name="id" id="edit-departemen-id">

        <x-form.input
            name="kode_departemen"
            label="Kode Departemen"
            id="edit-kode_departemen"
            readonly
        />

        <x-form.input
            name="nama_departemen"
            label="Nama Departemen"
            id="edit-nama_departemen"
            required
        />

        <x-form.switch
            name="status"
            label="Status Aktif"
            id="edit-status"
        />
    </form>
</x-offcanvas.form>

<script>
    document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-bs-target="#offcanvas-departemen-edit"]');
    if (!btn) return;

    const id = btn.dataset.id;

    // Ambil data departemen dari API
    fetch(`/departemen/${id}/edit-data`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit-departemen-id').value = data.id;
            document.getElementById('edit-kode_departemen').value = data.kode_departemen;
            document.getElementById('edit-nama_departemen').value = data.nama_departemen;
            document.getElementById('edit-status').checked = data.status === 'aktif';
        })
        .catch(err => console.error('Gagal ambil data departemen:', err));
});
</script>