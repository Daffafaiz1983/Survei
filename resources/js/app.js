import './bootstrap';
// Quick add category modal logic for question forms
document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('btn-open-category-modal');
    const deleteBtn = document.getElementById('btn-delete-category');
    const modal = document.getElementById('category-modal');
    const cancelBtn = document.getElementById('btn-cancel-category');
    const saveBtn = document.getElementById('btn-save-category');
    const nameInput = document.getElementById('new-category-name');
    const errorEl = document.getElementById('new-category-error');
    const select = document.getElementById('category_id');

    if (!openBtn || !modal || !select) return;

    const toggle = (show) => {
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
        if (show) setTimeout(() => nameInput && nameInput.focus(), 50);
    };

    openBtn.addEventListener('click', () => toggle(true));
    cancelBtn && cancelBtn.addEventListener('click', () => toggle(false));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) toggle(false);
    });

    saveBtn && saveBtn.addEventListener('click', async () => {
        if (!nameInput) return;
        const name = nameInput.value.trim();
        if (!name) {
            errorEl && (errorEl.textContent = 'Nama kategori wajib diisi.');
            errorEl && errorEl.classList.remove('hidden');
            return;
        }
        errorEl && errorEl.classList.add('hidden');
        try {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('/admin/categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name })
            });
            if (!res.ok) {
                const data = await res.json().catch(() => ({}));
                const msg = data.message || 'Gagal menyimpan kategori.';
                errorEl && (errorEl.textContent = msg);
                errorEl && errorEl.classList.remove('hidden');
                return;
            }
            const { id, name: savedName } = await res.json();
            // Tambahkan ke dropdown dan pilih
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = savedName;
            select.appendChild(opt);
            select.value = String(id);
            // Reset dan tutup
            nameInput.value = '';
            toggle(false);
        } catch (err) {
            errorEl && (errorEl.textContent = 'Terjadi kesalahan jaringan.');
            errorEl && errorEl.classList.remove('hidden');
        }
    });

    // Delete selected category via AJAX
    deleteBtn && deleteBtn.addEventListener('click', async () => {
        const selected = select.value;
        if (!selected) return;
        if (!confirm('Hapus kategori terpilih? Ini tidak dapat dibatalkan.')) return;
        try {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`/admin/categories/${selected}`, {
                method: 'POST', // Laravel HTML forms use POST with method spoofing; for fetch we can send DELETE
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'DELETE'
                }
            });
            if (!(res.ok || res.status === 204)) {
                alert('Gagal menghapus kategori.');
                return;
            }
            // Remove option from select
            const opt = select.querySelector(`option[value="${selected}"]`);
            if (opt) opt.remove();
            select.value = '';
        } catch (e) {
            alert('Terjadi kesalahan jaringan.');
        }
    });
});


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
