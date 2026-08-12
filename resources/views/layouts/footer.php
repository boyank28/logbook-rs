  </div> <!-- end main-content -->
</div> <!-- end app-container -->

<!-- Modal Change Password Global -->
<div id="modalChangePassword" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:white; width:400px; border-radius:14px; padding:24px; box-shadow: 0 20px 30px rgba(0,0,0,0.25);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 18px;">
      <h3 style="font-size: 16px; font-weight:700; color:#0f172a; margin:0;">🔑 Ubah Password Akun</h3>
      <button onclick="closeModal('modalChangePassword')" style="background:none; border:none; font-size:18px; cursor:pointer; color:#64748b;">✕</button>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?route=change_password">
      <div class="form-group">
        <label class="form-label">Password Lama <span class="required">*</span></label>
        <input type="password" name="old_password" class="form-control" required placeholder="Masukkan password lama...">
      </div>

      <div class="form-group">
        <label class="form-label">Password Baru <span class="required">*</span></label>
        <input type="password" name="new_password" class="form-control" minlength="6" required placeholder="Minimal 6 karakter...">
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
        <input type="password" name="confirm_password" class="form-control" minlength="6" required placeholder="Ulangi password baru...">
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:24px;">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalChangePassword')">Batal</button>
        <button type="submit" class="btn btn-primary">💾 Simpan Password</button>
      </div>
    </form>
  </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
<script src="<?= BASE_URL ?>/assets/js/logbook.js"></script>
<script src="<?= BASE_URL ?>/assets/js/template-builder.js"></script>
<script>
function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'flex';
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'none';
}

function toggleNotifications(e) {
  e.stopPropagation();
  const uDropdown = document.getElementById('userDropdown');
  if (uDropdown) uDropdown.classList.remove('active');
  const dropdown = document.getElementById('notifDropdown');
  if (dropdown) dropdown.classList.toggle('active');
}

function toggleUserDropdown(e) {
  e.stopPropagation();
  const nDropdown = document.getElementById('notifDropdown');
  if (nDropdown) nDropdown.classList.remove('active');
  const dropdown = document.getElementById('userDropdown');
  if (dropdown) dropdown.classList.toggle('active');
}

function markAllRead() {
  document.querySelectorAll('.notification-item.unread').forEach(item => item.classList.remove('unread'));
  const badge = document.getElementById('notifBadge');
  if (badge) badge.style.display = 'none';
}

document.addEventListener('click', function() {
  const nDropdown = document.getElementById('notifDropdown');
  if (nDropdown) nDropdown.classList.remove('active');
  const uDropdown = document.getElementById('userDropdown');
  if (uDropdown) uDropdown.classList.remove('active');
});

<?php if (isset($_GET['pwd_success'])): ?>
  alert('✅ Password akun Anda telah berhasil diperbarui!');
<?php endif; ?>
<?php if (isset($_GET['pwd_error'])): ?>
  alert('⚠️ Gagal Ubah Password: <?= htmlspecialchars($_GET['pwd_error'], ENT_QUOTES) ?>');
<?php endif; ?>
</script>
</body>
</html>
