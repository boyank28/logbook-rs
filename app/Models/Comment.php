<?php
require_once __DIR__ . '/../../config/database.php';

class Comment {
    public static function initTable(): void {
        $db = Database::getConnection();
        $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'sqlite') {
            $sql = "CREATE TABLE IF NOT EXISTS comments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                logbook_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                comment TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            try { $db->exec($sql); } catch (\Throwable $t) {}
        } else {
            $sqlMysql = "CREATE TABLE IF NOT EXISTS comments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                logbook_id INT NOT NULL,
                user_id INT NOT NULL,
                comment TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            try { $db->exec($sqlMysql); } catch (\Throwable $ex) {}
        }
    }

    public static function getByLogbook(int $logbookId): array {
        self::initTable();
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT c.*, u.name as user_name, r.name as role_name 
                               FROM comments c 
                               LEFT JOIN users u ON c.user_id = u.id 
                               LEFT JOIN roles r ON u.role_id = r.id 
                               WHERE c.logbook_id = ? 
                               ORDER BY c.id ASC");
        $stmt->execute([$logbookId]);
        return $stmt->fetchAll() ?: [];
    }

    public static function create(array $data): int {
        self::initTable();
        $db = Database::getConnection();
        $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $timeFunc = ($driver === 'sqlite') ? "datetime('now')" : "NOW()";

        $stmt = $db->prepare("INSERT INTO comments (logbook_id, user_id, comment, created_at) VALUES (?, ?, ?, $timeFunc)");
        $stmt->execute([
            $data['logbook_id'],
            $data['user_id'],
            $data['comment']
        ]);
        return (int)$db->lastInsertId();
    }
}
