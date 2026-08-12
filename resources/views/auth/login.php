<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Logbook Dinamis Rumah Sakit</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    .login-box {
      width: 400px;
      background: #ffffff;
      padding: 36px 32px;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.3);
      border: 1px solid #334155;
    }
    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }
    .brand-icon {
      width: 54px;
      height: 54px;
      background: #2563eb;
      color: white;
      border-radius: 12px;
      font-size: 28px;
      font-weight: bold;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 12px;
      box-shadow: 0 4px 12px rgba(37,99,235,0.4);
    }
    .demo-hint {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 10px 12px;
      font-size: 11px;
      color: #64748b;
      margin-top: 16px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <div class="brand-header">
    <div class="brand-icon">+</div>
    <h2 style="color:#0f172a; font-weight:800; font-size: 20px; letter-spacing: 0.5px;">LOGBOOK DINAMIS</h2>
    <p style="color:#64748b; font-size:12px; font-weight: 600; margin-top: 2px;">RUMAH SAKIT</p>
  </div>

  <form method="POST" action="<?= BASE_URL ?>/index.php?route=do_login">
    <div class="form-group">
      <label class="form-label">Email / Username <span class="required">*</span></label>
      <input type="email" name="email" class="form-control" value="masmul@rs.com" required placeholder="Masukkan email...">
    </div>

    <div class="form-group">
      <label class="form-label">Password <span class="required">*</span></label>
      <input type="password" name="password" class="form-control" value="password123" required placeholder="Masukkan password...">
    </div>

    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 14px; margin-top: 10px;">
      🔑 Masuk ke Sistem
    </button>
  </form>

  <div class="demo-hint">
    <strong>Demo Akun Login:</strong><br>
    • Petugas Unit: <code>masmul@rs.com</code> (Pass: password123)<br>
    • IT Support: <code>budi@rs.id</code> (Pass: password123)<br>
    • Super Admin: <code>admin@rs.id</code> (Pass: password123)
  </div>
</div>

</body>
</html>
