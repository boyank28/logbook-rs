<?php
render_topbar('Pengaturan Aplikasi');
?>

<div class="page-body">
  <!-- Branding Settings -->
  <div class="card">
    <h3 style="font-size: 16px; font-weight:700; margin-bottom: 20px;">⚙️ Pengaturan Branding & Logo Aplikasi</h3>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=settings_save">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div class="form-group">
          <label class="form-label">Nama Utama Aplikasi (Baris 1) <span class="required">*</span></label>
          <input type="text" name="app_name" class="form-control" value="<?= htmlspecialchars($settings['app_name'] ?? 'LOGBOOK DINAMIS') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Nama Subtitle / Instansi (Baris 2) <span class="required">*</span></label>
          <input type="text" name="app_subtitle" class="form-control" value="<?= htmlspecialchars($settings['app_subtitle'] ?? 'RUMAH SAKIT') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Ikon Logo Brand <span class="required">*</span></label>
          <select name="app_icon" class="form-control">
            <option value="+" <?= ($settings['app_icon']??'') === '+' ? 'selected' : '' ?>>➕ Cross Plus (Default)</option>
            <option value="🏥" <?= ($settings['app_icon']??'') === '🏥' ? 'selected' : '' ?>>🏥 Rumah Sakit</option>
            <option value="🩺" <?= ($settings['app_icon']??'') === '🩺' ? 'selected' : '' ?>>🩺 Stetoskop</option>
            <option value="🛡️" <?= ($settings['app_icon']??'') === '🛡️' ? 'selected' : '' ?>>🛡️ Shield IT</option>
            <option value="💻" <?= ($settings['app_icon']??'') === '💻' ? 'selected' : '' ?>>💻 Laptop/Komputer</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Warna Theme Sidebar</label>
          <select name="sidebar_color" class="form-control">
            <option value="#0f172a" <?= ($settings['sidebar_color']??'') === '#0f172a' ? 'selected' : '' ?>>🌑 Dark Navy (Default)</option>
            <option value="#0284c7" <?= ($settings['sidebar_color']??'') === '#0284c7' ? 'selected' : '' ?>>🌊 Ocean Blue</option>
            <option value="#059669" <?= ($settings['sidebar_color']??'') === '#059669' ? 'selected' : '' ?>>Emerald Green</option>
            <option value="#7c3aed" <?= ($settings['sidebar_color']??'') === '#7c3aed' ? 'selected' : '' ?>>🟣 Royal Purple</option>
          </select>
        </div>
      </div>

      <div style="margin-top: 24px;">
        <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">💾 Simpan Pengaturan Branding</button>
      </div>
    </form>
  </div>

  <!-- Business Process Card (Compact) -->
  <div class="card" style="margin-top: 24px;">
    <h3 style="font-size: 15px; font-weight:700; margin-bottom: 12px; color: #0f172a;">🔄 Bisnis Proses Singkat Sistem</h3>
    <div style="font-size: 13px; color: #334155; line-height: 1.8;">
      • <strong>1. Input Logbook:</strong> Petugas Unit membuat tiket laporan kendala (SIMRS, Jaringan, Server, Komkep, dll).<br>
      • <strong>2. Penugasan IT:</strong> Tiket diteruskan ke Tim IT / Teknisi penanggungjawab sesuai target respon SLA.<br>
      • <strong>3. Tindakan & Solusi:</strong> Teknisi menangani kendala dan meng-update status (Open ➔ Proses ➔ Selesai).<br>
      • <strong>4. Audit & Laporan:</strong> Riwayat terekam otomatis di Audit Log System & dapat di-export ke Excel.
    </div>
  </div>

  <!-- About Application & Developer Support Card -->
  <div class="card" style="margin-top: 24px;" id="about">
    <h3 style="font-size: 16px; font-weight:700; margin-bottom: 16px;">ℹ️ Tentang Aplikasi & Dukungan Pengembang</h3>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: center;">
      <div>
        <p style="font-size: 13px; color: #334155; line-height: 1.6; margin-bottom: 12px;">
          <strong>Logbook Dinamis Rumah Sakit (v1.2.0)</strong> adalah sistem manajemen operasional IT & Layanan Rumah Sakit terintegrasi. Dirancang khusus untuk mempermudah pelaporan gangguan SIMRS, pemeliharaan server, insiden jaringan, serta rekapitulasi kinerja unit kerja secara real-time.
        </p>
        <div style="display: flex; gap: 16px; font-size: 12px; color: #64748b;">
          <span>📌 <strong>Arsitektur:</strong> PHP Native MVC</span>
          <span>💾 <strong>Database:</strong> MySQL / SQLite</span>
          <span>⚡ <strong>Lisensi:</strong> Open-Source Production</span>
        </div>
      </div>

      <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); padding: 20px; border-radius: 12px; border: 1px solid #fed7aa; text-align: center;">
        <div style="font-size: 28px; margin-bottom: 6px;">☕💛</div>
        <h4 style="font-size: 14px; font-weight: 700; color: #9a3412; margin-bottom: 4px;">Dukung Pengembang</h4>
        <p style="font-size: 11px; color: #c2410c; margin-bottom: 14px;">Bantu kami terus mengembangkan fitur-fitur hebat aplikasi ini!</p>
        
        <a href="https://saweria.co/boyank28" target="_blank" rel="noopener noreferrer" class="btn" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; border-radius: 8px; width: 100%; justify-content: center; font-weight: 700; box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3); text-decoration: none;">
          🎁 Support via Saweria
        </a>
      </div>
    </div>
  </div>
</div>
