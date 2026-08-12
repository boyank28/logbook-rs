<?php
require_once __DIR__ . '/../../config/database.php';

if (!class_exists('User')) {
    class User {
        public static function getAll(): array {
            $db = Database::getConnection();
            return $db->query("SELECT u.*, r.name as role_name, un.name as unit_name 
                               FROM users u 
                               LEFT JOIN roles r ON u.role_id = r.id
                               LEFT JOIN units un ON u.unit_id = un.id
                               ORDER BY u.id DESC")->fetchAll();
        }

        public static function findById(int $id): ?array {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch() ?: null;
        }

        public static function create(string $name, string $email, string $password, int $roleId, ?int $unitId): int {
            $db = Database::getConnection();
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("INSERT INTO users (name, email, password, role_id, unit_id, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
            $stmt->execute([$name, $email, $hash, $roleId, $unitId]);
            return (int)$db->lastInsertId();
        }

        public static function update(int $id, string $name, string $email, int $roleId, ?int $unitId): bool {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, role_id = ?, unit_id = ? WHERE id = ?");
            return $stmt->execute([$name, $email, $roleId, $unitId, $id]);
        }

        public static function updatePassword(int $id, string $newPassword): bool {
            $db = Database::getConnection();
            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            return $stmt->execute([$hash, $id]);
        }

        public static function delete(int $id): bool {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }
}

if (!class_exists('Unit')) {
    class Unit {
        public static function getAll(): array {
            $db = Database::getConnection();
            return $db->query("SELECT * FROM units ORDER BY id DESC")->fetchAll();
        }

        public static function create(string $code, string $name): int {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO units (code, name, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$code, $name]);
            return (int)$db->lastInsertId();
        }

        public static function update(int $id, string $code, string $name): bool {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE units SET code = ?, name = ? WHERE id = ?");
            return $stmt->execute([$code, $name, $id]);
        }

        public static function delete(int $id): bool {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM units WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }
}

if (!class_exists('LogbookTemplate')) {
    class LogbookTemplate {
        public static function getAll(): array {
            $db = Database::getConnection();
            return $db->query("SELECT t.*, (SELECT COUNT(*) FROM logbook_fields WHERE template_id = t.id) as field_count 
                               FROM logbook_templates t ORDER BY t.id ASC")->fetchAll();
        }

        public static function find(int $id): ?array {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM logbook_templates WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch() ?: null;
        }

        public static function create(string $name, ?string $description = null): int {
            $db = Database::getConnection();
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . rand(100, 999);
            $stmt = $db->prepare("INSERT INTO logbook_templates (name, slug, description, is_active, created_at) VALUES (?, ?, ?, 1, NOW())");
            $stmt->execute([$name, $slug, $description]);
            return (int)$db->lastInsertId();
        }

        public static function delete(int $id): bool {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM logbook_templates WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }
}

if (!class_exists('AuditLog')) {
    class AuditLog {
        public static function getByLogbook(int $logbookId): array {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id WHERE a.logbook_id = ? ORDER BY a.id ASC");
            $stmt->execute([$logbookId]);
            return $stmt->fetchAll();
        }

        public static function getAll(): array {
            $db = Database::getConnection();
            return $db->query("SELECT a.*, u.name as user_name, l.ticket_number 
                               FROM audit_logs a 
                               LEFT JOIN users u ON a.user_id = u.id 
                               LEFT JOIN logbooks l ON a.logbook_id = l.id 
                               ORDER BY a.id DESC LIMIT 50")->fetchAll();
        }

        public static function log(?int $logbookId, int $userId, string $action, ?string $note = null): void {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO audit_logs (logbook_id, user_id, action, note, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$logbookId, $userId, $action, $note]);
        }
    }
}
