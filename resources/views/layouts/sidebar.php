<?php
if (!class_exists('LogbookTemplate')) {
    require_once __DIR__ . '/../../../app/Models/User.php';
}

$currentRoute = $_GET['route'] ?? 'dashboard';
$appSettings = get_app_settings();
$dynamicTemplates = LogbookTemplate::getAll();

$authUser = auth_user();
$roleId = (int)($authUser['role_id'] ?? 3); // 1 = Super Admin, 2 = IT Support, 3 = Petugas Unit
$isSuperAdmin = ($roleId === 1 || strtolower($authUser['role_name'] ?? '') === 'super admin');
$isITSupport = ($roleId === 2 || strtolower($authUser['role_name'] ?? '') === 'it support');
$isAdminOrIT = ($isSuperAdmin || $isITSupport);

$colorClass = '';
$colorSetting = $appSettings['sidebar_color'] ?? '#0f172a';
if ($colorSetting === '#0284c7') {
    $colorClass = 'theme-ocean-blue';
} elseif ($colorSetting === '#059669') {
    $colorClass = 'theme-emerald';
} elseif ($colorSetting === '#7c3aed') {
    $colorClass = 'theme-purple';
}
?>
<aside class="sidebar <?= $colorClass ?>">
  <div class="sidebar-header">
    <div class="sidebar-brand-icon"><?= htmlspecialchars($appSettings['app_icon'] ?? '+') ?></div>
    <div class="sidebar-brand-title">
      <?= htmlspecialchars($appSettings['app_name'] ?? 'LOGBOOK DINAMIS') ?><br>
      <span style="opacity: 0.8; font-weight:normal;"><?= htmlspecialchars($appSettings['app_subtitle'] ?? 'RUMAH SAKIT') ?></span>
    </div>
  </div>
  <nav class="sidebar-nav">
    <a href="<?= BASE_URL ?>/index.php?route=dashboard" class="nav-link <?= $currentRoute === 'dashboard' ? 'active' : '' ?>">
      <span class="nav-icon">📊</span> Dashboard
    </a>

    <div class="nav-section-label">LOGBOOK</div>
    <?php 
    foreach ($dynamicTemplates as $st): 
      $tName = $st['name'];
      $icon = '📋';
      if (stristr($tName, 'jaringan')) $icon = '🌐';
      elseif (stristr($tName, 'server')) $icon = '🖥️';
      elseif (stristr($tName, 'maintenance')) $icon = '⚙️';
      elseif (stristr($tName, 'insiden')) $icon = '🚨';
      elseif (stristr($tName, 'komkep')) $icon = '🩺';
      elseif (stristr($tName, 'farmasi')) $icon = '💊';

      $isActive = ($currentRoute === 'logbook' && ($_GET['template']??'') == $st['id']);
    ?>
      <a href="<?= BASE_URL ?>/index.php?route=logbook&template=<?= $st['id'] ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
        <span class="nav-icon"><?= $icon ?></span> <?= htmlspecialchars($tName) ?>
      </a>
    <?php endforeach; ?>

    <a href="<?= BASE_URL ?>/index.php?route=logbook" class="nav-link <?= ($currentRoute === 'logbook' && empty($_GET['template'])) ? 'active' : '' ?>">
      <span class="nav-icon">📝</span> Logbook Custom (Semua)
    </a>

    <?php if ($isAdminOrIT): ?>
    <div class="nav-section-label">MASTER</div>
    <a href="<?= BASE_URL ?>/index.php?route=master_units" class="nav-link <?= $currentRoute === 'master_units' ? 'active' : '' ?>">
      <span class="nav-icon">🏢</span> Unit
    </a>
    <a href="<?= BASE_URL ?>/index.php?route=master_categories" class="nav-link <?= $currentRoute === 'master_categories' ? 'active' : '' ?>">
      <span class="nav-icon">🏷️</span> Kategori
    </a>
    <a href="<?= BASE_URL ?>/index.php?route=master_priorities" class="nav-link <?= $currentRoute === 'master_priorities' ? 'active' : '' ?>">
      <span class="nav-icon">🎯</span> Prioritas
    </a>
    <?php if ($isSuperAdmin): ?>
    <a href="<?= BASE_URL ?>/index.php?route=master_users" class="nav-link <?= $currentRoute === 'master_users' ? 'active' : '' ?>">
      <span class="nav-icon">👤</span> User
    </a>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/index.php?route=templates" class="nav-link <?= $currentRoute === 'templates' || $currentRoute === 'template_edit' ? 'active' : '' ?>">
      <span class="nav-icon">📐</span> Template Field
    </a>
    <?php endif; ?>

    <div class="nav-section-label">LAPORAN & SISTEM</div>
    <?php if ($isAdminOrIT): ?>
    <a href="<?= BASE_URL ?>/index.php?route=audit_log" class="nav-link <?= $currentRoute === 'audit_log' ? 'active' : '' ?>">
      <span class="nav-icon">🔒</span> AUDIT LOG
    </a>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/index.php?route=reports" class="nav-link <?= $currentRoute === 'reports' ? 'active' : '' ?>">
      <span class="nav-icon">📈</span> LAPORAN
    </a>
    <?php if ($isSuperAdmin): ?>
    <a href="<?= BASE_URL ?>/index.php?route=settings" class="nav-link <?= $currentRoute === 'settings' ? 'active' : '' ?>">
      <span class="nav-icon">⚙️</span> PENGATURAN
    </a>
    <?php endif; ?>

    <div style="margin-top: 20px; padding: 0 4px;">
      <a href="https://saweria.co/boyank28" target="_blank" rel="noopener noreferrer" class="nav-link" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); color: white; justify-content: center; font-weight: 700; border-radius: 8px;">
        <span class="nav-icon">☕</span> Support Saweria
      </a>
    </div>
  </nav>
</aside>
<div class="main-content">
