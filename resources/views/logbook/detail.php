<?php
$titleLink = '<a href="' . BASE_URL . '/index.php?route=logbook" style="text-decoration:none; color:inherit;">← Detail Logbook</a>';
$detailBtns = '<div style="display:inline-flex; gap:8px;"><a href="' . BASE_URL . '/index.php?route=logbook_update_status&id=' . $logbook['id'] . '&status=Selesai" class="btn btn-success btn-sm">✅ Selesai</a><button class="btn btn-secondary btn-sm">Aksi ▾</button></div>';
render_topbar($titleLink, $detailBtns);
?>


<div class="page-body">
  <div class="card">
<?php
require_once __DIR__ . '/../../../app/Models/Attachment.php';
require_once __DIR__ . '/../../../app/Models/Comment.php';
$attachments = Attachment::getByLogbook($logbook['id']);
$comments = Comment::getByLogbook($logbook['id']);
?>
    <!-- Tabs Header -->
    <div class="nav-tabs">
      <div class="nav-tab active" id="tab-btn-detail" onclick="switchTab('detail', this)">Detail</div>
      <div class="nav-tab" id="tab-btn-komentar" onclick="switchTab('komentar', this)">Komentar (<?= count($comments) ?>)</div>
      <div class="nav-tab" id="tab-btn-lampiran" onclick="switchTab('lampiran', this)">Lampiran (<?= count($attachments) ?>)</div>
      <div class="nav-tab" id="tab-btn-riwayat" onclick="switchTab('riwayat', this)">Riwayat</div>
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
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
        <h4 style="font-size: 15px; font-weight:700; color:#0f172a; margin:0; display:flex; align-items:center; gap:8px;">
          💬 Diskusi & Komentar
          <span style="font-size: 12px; font-weight: 600; background:#e0f2fe; color:#0284c7; padding: 2px 8px; border-radius: 12px;"><?= count($comments) ?></span>
        </h4>
      </div>
      
      <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
        <?php if (empty($comments)): ?>
          <div style="background: #f8fafc; padding: 24px; text-align:center; color: #64748b; border-radius: 12px; border: 1px dashed #cbd5e1; font-size:13px;">
            💬 Belum ada diskusi pada logbook ini. Tulis komentar pertama untuk memulai diskusi.
          </div>
        <?php else: ?>
          <?php foreach ($comments as $c): 
            $rawName = $c['user_name'] ?? 'User';
            $cleanName = trim(preg_replace('/\s*\(.*?\)/', '', $rawName));
            $initial = strtoupper(substr($cleanName, 0, 1));
            $roleName = $c['role_name'] ?? 'Petugas';
            $isItRole = strpos(strtolower($roleName), 'admin') !== false || strpos(strtolower($roleName), 'it') !== false;
            $badgeBg = $isItRole ? '#eff6ff' : '#f0fdf4';
            $badgeText = $isItRole ? '#2563eb' : '#16a34a';
            $badgeBorder = $isItRole ? '#bfdbfe' : '#bbf7d0';
            $avatarBg = $isItRole ? 'linear-gradient(135deg, #2563eb, #1d4ed8)' : 'linear-gradient(135deg, #0d9488, #0f766e)';
          ?>
            <div style="display:flex; gap: 14px; background: #ffffff; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
              <div style="width: 38px; height: 38px; border-radius: 50%; background: <?= $avatarBg ?>; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;">
                <?= htmlspecialchars($initial) ?>
              </div>
              <div style="flex: 1;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 6px;">
                  <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-weight: 700; font-size: 13px; color: #0f172a;"><?= htmlspecialchars($cleanName) ?></span>
                    <span style="font-size: 10px; font-weight:600; padding: 2px 8px; border-radius: 10px; background: <?= $badgeBg ?>; color: <?= $badgeText ?>; border: 1px solid <?= $badgeBorder ?>;">
                      <?= htmlspecialchars($roleName) ?>
                    </span>
                  </div>
                  <span style="font-size: 11px; color: #94a3b8; display:flex; align-items:center; gap:4px;">
                    🕒 <?= date('d M Y, H:i', strtotime($c['created_at'])) ?>
                  </span>
                </div>
                <div style="font-size: 13px; color: #334155; line-height: 1.5; white-space: pre-wrap; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border-left: 3px solid <?= $badgeText ?>;">
                  <?= htmlspecialchars($c['comment']) ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Add Comment Form Card -->
      <form method="POST" action="<?= BASE_URL ?>/index.php?route=logbook_add_comment" style="background: #f8fafc; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <input type="hidden" name="logbook_id" value="<?= $logbook['id'] ?>">
        <div class="form-group" style="margin: 0;">
          <label class="form-label" style="font-weight:700; color: #0f172a; margin-bottom: 8px; display:block;">
            ✏️ Tambah Komentar / Update Penanganan
          </label>
          <textarea name="comment" class="form-control" rows="3" required placeholder="Tulis catatan, tanggapan, atau perkembangan penanganan logbook..." style="background: #ffffff; border-radius: 8px; border: 1px solid #cbd5e1; padding: 12px; font-size: 13px;"></textarea>
          <div style="display:flex; justify-content:flex-end; margin-top: 12px;">
            <button type="submit" class="btn btn-primary" style="padding: 8px 18px; font-weight:600; border-radius: 8px;">
              💬 Kirim Komentar
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Tab 3: Lampiran -->
    <div id="tab-lampiran" class="tab-pane" style="display: none;">
      <h4 style="font-size: 14px; font-weight:700; margin-bottom: 16px;">Berkas & Foto Lampiran</h4>
      
      <div class="table-responsive">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Preview / File</th>
              <th>Nama File</th>
              <th>Ukuran</th>
              <th>Waktu Unggah</th>
              <th style="text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($attachments)): ?>
              <tr>
                <td colspan="5" style="text-align: center; color: #64748b; padding: 20px;">Belum ada lampiran berkas atau foto.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($attachments as $att): 
                $ext = strtolower(pathinfo($att['file_name'], PATHINFO_EXTENSION));
                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) || ($att['file_type'] ?? '') === 'image';
                $fileUrl = BASE_URL . '/' . htmlspecialchars($att['file_path']);
                $sizeKb = round(($att['file_size'] ?? 0) / 1024, 1);
              ?>
              <tr>
                <td style="width: 80px;">
                  <?php if ($isImg): ?>
                    <a href="<?= $fileUrl ?>" target="_blank">
                      <img src="<?= $fileUrl ?>" alt="<?= htmlspecialchars($att['file_name']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                    </a>
                  <?php else: ?>
                    <span style="font-size: 24px;">📄</span>
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?= htmlspecialchars($att['file_name']) ?></strong>
                  <?php if ($isImg): ?>
                    <div style="font-size: 11px; color: #2563eb;">🖼️ Foto Lampiran</div>
                  <?php endif; ?>
                </td>
                <td><?= $sizeKb > 0 ? $sizeKb . ' KB' : '-' ?></td>
                <td><?= !empty($att['created_at']) ? date('d-m-Y H:i', strtotime($att['created_at'])) : '-' ?></td>
                <td style="text-align: center;">
                  <a href="<?= $fileUrl ?>" download="<?= htmlspecialchars($att['file_name']) ?>" target="_blank" class="btn btn-secondary btn-sm">📥 Unduh / Lihat</a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <form method="POST" action="<?= BASE_URL ?>/index.php?route=logbook_upload_attachment" enctype="multipart/form-data" style="margin-top: 20px; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
        <input type="hidden" name="logbook_id" value="<?= $logbook['id'] ?>">
        <label class="form-label" style="font-weight:700;">Unggah Lampiran / Foto Baru</label>
        <div style="display: flex; gap: 10px; margin-top: 6px;">
          <input type="file" name="attachment" class="form-control" accept="image/*,.pdf,.doc,.docx" required style="width: 320px;">
          <button type="submit" class="btn btn-primary btn-sm">📤 Unggah Berkas</button>
        </div>
      </form>
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

<?php if (isset($_GET['tab'])): ?>
document.addEventListener('DOMContentLoaded', function() {
  const tabName = <?= json_encode($_GET['tab']) ?>;
  const btn = document.getElementById('tab-btn-' + tabName);
  if (btn) {
    switchTab(tabName, btn);
  }
});
<?php endif; ?>
</script>
