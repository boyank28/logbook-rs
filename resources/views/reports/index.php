<?php
render_topbar('Laporan Logbook');
$summary = $reportData['summary'] ?? ['total' => 0, 'open' => 0, 'proses' => 0, 'selesai' => 0];
$total = max(1, $summary['total']);
$openPct = number_format(($summary['open'] / $total) * 100, 1);
$prosesPct = number_format(($summary['proses'] / $total) * 100, 1);
$selesaiPct = number_format(($summary['selesai'] / $total) * 100, 1);

$exportQuery = http_build_query([
  'route' => 'export_excel',
  'start_date' => $filters['start_date'],
  'end_date' => $filters['end_date'],
  'template_id' => $filters['template_id'],
  'unit_id' => $filters['unit_id']
]);
?>

<div class="page-body">
  <!-- Filter Bar -->
  <form method="GET" action="<?= BASE_URL ?>/index.php" class="filter-bar">
    <input type="hidden" name="route" value="reports">
    <div class="filter-inputs" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
      <input type="date" name="start_date" class="form-control" style="width: 150px;" value="<?= htmlspecialchars($filters['start_date']) ?>">
      <span style="color:#64748b; font-size:12px;">s/d</span>
      <input type="date" name="end_date" class="form-control" style="width: 150px;" value="<?= htmlspecialchars($filters['end_date']) ?>">
      
      <select name="template_id" class="form-control" style="width: 160px;">
        <option value="">Semua Jenis Log</option>
        <?php foreach ($templates as $t): ?>
          <option value="<?= $t['id'] ?>" <?= $filters['template_id'] == $t['id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
      
      <select name="unit_id" class="form-control" style="width: 150px;">
        <option value="">Semua Unit</option>
        <?php foreach ($units as $u): ?>
          <option value="<?= $u['id'] ?>" <?= $filters['unit_id'] == $u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['name']) ?></option>
        <?php endforeach; ?>
      </select>
      
      <button type="submit" class="btn btn-primary">🔍 Tampilkan</button>
      <a href="<?= BASE_URL ?>/index.php?route=reports" class="btn btn-secondary" style="font-size:12px;">Reset</a>
    </div>
    <div>
      <a href="<?= BASE_URL ?>/index.php?<?= $exportQuery ?>" class="btn btn-success">📊 Export Excel (CSV)</a>
    </div>
  </form>

  <!-- Summary Cards -->
  <div class="stats-grid" style="margin-bottom: 20px;">
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">TOTAL LOGBOOK</div>
        <div class="stat-value"><?= number_format($summary['total']) ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">OPEN</div>
        <div class="stat-value" style="color: #ea580c;"><?= number_format($summary['open']) ?></div>
        <div class="stat-subtext"><?= $openPct ?>%</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">PROSES</div>
        <div class="stat-value" style="color: #ca8a04;"><?= number_format($summary['proses']) ?></div>
        <div class="stat-subtext"><?= $prosesPct ?>%</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">SELESAI</div>
        <div class="stat-value" style="color: #16a34a;"><?= number_format($summary['selesai']) ?></div>
        <div class="stat-subtext"><?= $selesaiPct ?>%</div>
      </div>
    </div>
  </div>

  <!-- Rekap Grid -->
  <div class="dashboard-grid-2">
    <div class="card">
      <h4 style="font-size: 14px; font-weight:700; margin-bottom: 14px;">Rekap per Jenis Logbook</h4>
      <div class="table-responsive">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Jenis Logbook</th>
              <th style="text-align:center;">Total</th>
              <th style="text-align:center;">Open</th>
              <th style="text-align:center;">Proses</th>
              <th style="text-align:center;">Selesai</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($reportData['template_breakdown'])): ?>
              <tr>
                <td colspan="5" style="text-align:center; color:#64748b;">Tidak ada data logbook pada periode/filter ini.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($reportData['template_breakdown'] as $tb): ?>
              <tr>
                <td><strong><?= htmlspecialchars($tb['template_name']) ?></strong></td>
                <td style="text-align:center; font-weight:700;"><?= number_format($tb['total']) ?></td>
                <td style="text-align:center; color:#ea580c;"><?= number_format($tb['open_cnt']) ?></td>
                <td style="text-align:center; color:#ca8a04;"><?= number_format($tb['proses_cnt']) ?></td>
                <td style="text-align:center; color:#16a34a; font-weight:600;"><?= number_format($tb['selesai_cnt']) ?></td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card" style="display:flex; flex-direction:column; justify-content:center; align-items:center; min-height: 300px;">
      <h4 style="font-size: 14px; font-weight:700; margin-bottom: 14px; width:100%;">Distribusi Kategori Insiden</h4>
      <div style="height: 220px; width: 100%; position:relative;">
        <canvas id="rekapPieChart"></canvas>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('rekapPieChart');
  if (!ctx) return;

  const catData = <?= json_encode($reportData['category_breakdown'] ?? []) ?>;
  const labels = catData.map(item => item.category || 'Lainnya');
  const values = catData.map(item => parseInt(item.count || 0));

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels.length ? labels : ['Belum Ada Data'],
      datasets: [{
        data: values.length ? values : [1],
        backgroundColor: ['#2563eb', '#16a34a', '#ea580c', '#a855f7', '#06b6d4', '#f59e0b']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right'
        }
      }
    }
  });
});
</script>
