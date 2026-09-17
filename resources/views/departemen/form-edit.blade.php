<x-offcanvas.form id="offcanvas-departemen-edit" title="Edit Departemen" description="Perbarui data departemen." size="md">
    <form id="offcanvas-departemen-edit-form" method="POST">
        @csrf
        @method('PUT')

        <x-form.input name="kode" label="Kode Departemen" id="edit-kode" readonly />
        <x-form.input name="nama" label="Nama Departemen" id="edit-nama" required />
        <x-form.switch name="status" label="Status Aktif" id="edit-status" />
    </form>
</x-offcanvas.form>

<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-bs-target="#offcanvas-departemen-edit"]');
        if (!btn) return;

        const id = btn.dataset.id;
        const form = document.getElementById('offcanvas-departemen-edit-form');
        form.action = `/departemen/${id}`;

        fetch(`/departemen/${id}/edit-data`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit-kode').value = data.kode;
                document.getElementById('edit-nama').value = data.nama;
                document.getElementById('edit-status').checked = data.status === 'aktif';
            })
            .catch(err => console.error('Gagal ambil data departemen:', err));
    });
</script>