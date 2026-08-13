<?php
require_once __DIR__ . '/../../config/app.php';

function auth_check(): bool {
    return isset($_SESSION['user']);
}

function auth_user(): ?array {
    if (!isset($_SESSION['user'])) {
        return null;
    }
    $sessionUser = $_SESSION['user'];
    $email = $sessionUser['email'] ?? 'masmul@rs.com';
    
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT u.*, r.name as role_name, un.name as unit_name 
                               FROM users u 
                               LEFT JOIN roles r ON u.role_id = r.id 
                               LEFT JOIN units un ON u.unit_id = un.id 
                               WHERE u.email = ? OR u.id = ? OR u.email LIKE ?");
        $stmt->execute([$email, $sessionUser['id'] ?? 0, 'masmul%']);
        $dbUser = $stmt->fetch();

        if ($dbUser) {
            $cleanName = trim(preg_replace('/\s*\(.*?\)/', '', $dbUser['name']));
            return [
                'id' => $dbUser['id'],
                'name' => $cleanName ?: 'Mas Mulyadi, S.Kep',
                'full_name' => $dbUser['name'],
                'email' => $dbUser['email'],
                'role_id' => (int)$dbUser['role_id'],
                'role_name' => $dbUser['role_name'] ?? 'Petugas Unit',
                'unit_id' => $dbUser['unit_id'],
                'unit_name' => $dbUser['unit_name'] ?? 'Rawat Jalan'
            ];
        }
    } catch (\Throwable $t) {}

    return $sessionUser;
}

function auth_login(array $user): void {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role_id' => (int)$user['role_id'],
        'role_name' => $user['role_name'] ?? 'Petugas Unit',
        'unit_id' => $user['unit_id'] ?? null,
        'unit_name' => $user['unit_name'] ?? 'Rawat Jalan'
    ];
}

function auth_logout(): void {
    unset($_SESSION['user']);
    session_destroy();
}

function require_auth(): void {
    if (!auth_check()) {
        header("Location: " . BASE_URL . "/index.php?route=login");
        exit();
    }
}

function require_role(array $allowedRoleIds): void {
    require_auth();
    $user = auth_user();
    if (!$user || !in_array((int)$user['role_id'], $allowedRoleIds)) {
        header("Location: " . BASE_URL . "/index.php?route=dashboard&access_denied=1");
        exit();
    }
}

function get_app_settings(): array {
    $file = __DIR__ . '/../../storage/settings.json';
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if ($data) return $data;
    }
    return [
        'app_name' => 'LOGBOOK DINAMIS',
        'app_subtitle' => 'RUMAH SAKIT',
        'app_icon' => '+',
        'sidebar_color' => '#0f172a'
    ];
}

function save_app_settings(array $settings): void {
    $file = __DIR__ . '/../../storage/settings.json';
    file_put_contents($file, json_encode($settings, JSON_PRETTY_PRINT));
}

function render_topbar(string $pageTitle, string $extraHtml = ''): void {
    $authUser = auth_user() ?? ['name' => 'Mas Mulyadi, S.Kep', 'role_name' => 'Petugas Unit', 'email' => 'masmul@rs.com', 'unit_name' => 'Rawat Jalan'];
    $cleanAuthName = trim(preg_replace('/\s*\(.*?\)/', '', $authUser['name'] ?? 'Mas Mulyadi, S.Kep'));
    $initial = strtoupper(substr($cleanAuthName, 0, 1));
    ?>
    <div class="topbar">
      <div class="topbar-title">
        <?= $pageTitle ?>
        <?php if (!empty($extraHtml)): ?>
          <span style="margin-left: 12px;"><?= $extraHtml ?></span>
        <?php endif; ?>
      </div>

      <div class="topbar-right">
        <div class="header-date"><?= date('d F Y H:i') ?></div>
        
        <!-- Notification Bell with Interactive Dropdown -->
        <?php
        require_once __DIR__ . '/../Models/Logbook.php';
        $recentNotifs = [];
        try {
            $recentNotifs = Logbook::getAll([], 5);
        } catch (\Throwable $t) {}
        $initialBadgeCount = count($recentNotifs);
        ?>
        <div class="notification-wrapper">
          <div class="notification-bell" onclick="toggleNotifications(event)">
            🔔
            <span class="bell-badge" id="notifBadge" style="<?= $initialBadgeCount === 0 ? 'display:none;' : '' ?>"><?= $initialBadgeCount ?></span>
          </div>

          <div class="notification-dropdown" id="notifDropdown">
            <div class="notification-header">
              <span>Notifikasi Terkini</span>
              <span style="font-size: 10px; color:#2563eb; cursor:pointer;" onclick="markAllRead()">Tandai Dibaca</span>
            </div>
            <div class="notification-list">
              <?php if (empty($recentNotifs)): ?>
                <div style="padding:16px; text-align:center; color:#64748b; font-size:12px;">Belum ada notifikasi.</div>
              <?php else: ?>
                <?php foreach ($recentNotifs as $n): 
                  $cat = strtolower($n['category'] ?? '');
                  $icon = '📝';
                  if (strpos($cat, 'simrs') !== false) $icon = '📋';
                  elseif (strpos($cat, 'jaringan') !== false) $icon = '🌐';
                  elseif (strpos($cat, 'server') !== false) $icon = '🖥️';
                  elseif (strpos($cat, 'insiden') !== false) $icon = '🚨';

                  $createdTime = !empty($n['created_at']) ? date('d/m/Y H:i', strtotime($n['created_at'])) : 'baru saja';
                ?>
                  <a href="<?= BASE_URL ?>/index.php?route=logbook_detail&id=<?= $n['id'] ?>" data-id="<?= $n['id'] ?>" onclick="markItemRead('<?= $n['id'] ?>')" class="notification-item unread">
                    <span><?= $icon ?></span>
                    <div>
                      <strong><?= htmlspecialchars($n['title']) ?></strong>
                      <div><?= htmlspecialchars(mb_strimwidth($n['description'] ?? '', 0, 45, '...')) ?></div>
                      <div style="color:var(--text-muted); font-size:10px;"><?= htmlspecialchars($n['unit_name'] ?? 'Unit') ?> • <?= $createdTime ?></div>
                    </div>
                  </a>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <div class="notification-footer">
              <a href="<?= BASE_URL ?>/index.php?route=logbook" style="color:#2563eb; text-decoration:none;">Lihat Semua Notifikasi →</a>
            </div>
          </div>
        </div>

        <!-- User Profile with Clean Dynamic Session Data -->
        <div class="user-profile-wrapper">
          <div class="user-profile" onclick="toggleUserDropdown(event)">
            <div class="user-avatar"><?= htmlspecialchars($initial) ?></div>
            <div class="user-info">
              <div class="user-name"><?= htmlspecialchars($cleanAuthName) ?> ▾</div>
              <div class="user-role"><?= htmlspecialchars($authUser['role_name']) ?></div>
            </div>
          </div>

          <div class="user-dropdown" id="userDropdown">
            <div class="user-dropdown-header">
              <div class="user-avatar" style="width:40px; height:40px;"><?= htmlspecialchars($initial) ?></div>
              <div>
                <div style="font-weight:700; font-size:13px; color:#0f172a;"><?= htmlspecialchars($cleanAuthName) ?> (<?= htmlspecialchars($authUser['role_name']) ?>)</div>
                <div style="font-size:11px; color:#64748b;"><?= htmlspecialchars($authUser['email'] ?? 'masmul@rs.com') ?> • <?= htmlspecialchars($authUser['unit_name'] ?? 'Rawat Jalan') ?></div>
              </div>
            </div>
            <div class="user-dropdown-menu">
              <a href="<?= BASE_URL ?>/index.php?route=master_users" class="user-dropdown-item">
                <span>👤</span> Profil Saya
              </a>
              <a href="#" class="user-dropdown-item" onclick="openModal('modalChangePassword'); return false;">
                <span>🔑</span> Ubah Password
              </a>
              <?php if (($authUser['role_id'] ?? 3) == 1): ?>
              <a href="<?= BASE_URL ?>/index.php?route=settings" class="user-dropdown-item">
                <span>⚙️</span> Pengaturan Akun
              </a>
              <?php endif; ?>
              <a href="<?= BASE_URL ?>/index.php?route=logout" class="user-dropdown-item danger">
                <span>🚪</span> Logout System
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
    <?php
}
