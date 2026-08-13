<?php
require_once __DIR__ . '/../../config/database.php';

class Attachment {
    public static function initTable(): void {
        $db = Database::getConnection();
        $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'sqlite') {
            $sql = "CREATE TABLE IF NOT EXISTS attachments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                logbook_id INTEGER NOT NULL,
                filename TEXT NOT NULL,
                filepath TEXT NOT NULL,
                filesize INTEGER DEFAULT 0,
                filetype TEXT DEFAULT 'file',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            try { $db->exec($sql); } catch (\Throwable $t) {}
        } else {
            $sqlMysql = "CREATE TABLE IF NOT EXISTS attachments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                logbook_id INT NOT NULL,
                filename VARCHAR(255) NOT NULL,
                filepath VARCHAR(255) NOT NULL,
                filesize INT DEFAULT 0,
                filetype VARCHAR(50) DEFAULT 'file',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            try { $db->exec($sqlMysql); } catch (\Throwable $ex) {}
        }
    }

    public static function getByLogbook(int $logbookId): array {
        self::initTable();
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM attachments WHERE logbook_id = ? ORDER BY id DESC");
        $stmt->execute([$logbookId]);
        $rows = $stmt->fetchAll() ?: [];
        foreach ($rows as &$r) {
            $r['file_name'] = $r['filename'] ?? $r['file_name'] ?? '';
            $r['file_path'] = $r['filepath'] ?? $r['file_path'] ?? '';
            $r['file_type'] = $r['filetype'] ?? $r['file_type'] ?? 'file';
            $r['file_size'] = $r['filesize'] ?? $r['file_size'] ?? 0;
        }
        return $rows;
    }

    public static function create(array $data): int {
        self::initTable();
        $db = Database::getConnection();
        $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $timeFunc = ($driver === 'sqlite') ? "datetime('now')" : "NOW()";

        $fileName = $data['file_name'] ?? $data['filename'] ?? '';
        $filePath = $data['file_path'] ?? $data['filepath'] ?? '';
        $fileSize = $data['file_size'] ?? $data['filesize'] ?? 0;
        $fileType = $data['file_type'] ?? $data['filetype'] ?? 'file';

        try {
            $stmt = $db->prepare("INSERT INTO attachments (logbook_id, filename, filepath, filesize, filetype, created_at) VALUES (?, ?, ?, ?, ?, $timeFunc)");
            $stmt->execute([
                $data['logbook_id'],
                $fileName,
                $filePath,
                $fileSize,
                $fileType
            ]);
            return (int)$db->lastInsertId();
        } catch (\Throwable $t) {
            $stmt = $db->prepare("INSERT INTO attachments (logbook_id, file_name, file_path, file_size, file_type, created_at) VALUES (?, ?, ?, ?, ?, $timeFunc)");
            $stmt->execute([
                $data['logbook_id'],
                $fileName,
                $filePath,
                $fileSize,
                $fileType
            ]);
            return (int)$db->lastInsertId();
        }
    }
}
