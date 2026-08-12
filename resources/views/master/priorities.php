<?php
render_topbar('Master Prioritas');
?>

<div class="page-body">
  <div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Daftar Tingkat Prioritas & SLA</h3>
      <button class="btn btn-primary btn-sm" onclick="openModal('modalCreatePriority')">+ Prioritas Baru</button>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Nama Prioritas</th>
            <th>Warna Badge</th>
            <th>SLA Response</th>
            <th style="text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Tinggi</strong></td>
            <td><span class="priority-dot"><span class="dot dot-red"></span> Merah</span></td>
            <td>15 Menit</td>
            <td style="text-align: center;">
              <button class="btn btn-secondary btn-sm" onclick="alert('Edit SLA Prioritas Tinggi')">✏️ Edit</button>
            </td>
          </tr>
          <tr>
            <td><strong>Sedang</strong></td>
            <td><span class="priority-dot"><span class="dot dot-orange"></span> Oranye</span></td>
            <td>1 Jam</td>
            <td style="text-align: center;">
              <button class="btn btn-secondary btn-sm" onclick="alert('Edit SLA Prioritas Sedang')">✏️ Edit</button>
            </td>
          </tr>
          <tr>
            <td><strong>Rendah</strong></td>
            <td><span class="priority-dot"><span class="dot dot-green"></span> Hijau</span></td>
            <td>24 Jam</td>
            <td style="text-align: center;">
              <button class="btn btn-secondary btn-sm" onclick="alert('Edit SLA Prioritas Rendah')">✏️ Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Create Priority -->
<div id="modalCreatePriority" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
  <div style="background:white; width:420px; border-radius:12px; padding:24px; box-shadow: 0 20px 25px rgba(0,0,0,0.2);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight:700;">Tambah Prioritas Baru</h3>
      <button onclick="closeModal('modalCreatePriority')" style="background:none; border:none; font-size:18px; cursor:pointer;">✕</button>
    </div>
    
    <form onsubmit="event.preventDefault(); alert('Prioritas baru berhasil disimpan!'); closeModal('modalCreatePriority');">
      <div class="form-group">
        <label class="form-label">Nama Prioritas <span class="required">*</span></label>
        <input type="text" class="form-control" placeholder="Contoh: Darurat" required>
      </div>

      <div class="form-group">
        <label class="form-label">Target SLA</label>
        <input type="text" class="form-control" placeholder="Contoh: 5 Menit" required>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreatePriority')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Prioritas</button>
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
