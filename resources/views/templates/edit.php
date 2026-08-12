<form id="templateForm" method="POST" action="<?= BASE_URL ?>/index.php?route=template_save_fields">
  <input type="hidden" name="template_id" value="<?= $template['id'] ?>">
  <input type="hidden" name="fields_json" id="fieldsJsonInput">

<?php
$tplTitle = '← Edit Template : ' . htmlspecialchars($template['name'] ?? 'Logbook');
$tplBtns = '<div style="display:inline-flex; gap:8px;"><a href="' . BASE_URL . '/index.php?route=templates" class="btn btn-secondary btn-sm">Batal</a><button type="button" class="btn btn-primary btn-sm" onclick="submitTemplateForm()">💾 Simpan Template</button></div>';
render_topbar($tplTitle, $tplBtns);
?>


  <div class="page-body">
    <div class="card">
      <div class="builder-grid">
        <!-- Left Panel: Field List -->
        <div>
          <h4 style="font-size: 14px; font-weight:700; margin-bottom: 14px;">Daftar Field</h4>
          
          <div id="fieldsSortable"></div>

          <button type="button" class="btn btn-primary btn-sm" style="margin-top: 14px;" onclick="addNewField()">+ Tambah Field</button>
        </div>

        <!-- Right Panel: Field Properties Config -->
        <div id="configPanel" style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color);">
          <h4 style="font-size: 14px; font-weight:700; margin-bottom: 14px;">Pengaturan Field</h4>
          
          <div class="form-group">
            <label class="form-label">Label Field <span class="required">*</span></label>
            <input type="text" id="propLabel" class="form-control" placeholder="Contoh: Lokasi, Serial Number" oninput="updateCurrentField()">
          </div>

          <div class="form-group">
            <label class="form-label">Tipe Field</label>
            <select id="propType" class="form-control" onchange="updateCurrentField()">
              <option value="text">Text (Singkat)</option>
              <option value="select">Dropdown Choice (Select)</option>
              <option value="textarea">Textarea (Panjang)</option>
              <option value="number">Angka / Number</option>
              <option value="date">Tanggal / Date</option>
            </select>
          </div>

          <div class="form-group" id="optionsGroup" style="display: none;">
            <label class="form-label">Pilihan Options (Pisahkan dengan koma)</label>
            <input type="text" id="propOptions" class="form-control" placeholder="Option 1, Option 2, Option 3" oninput="updateCurrentField()">
          </div>

          <div class="form-group">
            <label class="form-label" style="display:flex; align-items:center; gap:8px; cursor:pointer;">
              <input type="checkbox" id="propRequired" onchange="updateCurrentField()"> Wajib Diisi
            </label>
          </div>

          <div class="form-group">
            <label class="form-label">Placeholder / Hint</label>
            <input type="text" id="propPlaceholder" class="form-control" placeholder="Contoh: Masukkan detail..." oninput="updateCurrentField()">
          </div>

          <div class="form-group">
            <label class="form-label">Urutan</label>
            <input type="number" id="propOrder" class="form-control" min="1" oninput="updateCurrentField()">
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
let fieldsData = <?= json_encode($fields) ?>;
let selectedIndex = 0;

function renderFieldsList() {
  const container = document.getElementById('fieldsSortable');
  container.innerHTML = '';

  if (fieldsData.length === 0) {
    container.innerHTML = '<div style="color:var(--text-muted); font-size:12px; padding:10px;">Belum ada field. Klik "+ Tambah Field" di bawah.</div>';
    document.getElementById('configPanel').style.opacity = '0.5';
    return;
  }

  document.getElementById('configPanel').style.opacity = '1';

  fieldsData.forEach((f, idx) => {
    const item = document.createElement('div');
    item.className = 'field-list-item' + (idx === selectedIndex ? ' active' : '');
    item.style.cssText = `
      display: flex; justify-content: space-between; align-items: center;
      padding: 12px 14px; margin-bottom: 8px; border-radius: 6px;
      border: 1px solid ${idx === selectedIndex ? '#2563eb' : '#e2e8f0'};
      background: ${idx === selectedIndex ? '#f0f6ff' : '#ffffff'};
      cursor: pointer; transition: all 0.2s;
    `;
    item.onclick = () => selectField(idx);

    item.innerHTML = `
      <div>
        <span class="field-drag-handle" style="color:#94a3b8; margin-right:8px;">≡</span>
        <strong>${escapeHtml(f.label || 'Field Tanpa Nama')}</strong>
        <span style="font-size:11px; color:#64748b; margin-left:6px;">(${f.field_type})</span>
      </div>
      <div style="display:flex; align-items:center; gap:8px;">
        <span style="font-size:10px; padding:2px 6px; border-radius:4px; background:${f.is_required ? '#fee2e2' : '#f1f5f9'}; color:${f.is_required ? '#dc2626' : '#64748b'};">
          ${f.is_required ? 'Wajib' : 'Opsional'}
        </span>
        <button type="button" onclick="deleteField(event, ${idx})" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:14px;" title="Hapus Field">🗑️</button>
      </div>
    `;

    container.appendChild(item);
  });

  populateConfigForm();
}

function selectField(idx) {
  selectedIndex = idx;
  renderFieldsList();
}

function populateConfigForm() {
  if (fieldsData.length === 0 || selectedIndex >= fieldsData.length) return;
  const f = fieldsData[selectedIndex];

  document.getElementById('propLabel').value = f.label || '';
  document.getElementById('propType').value = f.field_type || 'text';
  document.getElementById('propRequired').checked = !!parseInt(f.is_required);
  document.getElementById('propPlaceholder').value = f.placeholder || '';
  document.getElementById('propOrder').value = f.sort_order || (selectedIndex + 1);
  document.getElementById('propOptions').value = f.field_options || '';

  const optionsGroup = document.getElementById('optionsGroup');
  optionsGroup.style.display = (f.field_type === 'select') ? 'block' : 'none';
}

function updateCurrentField() {
  if (fieldsData.length === 0 || selectedIndex >= fieldsData.length) return;

  const type = document.getElementById('propType').value;
  fieldsData[selectedIndex].label = document.getElementById('propLabel').value;
  fieldsData[selectedIndex].field_type = type;
  fieldsData[selectedIndex].is_required = document.getElementById('propRequired').checked ? 1 : 0;
  fieldsData[selectedIndex].placeholder = document.getElementById('propPlaceholder').value;
  fieldsData[selectedIndex].sort_order = parseInt(document.getElementById('propOrder').value) || (selectedIndex + 1);
  fieldsData[selectedIndex].field_options = document.getElementById('propOptions').value;

  document.getElementById('optionsGroup').style.display = (type === 'select') ? 'block' : 'none';

  // Re-render list title without resetting selection
  const listItems = document.querySelectorAll('#fieldsSortable .field-list-item');
  if (listItems[selectedIndex]) {
    const labelSpan = listItems[selectedIndex].querySelector('strong');
    if (labelSpan) labelSpan.textContent = fieldsData[selectedIndex].label || 'Field Tanpa Nama';
  }
}

function addNewField() {
  const newIndex = fieldsData.length;
  fieldsData.push({
    id: null,
    label: 'Field Baru ' + (newIndex + 1),
    field_type: 'text',
    is_required: 1,
    placeholder: '',
    field_options: '',
    sort_order: newIndex + 1
  });
  selectedIndex = newIndex;
  renderFieldsList();
}

function deleteField(e, idx) {
  e.stopPropagation();
  if (confirm('Hapus field ini dari template?')) {
    fieldsData.splice(idx, 1);
    if (selectedIndex >= fieldsData.length) {
      selectedIndex = Math.max(0, fieldsData.length - 1);
    }
    renderFieldsList();
  }
}

function submitTemplateForm() {
  document.getElementById('fieldsJsonInput').value = JSON.stringify(fieldsData);
  document.getElementById('templateForm').submit();
}

function escapeHtml(str) {
  return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
}

document.addEventListener('DOMContentLoaded', renderFieldsList);
</script>
