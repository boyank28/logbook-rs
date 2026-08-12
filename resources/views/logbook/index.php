<?php
$titleName = '← ' . htmlspecialchars($selectedTemplate['name'] ?? 'Log Gangguan SIMRS');
render_topbar($titleName);
?>

<div class="page-body">
  <!-- Filter Bar -->
  <form method="GET" action="<?= BASE_URL ?>/index.php" class="filter-bar">
    <input type="hidden" name="route" value="logbook">
    <input type="hidden" name="template" value="<?= htmlspecialchars($filters['template_id']) ?>">

    <div class="filter-inputs">
      <input type="text" class="form-control" style="width: 200px;" value="01-08-2026 s/d 12-08-2026" readonly>
      
      <select name="unit" class="form-control" style="width: 140px;" onchange="this.form.submit()">
        <option value="">Semua Unit</option>
        <?php foreach ($units as $u): ?>
          <option value="<?= $u['id'] ?>" <?= $filters['unit_id'] == $u['id'] ? 'selected' : '' ?>><?= $u['name'] ?></option>
        <?php endforeach; ?>
      </select>

      <select name="status" class="form-control" style="width: 140px;" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="Open" <?= $filters['status'] === 'Open' ? 'selected' : '' ?>>Open</option>
        <option value="Proses" <?= $filters['status'] === 'Proses' ? 'selected' : '' ?>>Proses</option>
        <option value="Selesai" <?= $filters['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
      </select>

      <select name="priority" class="form-control" style="width: 140px;" onchange="this.form.submit()">
        <option value="">Semua Prioritas</option>
        <option value="Tinggi" <?= $filters['priority'] === 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
        <option value="Sedang" <?= $filters['priority'] === 'Sedang' ? 'selected' : '' ?>>Sedang</option>
        <option value="Rendah" <?= $filters['priority'] === 'Rendah' ? 'selected' : '' ?>>Rendah</option>
      </select>

      <input type="text" name="search" class="form-control" style="width: 220px;" placeholder="Cari judul atau deskripsi..." value="<?= htmlspecialchars($filters['search']) ?>">
    </div>

    <div style="display: flex; gap: 8px;">
      <button type="submit" class="btn btn-secondary">🔍 Cari</button>
      <a href="<?= BASE_URL ?>/index.php?route=export_excel" class="btn btn-secondary">📥 Export</a>
      <a href="<?= BASE_URL ?>/index.php?route=logbook_create&template=<?= $filters['template_id'] ?: 1 ?>" class="btn btn-primary">+ Tambah Logbook</a>
    </div>
  </form>

  <!-- Logbook List Table -->
  <div class="card">
    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Judul</th>
            <th>Unit</th>
            <th>Prioritas</th>
            <th>Status</th>
            <th>Petugas</th>
            <th style="text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = $offset + 1;
          if (empty($logbooks)): ?>
            <tr><td colspan="9" style="text-align:center; padding: 20px;">Tidak ada data logbook.</td></tr>
          <?php endif;
          foreach ($logbooks as $row): 
            $pClass = $row['priority'] === 'Tinggi' ? 'dot-red' : ($row['priority'] === 'Sedang' ? 'dot-orange' : 'dot-green');
            $stClass = strtolower($row['status']);
            $dateFormatted = date('d-m-Y', strtotime($row['created_at']));
            $timeFormatted = date('H:i', strtotime($row['created_at']));
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $dateFormatted ?></td>
            <td><?= $timeFormatted ?></td>
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
            <td><?= htmlspecialchars($row['assigned_name'] ?? 'Budi') ?></td>
            <td style="text-align: center;">
              <a href="<?= BASE_URL ?>/index.php?route=logbook_detail&id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm" title="Detail">👁️</a>
              <a href="<?= BASE_URL ?>/index.php?route=logbook_detail&id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm" title="Edit">✏️</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Dynamic Pagination Footer -->
    <?php
    $queryParams = $_GET;
    function pageUrl($page, $queryParams) {
        $params = array_merge($queryParams, ['page' => $page]);
        return BASE_URL . '/index.php?' . http_build_query($params);
    }
    ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; font-size: 13px; color: #64748b;">
      <div>
        Menampilkan <strong><?= count($logbooks) ?></strong> dari <strong><?= $totalItems ?></strong> data logbook
      </div>
      <div style="display: flex; gap: 6px; align-items: center;">
        <?php if ($currentPage > 1): ?>
          <a href="<?= pageUrl($currentPage - 1, $queryParams) ?>" class="btn btn-secondary btn-sm">&lt;</a>
        <?php else: ?>
          <button class="btn btn-secondary btn-sm" disabled style="opacity: 0.5; cursor: not-allowed;">&lt;</button>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="<?= pageUrl($p, $queryParams) ?>" class="btn <?= $p === $currentPage ? 'btn-primary' : 'btn-secondary' ?> btn-sm"><?= $p ?></a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
          <a href="<?= pageUrl($currentPage + 1, $queryParams) ?>" class="btn btn-secondary btn-sm">&gt;</a>
        <?php else: ?>
          <button class="btn btn-secondary btn-sm" disabled style="opacity: 0.5; cursor: not-allowed;">&gt;</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
