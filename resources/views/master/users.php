<?php
render_topbar('Master User');
?>

<div class="page-body">
  <div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Daftar Pengguna Sistem</h3>
      <button class="btn btn-primary btn-sm" onclick="openModal('modalCreateUser')">+ User Baru</button>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Nama User</th>
            <th>Email</th>
            <th>Role</th>
            <th>Unit</th>
            <th>Status</th>
            <th style="text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <tr>
            <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role_name'] ?? 'Staff') ?></td>
            <td><?= htmlspecialchars($u['unit_name'] ?? 'General') ?></td>
            <td><span class="badge-status selesai"><?= ucfirst($u['status']) ?></span></td>
            <td style="text-align: center;">
              <button class="btn btn-secondary btn-sm" onclick="openEditUserModal('<?= $u['id'] ?>', '<?= htmlspecialchars($u['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($u['email'], ENT_QUOTES) ?>', '<?= $u['role_id'] ?>', '<?= $u['unit_id'] ?>')">✏️ Edit</button>
              <a href="<?= BASE_URL ?>/index.php?route=master_user_delete&id=<?= $u['id'] ?>" class="btn btn-secondary btn-sm" style="color:#ef4444;" onclick="return confirm('Yakin ingin menghapus user ini?')" title="Hapus">🗑️ Hapus</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Create User -->
<div id="modalCreateUser" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
  <div style="background:white; width:480px; border-radius:12px; padding:24px; box-shadow: 0 20px 25px rgba(0,0,0,0.2);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight:700;">Tambah User Baru</h3>
      <button onclick="closeModal('modalCreateUser')" style="background:none; border:none; font-size:18px; cursor:pointer;">✕</button>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=master_user_store">
      <div class="form-group">
        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: dr. Ahmad" required>
      </div>

      <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control" placeholder="ahmad@rs.id" required>
      </div>

      <div class="form-group">
        <label class="form-label">Password <span class="required">*</span></label>
        <input type="password" name="password" class="form-control" value="password123" required>
      </div>

      <div class="form-group">
        <label class="form-label">Role <span class="required">*</span></label>
        <select name="role_id" class="form-control" required>
          <option value="1">Super Admin</option>
          <option value="2">IT Support</option>
          <option value="3" selected>Petugas Unit</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Unit</label>
        <select name="unit_id" class="form-control">
          <option value="">-- Pilih Unit --</option>
          <?php foreach ($units as $un): ?>
            <option value="<?= $un['id'] ?>"><?= htmlspecialchars($un['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreateUser')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan User</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit User -->
<div id="modalEditUser" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
  <div style="background:white; width:480px; border-radius:12px; padding:24px; box-shadow: 0 20px 25px rgba(0,0,0,0.2);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight:700;">Edit User</h3>
      <button onclick="closeModal('modalEditUser')" style="background:none; border:none; font-size:18px; cursor:pointer;">✕</button>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=master_user_update">
      <input type="hidden" name="id" id="edit_user_id">

      <div class="form-group">
        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="name" id="edit_user_name" class="form-control" required>
      </div>

      <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" id="edit_user_email" class="form-control" required>
      </div>

      <div class="form-group">
        <label class="form-label">Role <span class="required">*</span></label>
        <select name="role_id" id="edit_user_role_id" class="form-control" required>
          <option value="1">Super Admin</option>
          <option value="2">IT Support</option>
          <option value="3">Petugas Unit</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Unit</label>
        <select name="unit_id" id="edit_user_unit_id" class="form-control">
          <option value="">-- Pilih Unit --</option>
          <?php foreach ($units as $un): ?>
            <option value="<?= $un['id'] ?>"><?= htmlspecialchars($un['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditUser')">Batal</button>
        <button type="submit" class="btn btn-primary">Update User</button>
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
function openEditUserModal(id, name, email, roleId, unitId) {
  document.getElementById('edit_user_id').value = id;
  document.getElementById('edit_user_name').value = name;
  document.getElementById('edit_user_email').value = email;
  document.getElementById('edit_user_role_id').value = roleId;
  document.getElementById('edit_user_unit_id').value = unitId;
  openModal('modalEditUser');
}
</script>
