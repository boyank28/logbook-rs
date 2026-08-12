<?php
$titleLink = '<a href="' . BASE_URL . '/index.php?route=logbook" style="text-decoration:none; color:inherit;">← Detail Logbook</a>';
$detailBtns = '<div style="display:inline-flex; gap:8px;"><a href="' . BASE_URL . '/index.php?route=logbook_update_status&id=' . $logbook['id'] . '&status=Selesai" class="btn btn-success btn-sm">✅ Selesai</a><button class="btn btn-secondary btn-sm">Aksi ▾</button></div>';
render_topbar($titleLink, $detailBtns);
?>


<div class="page-body">
  <div class="card">
    <!-- Tabs Header -->
    <div class="nav-tabs">
      <div class="nav-tab active" onclick="switchTab('detail', this)">Detail</div>
      <div class="nav-tab" onclick="switchTab('komentar', this)">Komentar (2)</div>
      <div class="nav-tab" onclick="switchTab('lampiran', this)">Lampiran (1)</div>
      <div class="nav-tab" onclick="switchTab('riwayat', this)">Riwayat</div>
    </div>

    <!-- Tab 1: Detail -->
    <div id="tab-detail" class="tab-pane active">
      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Left Info Panel -->
        <div>
          <table class="custom-table" style="border: none;">
            <tr>
              <td style="width: 150px; font-weight:600; color:#64748b;">Jenis Logbook</td>
              <td>: <?= htmlspecialchars($logbook['template_name'] ?? 'Log Gangguan SIMRS') ?></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Tanggal / Jam</td>
              <td>: <?= date('d-m-Y H:i', strtotime($logbook['created_at'])) ?></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Unit</td>
              <td>: <?= htmlspecialchars($logbook['unit_name'] ?? 'Rawat Jalan') ?></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Kategori</td>
              <td>: <?= htmlspecialchars($logbook['category']) ?></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Prioritas</td>
              <td>
                : <span class="priority-dot">
                  <span class="dot <?= $logbook['priority'] === 'Tinggi' ? 'dot-red' : 'dot-orange' ?>"></span> 
                  <?= $logbook['priority'] ?>
                </span>
              </td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Judul</td>
              <td>: <strong><?= htmlspecialchars($logbook['title']) ?></strong></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b; vertical-align: top;">Deskripsi</td>
              <td>: <?= nl2br(htmlspecialchars($logbook['description'])) ?></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b; vertical-align: top;">Tindakan</td>
              <td>: <?= nl2br(htmlspecialchars($logbook['action_taken'] ?? '-')) ?></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Status</td>
              <td>
                : <span class="badge-status <?= strtolower($logbook['status']) ?>"><?= $logbook['status'] ?></span>
              </td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Petugas</td>
              <td>: <?= htmlspecialchars($logbook['assigned_name'] ?? 'Budi (IT Support)') ?></td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Dibuat Oleh</td>
              <td>: <?= htmlspecialchars($logbook['creator_name'] ?? 'Siti') ?> (<?= date('d-m-Y H:i', strtotime($logbook['created_at'])) ?>)</td>
            </tr>
            <tr>
              <td style="font-weight:600; color:#64748b;">Diupdate Oleh</td>
              <td>: <?= htmlspecialchars($logbook['assigned_name'] ?? 'Budi') ?> (<?= date('d-m-Y H:i', strtotime($logbook['updated_at'])) ?>)</td>
            </tr>
          </table>
        </div>

        <!-- Right Timeline Panel -->
        <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
          <h4 style="font-size: 13px; font-weight: 700; margin-bottom: 12px;">Timeline / Riwayat</h4>
          
          <div class="timeline">
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-content">
                <div class="timeline-time">12-08-2026 10:22</div>
                <div class="timeline-title">Dibuat oleh Siti</div>
                <div style="color: var(--text-muted);">Status: <span style="color:#ea580c">Open</span></div>
              </div>
            </div>

            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-content">
                <div class="timeline-time">12-08-2026 10:30</div>
                <div class="timeline-title">Diambil oleh Budi</div>
                <div style="color: var(--text-muted);">Status: <span style="color:#ca8a04">Proses</span></div>
              </div>
            </div>

            <div class="timeline-item">
              <div class="timeline-dot" style="background:#22c55e;"></div>
              <div class="timeline-content">
                <div class="timeline-time">12-08-2026 11:15</div>
                <div class="timeline-title">Diselesaikan oleh Budi</div>
                <div style="color: var(--text-muted);">Status: <span style="color:#16a34a">Selesai</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 2: Komentar -->
    <div id="tab-komentar" class="tab-pane" style="display: none;">
      <h4 style="font-size: 14px; font-weight:700; margin-bottom: 16px;">Diskusi & Komentar</h4>
      
      <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 20px;">
        <div style="background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid var(--border-color);">
          <div style="display:flex; justify-content:space-between; margin-bottom: 6px;">
            <strong>Siti (Petugas RJ)</strong>
            <span style="font-size: 11px; color: var(--text-muted);">12-08-2026 10:25</span>
          </div>
          <p style="font-size: 13px; color: #334155;">Mohon bantuannya mas Budi, antrean IGD & Rawat Jalan cukup padat pagi ini.</p>
        </div>

        <div style="background: #eff6ff; padding: 14px; border-radius: 8px; border: 1px solid #bfdbfe;">
          <div style="display:flex; justify-content:space-between; margin-bottom: 6px;">
            <strong>Budi (IT Support)</strong>
            <span style="font-size: 11px; color: var(--text-muted);">12-08-2026 10:32</span>
          </div>
          <p style="font-size: 13px; color: #1e3a8a;">Siap bu Siti, sedang diperiksa koneksi service authentication SIMRS.</p>
        </div>
      </div>

      <!-- Add Comment Form -->
      <div class="form-group">
        <label class="form-label">Tambah Komentar</label>
        <textarea class="form-control" rows="3" placeholder="Tulis komentar atau update penanganan..."></textarea>
        <button class="btn btn-primary btn-sm" style="margin-top: 10px;" onclick="alert('Komentar berhasil ditambahkan!')">💬 Kirim Komentar</button>
      </div>
    </div>

    <!-- Tab 3: Lampiran -->
    <div id="tab-lampiran" class="tab-pane" style="display: none;">
      <h4 style="font-size: 14px; font-weight:700; margin-bottom: 16px;">Berkas Lampiran</h4>
      
      <div class="table-responsive">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Nama File</th>
              <th>Ukuran</th>
              <th>Waktu Unggah</th>
              <th style="text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>📄 <strong>tangkapan_layar_error_login.png</strong></td>
              <td>425 KB</td>
              <td>12-08-2026 10:22</td>
              <td style="text-align: center;">
                <button class="btn btn-secondary btn-sm" onclick="alert('Mengunduh file...')">📥 Unduh</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div style="margin-top: 16px;">
        <label class="form-label">Unggah Lampiran Baru</label>
        <div style="display: flex; gap: 10px;">
          <input type="file" class="form-control" style="width: 300px;">
          <button class="btn btn-primary btn-sm" onclick="alert('File berhasil diunggah!')">Upload</button>
        </div>
      </div>
    </div>

    <!-- Tab 4: Riwayat -->
    <div id="tab-riwayat" class="tab-pane" style="display: none;">
      <h4 style="font-size: 14px; font-weight:700; margin-bottom: 16px;">Riwayat Perubahan Logbook</h4>
      
      <div class="table-responsive">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Waktu</th>
              <th>User</th>
              <th>Aksi</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($auditLogs as $log): ?>
            <tr>
              <td><?= date('d-m-Y H:i', strtotime($log['created_at'])) ?></td>
              <td><strong><?= htmlspecialchars($log['user_name'] ?? 'System') ?></strong></td>
              <td><span class="badge-status proses"><?= htmlspecialchars($log['action']) ?></span></td>
              <td><?= htmlspecialchars($log['note'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function switchTab(tabId, el) {
  // Hide all tab panes
  document.querySelectorAll('.tab-pane').forEach(function(pane) {
    pane.style.display = 'none';
  });
  
  // Remove active class from all tabs
  document.querySelectorAll('.nav-tab').forEach(function(tab) {
    tab.classList.remove('active');
  });

  // Show target tab pane
  const targetPane = document.getElementById('tab-' + tabId);
  if (targetPane) {
    targetPane.style.display = 'block';
  }

  // Set active class on clicked tab
  if (el) {
    el.classList.add('active');
  }
}
</script>
