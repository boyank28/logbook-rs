<?php
$authUser = auth_user() ?? ['name' => 'Mas Mulyadi, S.Kep', 'role_name' => 'Petugas Unit', 'email' => 'masmul@rs.com', 'unit_name' => 'Rawat Jalan'];
$cleanAuthName = trim(preg_replace('/\s*\(.*?\)/', '', $authUser['name'] ?? 'Mas Mulyadi, S.Kep'));

render_topbar('Dashboard');
?>

<div class="page-body">
  <?php if (isset($_GET['access_denied'])): ?>
    <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 20px;">🛡️</span>
      <div>
        <strong>Akses Ditolak!</strong>
        <div style="font-size: 12px; font-weight: normal; margin-top: 2px;">Akun Anda (<?= htmlspecialchars($authUser['role_name']) ?>) tidak memiliki hak akses untuk membuka halaman tersebut.</div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Top Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrapper stat-icon-blue">📄</div>
      <div class="stat-info">
        <div class="stat-label">TOTAL LOGBOOK</div>
        <div class="stat-value"><?= number_format($stats['total'] ?? 1248, 0, ',', '.') ?></div>
        <div class="stat-subtext">Semua Logbook</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrapper stat-icon-orange">📥</div>
      <div class="stat-info">
        <div class="stat-label">OPEN</div>
        <div class="stat-value"><?= $stats['open'] ?? 27 ?></div>
        <div class="stat-subtext">Belum Ditangani</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrapper stat-icon-green">⏳</div>
      <div class="stat-info">
        <div class="stat-label">PROSES</div>
        <div class="stat-value"><?= $stats['proses'] ?? 36 ?></div>
        <div class="stat-subtext">Sedang Dikerjakan</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrapper stat-icon-purple">✅</div>
      <div class="stat-info">
        <div class="stat-label">SELESAI</div>
        <div class="stat-value"><?= number_format($stats['selesai'] ?? 1185, 0, ',', '.') ?></div>
        <div class="stat-subtext">Selesai</div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="dashboard-grid-2">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Logbook 7 Hari Terakhir</div>
      </div>
      <div style="height: 250px;">
        <canvas id="lineChart7Days"></canvas>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">Berdasarkan Kategori</div>
      </div>
      <div style="height: 250px; position: relative;">
        <canvas id="doughnutCategory"></canvas>
      </div>
    </div>
  </div>

  <!-- Logbook Terbaru Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">Logbook Terbaru</div>
      <a href="<?= BASE_URL ?>/index.php?route=logbook" class="btn btn-primary btn-sm">Lihat Semua</a>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Jenis Logbook</th>
            <th>Judul</th>
            <th>Unit</th>
            <th>Prioritas</th>
            <th>Status</th>
            <th>Petugas</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1;
          foreach ($recentLogbooks as $row): 
            $pClass = $row['priority'] === 'Tinggi' ? 'dot-red' : ($row['priority'] === 'Sedang' ? 'dot-orange' : 'dot-green');
            $stClass = strtolower($row['status']);
            $dateFormatted = date('d-m-Y', strtotime($row['created_at']));
            $timeFormatted = date('H:i', strtotime($row['created_at']));
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $dateFormatted ?></td>
            <td><?= $timeFormatted ?></td>
            <td><?= htmlspecialchars($row['template_name'] ?? 'Log Gangguan') ?></td>
            <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
            <td><?= htmlspecialchars($row['unit_name'] ?? 'General') ?></td>
            <td>
              <span class="priority-dot">
                <span class="dot <?= $pClass ?>"></span> <?= $row['priority'] ?>
              </span>
            </td>
            <td>
              <span class="badge-status <?= $stClass ?>"><?= $row['status'] ?></span>
            </td>
            <td><?= htmlspecialchars($row['assigned_name'] ?? $cleanAuthName) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
