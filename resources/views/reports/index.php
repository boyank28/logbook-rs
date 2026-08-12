<?php
render_topbar('Laporan Logbook');
?>


<div class="page-body">
  <!-- Filter Bar -->
  <div class="filter-bar">
    <div class="filter-inputs">
      <input type="text" class="form-control" style="width: 200px;" value="01-08-2026 s/d 12-08-2026" readonly>
      <select class="form-control" style="width: 140px;">
        <option value="">Semua Jenis</option>
        <?php foreach ($templates as $t): ?>
          <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <select class="form-control" style="width: 140px;">
        <option value="">Semua Unit</option>
        <?php foreach ($units as $u): ?>
          <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary">Tampilkan</button>
    </div>
    <div>
      <a href="<?= BASE_URL ?>/index.php?route=export_excel" class="btn btn-success">📊 Export Excel</a>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="stats-grid" style="margin-bottom: 20px;">
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">TOTAL</div>
        <div class="stat-value">1.248</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">OPEN</div>
        <div class="stat-value" style="color: #ea580c;">27</div>
        <div class="stat-subtext">2,17%</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">PROSES</div>
        <div class="stat-value" style="color: #ca8a04;">36</div>
        <div class="stat-subtext">2,88%</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <div class="stat-label">SELESAI</div>
        <div class="stat-value" style="color: #16a34a;">1.185</div>
        <div class="stat-subtext">94,95%</div>
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
              <th>Total</th>
              <th>Open</th>
              <th>Proses</th>
              <th>Selesai</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($templates as $t): ?>
            <tr>
              <td><strong><?= htmlspecialchars($t['name']) ?></strong></td>
              <td>120</td>
              <td>5</td>
              <td>8</td>
              <td>107</td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div style="height: 250px;">
        <canvas id="rekapPieChart"></canvas>
      </div>
    </div>
  </div>
</div>
