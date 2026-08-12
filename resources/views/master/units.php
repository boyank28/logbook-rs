<?php
render_topbar('Master Unit');
?>

<div class="page-body">
  <div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Daftar Unit Kerja</h3>
      <button class="btn btn-primary btn-sm" onclick="openModal('modalCreateUnit')">+ Unit Baru</button>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Kode Unit</th>
            <th>Nama Unit</th>
            <th style="text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($units as $un): ?>
          <tr>
            <td><strong><?= htmlspecialchars($un['code']) ?></strong></td>
            <td><?= htmlspecialchars($un['name']) ?></td>
            <td style="text-align: center;">
              <button class="btn btn-secondary btn-sm" onclick="openEditUnitModal('<?= $un['id'] ?>', '<?= htmlspecialchars($un['code'], ENT_QUOTES) ?>', '<?= htmlspecialchars($un['name'], ENT_QUOTES) ?>')">✏️ Edit</button>
              <a href="<?= BASE_URL ?>/index.php?route=master_unit_delete&id=<?= $un['id'] ?>" class="btn btn-secondary btn-sm" style="color:#ef4444;" onclick="return confirm('Yakin ingin menghapus unit ini?')" title="Hapus">🗑️ Hapus</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Create Unit -->
<div id="modalCreateUnit" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
  <div style="background:white; width:420px; border-radius:12px; padding:24px; box-shadow: 0 20px 25px rgba(0,0,0,0.2);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight:700;">Tambah Unit Baru</h3>
      <button onclick="closeModal('modalCreateUnit')" style="background:none; border:none; font-size:18px; cursor:pointer;">✕</button>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=master_unit_store">
      <div class="form-group">
        <label class="form-label">Kode Unit <span class="required">*</span></label>
        <input type="text" name="code" class="form-control" placeholder="Contoh: RAD, LAB, ICU" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Unit <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: Radiologi, Laboratorium" required>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreateUnit')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Unit</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Unit -->
<div id="modalEditUnit" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
  <div style="background:white; width:420px; border-radius:12px; padding:24px; box-shadow: 0 20px 25px rgba(0,0,0,0.2);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight:700;">Edit Unit</h3>
      <button onclick="closeModal('modalEditUnit')" style="background:none; border:none; font-size:18px; cursor:pointer;">✕</button>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=master_unit_update">
      <input type="hidden" name="id" id="edit_unit_id">

      <div class="form-group">
        <label class="form-label">Kode Unit <span class="required">*</span></label>
        <input type="text" name="code" id="edit_unit_code" class="form-control" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Unit <span class="required">*</span></label>
        <input type="text" name="name" id="edit_unit_name" class="form-control" required>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditUnit')">Batal</button>
        <button type="submit" class="btn btn-primary">Update Unit</button>
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
function openEditUnitModal(id, code, name) {
  document.getElementById('edit_unit_id').value = id;
  document.getElementById('edit_unit_code').value = code;
  document.getElementById('edit_unit_name').value = name;
  openModal('modalEditUnit');
}
</script>
