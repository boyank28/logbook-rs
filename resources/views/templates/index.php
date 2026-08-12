<?php
render_topbar('Template Field');
?>

<div class="page-body">
  <div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Daftar Template Logbook Dinamis</h3>
      <button class="btn btn-primary btn-sm" onclick="openModal('modalCreateTemplate')">+ Template Baru</button>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Nama Template</th>
            <th>Jumlah Field</th>
            <th style="text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($templates as $t): ?>
          <tr>
            <td><strong><?= htmlspecialchars($t['name']) ?></strong></td>
            <td><?= $t['field_count'] ?></td>
            <td style="text-align: center;">
              <a href="<?= BASE_URL ?>/index.php?route=template_edit&id=<?= $t['id'] ?>" class="btn btn-secondary btn-sm" title="Edit Field">✏️ Edit Field</a>
              <a href="<?= BASE_URL ?>/index.php?route=template_delete&id=<?= $t['id'] ?>" class="btn btn-secondary btn-sm" style="color:#ef4444;" onclick="return confirm('Yakin ingin menghapus template ini?')" title="Hapus">🗑️ Hapus</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Create Template -->
<div id="modalCreateTemplate" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
  <div style="background:white; width:450px; border-radius:12px; padding:24px; box-shadow: 0 20px 25px rgba(0,0,0,0.2);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight:700;">Tambah Template Baru</h3>
      <button onclick="closeModal('modalCreateTemplate')" style="background:none; border:none; font-size:18px; cursor:pointer;">✕</button>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=template_store">
      <div class="form-group">
        <label class="form-label">Nama Template <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: Log Apotek & Resep" required>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi Template</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Catatan fungsi template ini..."></textarea>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreateTemplate')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Template</button>
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
