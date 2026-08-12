<form method="POST" action="<?= BASE_URL ?>/index.php?route=logbook_store" enctype="multipart/form-data">
<?php
$btns = '<div style="display:inline-flex; gap:8px;"><a href="' . BASE_URL . '/index.php?route=logbook" class="btn btn-secondary btn-sm">Batal</a><button type="submit" class="btn btn-primary btn-sm">Simpan</button></div>';
render_topbar('← Tambah Logbook', $btns);
?>


<div class="page-body">
  <div class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <!-- Left Column -->
      <div>
        <div class="form-group">
          <label class="form-label">Jenis Logbook <span class="required">*</span></label>
          <select name="template_id" class="form-control" required>
            <?php foreach ($templates as $t): ?>
              <option value="<?= $t['id'] ?>" <?= $t['id'] == $templateId ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal <span class="required">*</span></label>
          <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Jam <span class="required">*</span></label>
          <input type="time" class="form-control" value="<?= date('H:i') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Unit <span class="required">*</span></label>
          <select name="unit_id" class="form-control" required>
            <?php foreach ($units as $u): ?>
              <option value="<?= $u['id'] ?>" <?= $u['name'] === 'Rawat Jalan' ? 'selected' : '' ?>><?= htmlspecialchars($u['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kategori <span class="required">*</span></label>
          <select name="category" class="form-control" required>
            <option value="SIMRS" selected>SIMRS</option>
            <option value="Jaringan">Jaringan</option>
            <option value="Server">Server</option>
            <option value="Hardware">Hardware</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Prioritas <span class="required">*</span></label>
          <select name="priority" class="form-control" required>
            <option value="Tinggi" selected>🔴 Tinggi</option>
            <option value="Sedang">🟠 Sedang</option>
            <option value="Rendah">🟢 Rendah</option>
          </select>
        </div>
      </div>

      <!-- Right Column -->
      <div>
        <div class="form-group">
          <label class="form-label">Judul <span class="required">*</span></label>
          <input type="text" name="title" class="form-control" value="SIMRS tidak dapat login" placeholder="Judul masalah..." required>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi <span class="required">*</span></label>
          <textarea name="description" class="form-control" rows="3" required>Pengguna tidak dapat login ke SIMRS muncul pesan "User atau Password salah".</textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Tindakan</label>
          <textarea name="action_taken" class="form-control" rows="3">Reset password sementara dan cek service SIMRS.</textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Status <span class="required">*</span></label>
          <select name="status" class="form-control" required>
            <option value="Open">Open</option>
            <option value="Proses" selected>Proses</option>
            <option value="Selesai">Selesai</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Petugas <span class="required">*</span></label>
          <select name="assigned_to" class="form-control" required>
            <?php foreach ($users as $usr): ?>
              <option value="<?= $usr['id'] ?>"><?= htmlspecialchars($usr['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Lampiran</label>
          <input type="file" class="form-control">
        </div>
      </div>
    </div>
  </div>
</div>
</form>
