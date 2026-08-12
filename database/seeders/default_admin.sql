-- Seeders Data Awal untuk Logbook RS
USE `logbook_rs`;

-- Roles
INSERT IGNORE INTO `roles` (`id`, `name`, `slug`) VALUES
(1, 'Super Admin', 'admin'),
(2, 'IT Support', 'it_support'),
(3, 'Petugas Unit', 'unit_staff');

-- Units
INSERT IGNORE INTO `units` (`id`, `code`, `name`) VALUES
(1, 'SIMRS', 'SIMRS'),
(2, 'IGD', 'IGD'),
(3, 'RJ', 'Rawat Jalan'),
(4, 'RI', 'Rawat Inap'),
(5, 'IT', 'IT Room'),
(6, 'FAR', 'Farmasi');

-- Users (Password: password123 -> $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `unit_id`, `status`) VALUES
(1, 'Budi (IT Support)', 'budi@rs.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 5, 'active'),
(2, 'Siti (Petugas RJ)', 'siti@rs.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 3, 'active'),
(3, 'Andi (Teknisi)', 'andi@rs.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 5, 'active'),
(4, 'Rudi (Admin)', 'admin@rs.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, 'active');

-- Templates
INSERT IGNORE INTO `logbook_templates` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Log Gangguan SIMRS', 'log-gangguan-simrs', 'Catatan gangguan sistem SIMRS rumah sakit'),
(2, 'Log Jaringan', 'log-jaringan', 'Pemantauan & gangguan jaringan LAN/WiFi'),
(3, 'Log Server', 'log-server', 'Maintenis & pemantauan server RS'),
(4, 'Log Maintenance', 'log-maintenance', 'Pemeliharaan rutin perangkat & software'),
(5, 'Log Insiden', 'log-insiden', 'Laporan insiden keamanan atau downtime fatal');

-- Fields for Template 1 (Log Gangguan SIMRS)
INSERT IGNORE INTO `logbook_fields` (`id`, `template_id`, `label`, `field_type`, `is_required`, `placeholder`, `field_options`, `sort_order`) VALUES
(1, 1, 'Lokasi (Text)', 'text', 1, 'Contoh: IGD, Rawat Jalan, Farmasi', NULL, 1),
(2, 1, 'Perangkat (Select)', 'select', 0, 'Pilih Perangkat', '["PC Kasir", "Printer Thermal", "Barcode Scanner", "Server Database"]', 2),
(3, 1, 'IP Address (Text)', 'text', 0, '192.168.x.x', NULL, 3),
(4, 1, 'Jenis Gangguan (Select)', 'select', 1, 'Pilih Jenis', '["Aplikasi Error", "Gagal Login", "Printer Macet", "Koneksi Putus"]', 4),
(5, 1, 'Penyebab (Textarea)', 'textarea', 0, 'Jelaskan dugaan penyebab...', NULL, 5),
(6, 1, 'Tindakan (Textarea)', 'textarea', 1, 'Tindakan perbaikan yang dilakukan...', NULL, 6),
(7, 1, 'Teknisi (User)', 'user', 1, 'Pilih Teknisi Penanggungjawab', NULL, 7),
(8, 1, 'Status (Select)', 'select', 1, 'Pilih Status', '["Open", "Proses", "Selesai"]', 8);

-- Logbooks Sample Data
INSERT IGNORE INTO `logbooks` (`id`, `ticket_number`, `template_id`, `unit_id`, `category`, `priority`, `status`, `title`, `description`, `action_taken`, `assigned_to`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'LOG-20260812-001', 2, 2, 'Jaringan', 'Tinggi', 'Proses', 'Internet Putus', 'Koneksi internet di IGD terputus total sejak jam 10:30', 'Pengecekan kabel FO & restart switch utama', 1, 2, '2026-08-12 10:40:00', '2026-08-12 10:42:00'),
(2, 'LOG-20260812-002', 1, 3, 'SIMRS', 'Tinggi', 'Proses', 'SIMRS tidak dapat login', 'Pengguna tidak dapat login ke SIMRS muncul pesan "User atau Password salah".', 'Reset password sementara dan cek service SIMRS.', 1, 2, '2026-08-12 10:22:00', '2026-08-12 10:30:00'),
(3, 'LOG-20260812-003', 3, 5, 'Server', 'Sedang', 'Selesai', 'Server Database Lambat', 'Response time database melebihi 5 detik saat jam sibuk', 'Optimasasi query & re-index tabel utama', 3, 1, '2026-08-12 09:58:00', '2026-08-12 10:15:00'),
(4, 'LOG-20260812-004', 4, 5, 'Maintenance', 'Rendah', 'Selesai', 'Backup Harian Server', 'Pelaksanaan backup harian rutin database SIMRS', 'Backup berhasil disimpan di NAS Server', 1, 1, '2026-08-12 09:15:00', '2026-08-12 09:40:00'),
(5, 'LOG-20260812-005', 5, 4, 'Insiden', 'Tinggi', 'Proses', 'Akses Tidak Sah SIMRS', 'Terdeteksi percobaan login mencurigakan dari IP external', 'Blokir IP di Firewall & audit log akses', 4, 1, '2026-08-12 08:32:00', '2026-08-12 09:00:00');

-- Audit Logs
INSERT IGNORE INTO `audit_logs` (`id`, `logbook_id`, `user_id`, `action`, `note`, `created_at`) VALUES
(1, 2, 2, 'Dibuat oleh Siti', 'Logbook dibuat oleh Siti', '2026-08-12 10:22:00'),
(2, 2, 1, 'Diambil oleh Budi', 'Status diubah ke Proses', '2026-08-12 10:30:00'),
(3, 2, 1, 'Diselesaikan oleh Budi', 'Masalah terselesaikan', '2026-08-12 11:15:00');
