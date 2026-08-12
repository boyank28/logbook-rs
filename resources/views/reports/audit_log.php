<?php
render_topbar('Audit Log System');
?>


<div class="page-body">
  <div class="card">
    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Waktu</th>
            <th>No Ticket</th>
            <th>User</th>
            <th>Aksi</th>
            <th>Catatan</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          if (empty($logs)): ?>
            <tr><td colspan="5" style="text-align: center; padding: 20px;">Belum ada riwayat audit log.</td></tr>
          <?php endif;
          foreach ($logs as $log): 
            $cleanUser = trim(preg_replace('/\s*\(.*?\)/', '', $log['user_name'] ?? 'System'));
            $badgeClass = 'proses';
            if (stristr($log['action'], 'tambah') || stristr($log['action'], 'dibuat')) $badgeClass = 'selesai';
            elseif (stristr($log['action'], 'hapus')) $badgeClass = 'open';
          ?>
          <tr>
            <td><?= date('d-m-Y H:i', strtotime($log['created_at'])) ?></td>
            <td><strong><?= htmlspecialchars($log['ticket_number'] ?? '-') ?></strong></td>
            <td><?= htmlspecialchars($cleanUser) ?></td>
            <td><span class="badge-status <?= $badgeClass ?>"><?= htmlspecialchars($log['action']) ?></span></td>
            <td><?= htmlspecialchars($log['note'] ?? '-') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
