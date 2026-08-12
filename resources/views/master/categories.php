<?php
render_topbar('Master Kategori');
?>

<div class="page-body">
  <div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Daftar Kategori Logbook</h3>
      <button class="btn btn-primary btn-sm" onclick="openModal('modalCreateCategory')">+ Kategori Baru</button>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Nama Kategori</th>
            <th>Keterangan</th>
            <th style="text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>SIMRS</strong></td>
            <td>Gangguan modul SIMRS</td>
            <td style="text-align:center;">
              <button class="btn btn-secondary btn-sm" onclick="alert('Edit Kategori SIMRS')">✏️ Edit</button>
              <button class="btn btn-secondary btn-sm" style="color:#ef4444;" onclick="alert('Hapus Kategori')">🗑️ Hapus</button>
            </td>
          </tr>
          <tr>
            <td><strong>Jaringan</strong></td>
            <td>Koneksi LAN & WiFi</td>
            <td style="text-align:center;">
              <button class="btn btn-secondary btn-sm" onclick="alert('Edit Kategori Jaringan')">✏️ Edit</button>
              <button class="btn btn-secondary btn-sm" style="color:#ef4444;" onclick="alert('Hapus Kategori')">🗑️ Hapus</button>
            </td>
          </tr>
          <tr>
            <td><strong>Server</strong></td>
            <td>Maintenis Server</td>
            <td style="text-align:center;">
              <button class="btn btn-secondary btn-sm" onclick="alert('Edit Kategori Server')">✏️ Edit</button>
              <button class="btn btn-secondary btn-sm" style="color:#ef4444;" onclick="alert('Hapus Kategori')">🗑️ Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Create Category -->
<div id="modalCreateCategory" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
  <div style="background:white; width:420px; border-radius:12px; padding:24px; box-shadow: 0 20px 25px rgba(0,0,0,0.2);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight:700;">Tambah Kategori Baru</h3>
      <button onclick="closeModal('modalCreateCategory')" style="background:none; border:none; font-size:18px; cursor:pointer;">✕</button>
    </div>
    
    <form onsubmit="event.preventDefault(); alert('Kategori baru berhasil disimpan!'); closeModal('modalCreateCategory');">
      <div class="form-group">
        <label class="form-label">Nama Kategori <span class="required">*</span></label>
        <input type="text" class="form-control" placeholder="Contoh: Hardware, Software" required>
      </div>

      <div class="form-group">
        <label class="form-label">Keterangan</label>
        <textarea class="form-control" rows="3" placeholder="Deskripsi kategori..."></textarea>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreateCategory')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Kategori</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'flex';
}
function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'none';
}
</script>
