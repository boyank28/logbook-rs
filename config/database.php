<?php
class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = '127.0.0.1';
            $db   = 'logbook_rs';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            try {
                // Try MySQL first
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                // If MySQL database doesn't exist, attempt to create it
                try {
                    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass);
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                    self::$instance = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    self::initTables(self::$instance);
                } catch (\PDOException $ex) {
                    // Fallback to SQLite in storage/database.sqlite if MySQL is unavailable
                    $sqlitePath = __DIR__ . '/../storage/database.sqlite';
                    self::$instance = new PDO("sqlite:" . $sqlitePath);
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    self::initSQLiteTables(self::$instance);
                }
            }
            self::seedDefaultUsers(self::$instance);
        }
        return self::$instance;
    }

    private static function seedDefaultUsers(PDO $db): void {
        try {
            $check = $db->query("SELECT COUNT(*) as cnt FROM users WHERE email = 'masmul@rs.com'")->fetch();
            if (!$check || $check['cnt'] == 0) {
                $stmt = $db->prepare("INSERT INTO users (name, email, password, role_id, unit_id, status) VALUES (?, ?, ?, ?, ?, 'active')");
                $hash = password_hash('password123', PASSWORD_BCRYPT);
                $stmt->execute(['Mas Mulyadi, S.Kep', 'masmul@rs.com', $hash, 3, 3]);
            }
        } catch (\Throwable $t) {}
    }

    private static function initTables(PDO $db): void {
        $sqlPath = __DIR__ . '/../database/migrations/001_users.sql';
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            $db->exec($sql);
        }
        $seederPath = __DIR__ . '/../database/seeders/default_admin.sql';
        if (file_exists($seederPath)) {
            $sqlSeeder = file_get_contents($seederPath);
            try { $db->exec($sqlSeeder); } catch (\Throwable $t) {}
        }
    }

    private static function initSQLiteTables(PDO $db): void {
        $db->exec("
            CREATE TABLE IF NOT EXISTS roles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE
            );
            CREATE TABLE IF NOT EXISTS units (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT NOT NULL UNIQUE,
                name TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                role_id INTEGER NOT NULL,
                unit_id INTEGER NULL,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS logbook_templates (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                description TEXT NULL,
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS logbook_fields (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                template_id INTEGER NOT NULL,
                label TEXT NOT NULL,
                field_type TEXT NOT NULL,
                is_required INTEGER DEFAULT 0,
                placeholder TEXT NULL,
                field_options TEXT NULL,
                sort_order INTEGER DEFAULT 0
            );
            CREATE TABLE IF NOT EXISTS logbooks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ticket_number TEXT NOT NULL UNIQUE,
                template_id INTEGER NOT NULL,
                unit_id INTEGER NOT NULL,
                category TEXT NOT NULL,
                priority TEXT DEFAULT 'Sedang',
                status TEXT DEFAULT 'Open',
                title TEXT NOT NULL,
                description TEXT NOT NULL,
                action_taken TEXT NULL,
                assigned_to INTEGER NULL,
                created_by INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS logbook_values (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                logbook_id INTEGER NOT NULL,
                field_id INTEGER NOT NULL,
                value TEXT NULL
            );
            CREATE TABLE IF NOT EXISTS attachments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                logbook_id INTEGER NOT NULL,
                filename TEXT NOT NULL,
                filepath TEXT NOT NULL,
                filetype TEXT NULL,
                filesize INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS audit_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                logbook_id INTEGER NULL,
                user_id INTEGER NOT NULL,
                action TEXT NOT NULL,
                note TEXT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Check if users empty
        $stmt = $db->query("SELECT COUNT(*) as cnt FROM users");
        $row = $stmt->fetch();
        if ($row && $row['cnt'] == 0) {
            $db->exec("INSERT INTO roles (id, name, slug) VALUES 
                (1, 'Super Admin', 'admin'),
                (2, 'IT Support', 'it_support'),
                (3, 'Petugas Unit', 'unit_staff');");

            $db->exec("INSERT INTO units (id, code, name) VALUES 
                (1, 'SIMRS', 'SIMRS'),
                (2, 'IGD', 'IGD'),
                (3, 'RJ', 'Rawat Jalan'),
                (4, 'RI', 'Rawat Inap'),
                (5, 'IT', 'IT Room'),
                (6, 'FAR', 'Farmasi');");

            $db->exec("INSERT INTO users (id, name, email, password, role_id, unit_id, status) VALUES
                (1, 'Mas Mulyadi, S.Kep', 'masmul@rs.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 3, 'active'),
                (2, 'Rudi (Admin)', 'admin@rs.id', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, 'active'),
                (3, 'Andi (Teknisi)', 'andi@rs.id', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 5, 'active'),
                (4, 'Siti (Petugas RJ)', 'siti@rs.id', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 3, 'active'),
                (5, 'Budi (IT Support)', 'budi@rs.id', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 5, 'active');");

            $db->exec("INSERT INTO logbook_templates (id, name, slug, description) VALUES
                (1, 'Log Gangguan SIMRS', 'log-gangguan-simrs', 'Catatan gangguan sistem SIMRS rumah sakit'),
                (2, 'Log Jaringan', 'log-jaringan', 'Pemantauan & gangguan jaringan LAN/WiFi'),
                (3, 'Log Server', 'log-server', 'Maintenis & pemantauan server RS'),
                (4, 'Log Maintenance', 'log-maintenance', 'Pemeliharaan rutin perangkat & software'),
                (5, 'Log Insiden', 'log-insiden', 'Laporan insiden keamanan atau downtime fatal');");

            $db->exec("INSERT INTO logbook_fields (id, template_id, label, field_type, is_required, placeholder, sort_order) VALUES
                (1, 1, 'Lokasi (text)', 'text', 1, 'Contoh: IGD, Rawat Jalan, Farmasi', 1),
                (2, 1, 'Perangkat (select)', 'select', 0, 'Pilih Perangkat', 2),
                (3, 1, 'IP Address (text)', 'text', 0, '192.168.x.x', 3),
                (4, 1, 'Jenis Gangguan (select)', 'select', 1, 'Pilih Jenis', 4),
                (5, 1, 'Penyebab (textarea)', 'textarea', 0, 'Jelaskan dugaan penyebab...', 5),
                (6, 1, 'Tindakan (textarea)', 'textarea', 1, 'Tindakan perbaikan yang dilakukan...', 6),
                (7, 1, 'Teknisi (user)', 'user', 1, 'Pilih Teknisi', 7),
                (8, 1, 'Status (select)', 'select', 1, 'Pilih Status', 8);");

            $db->exec("INSERT INTO logbooks (id, ticket_number, template_id, unit_id, category, priority, status, title, description, action_taken, assigned_to, created_by, created_at, updated_at) VALUES
                (1, 'LOG-20260812-001', 2, 2, 'Jaringan', 'Tinggi', 'Proses', 'Internet Putus', 'Koneksi internet di IGD terputus total sejak jam 10:30', 'Pengecekan kabel FO & restart switch utama', 1, 2, '2026-08-12 10:40:00', '2026-08-12 10:42:00'),
                (2, 'LOG-20260812-002', 1, 3, 'SIMRS', 'Tinggi', 'Proses', 'SIMRS tidak dapat login', 'Pengguna tidak dapat login ke SIMRS muncul pesan \"User atau Password salah\".', 'Reset password sementara dan cek service SIMRS.', 1, 2, '2026-08-12 10:22:00', '2026-08-12 10:30:00'),
                (3, 'LOG-20260812-003', 3, 5, 'Server', 'Sedang', 'Selesai', 'Server Database Lambat', 'Response time database melebihi 5 detik saat jam sibuk', 'Optimasasi query & re-index tabel utama', 3, 1, '2026-08-12 09:58:00', '2026-08-12 10:15:00'),
                (4, 'LOG-20260812-004', 4, 5, 'Maintenance', 'Rendah', 'Selesai', 'Backup Harian Server', 'Pelaksanaan backup harian rutin database SIMRS', 'Backup berhasil disimpan di NAS Server', 1, 1, '2026-08-12 09:15:00', '2026-08-12 09:40:00'),
                (5, 'LOG-20260812-005', 5, 4, 'Insiden', 'Tinggi', 'Proses', 'Akses Tidak Sah SIMRS', 'Terdeteksi percobaan login mencurigakan dari IP external', 'Blokir IP di Firewall & audit log akses', 4, 1, '2026-08-12 08:32:00', '2026-08-12 09:00:00');");

            $db->exec("INSERT INTO audit_logs (id, logbook_id, user_id, action, note, created_at) VALUES
                (1, 2, 2, 'Dibuat oleh Siti', 'Logbook dibuat oleh Siti', '2026-08-12 10:22:00'),
                (2, 2, 1, 'Diambil oleh Budi', 'Status diubah ke Proses', '2026-08-12 10:30:00'),
                (3, 2, 1, 'Diselesaikan oleh Budi', 'Masalah terselesaikan oleh Budi', '2026-08-12 11:15:00');");
        }
    }
}
