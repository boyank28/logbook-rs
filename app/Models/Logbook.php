<?php
require_once __DIR__ . '/../../config/database.php';

class Logbook {
    public static function countAll(array $filters = []): int {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM logbooks l WHERE 1=1";
        $params = [];

        if (!empty($filters['template_id'])) {
            $sql .= " AND l.template_id = ?";
            $params[] = $filters['template_id'];
        }
        if (!empty($filters['unit_id'])) {
            $sql .= " AND l.unit_id = ?";
            $params[] = $filters['unit_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND l.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['priority'])) {
            $sql .= " AND l.priority = ?";
            $params[] = $filters['priority'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (l.title LIKE ? OR l.description LIKE ? OR l.ticket_number LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public static function getAll(array $filters = [], ?int $limit = null, ?int $offset = null): array {
        $db = Database::getConnection();
        $sql = "SELECT l.*, 
                       u.name as unit_name, 
                       t.name as template_name, 
                       usr.name as assigned_name,
                       creator.name as creator_name
                FROM logbooks l
                LEFT JOIN units u ON l.unit_id = u.id
                LEFT JOIN logbook_templates t ON l.template_id = t.id
                LEFT JOIN users usr ON l.assigned_to = usr.id
                LEFT JOIN users creator ON l.created_by = creator.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['template_id'])) {
            $sql .= " AND l.template_id = ?";
            $params[] = $filters['template_id'];
        }
        if (!empty($filters['unit_id'])) {
            $sql .= " AND l.unit_id = ?";
            $params[] = $filters['unit_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND l.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['priority'])) {
            $sql .= " AND l.priority = ?";
            $params[] = $filters['priority'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (l.title LIKE ? OR l.description LIKE ? OR l.ticket_number LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY l.id DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT l.*, 
                                     u.name as unit_name, 
                                     t.name as template_name, 
                                     usr.name as assigned_name,
                                     creator.name as creator_name
                              FROM logbooks l
                              LEFT JOIN units u ON l.unit_id = u.id
                              LEFT JOIN logbook_templates t ON l.template_id = t.id
                              LEFT JOIN users usr ON l.assigned_to = usr.id
                              LEFT JOIN users creator ON l.created_by = creator.id
                              WHERE l.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function getStats(): array {
        $db = Database::getConnection();
        $total = $db->query("SELECT COUNT(*) FROM logbooks")->fetchColumn();
        $open = $db->query("SELECT COUNT(*) FROM logbooks WHERE status = 'Open'")->fetchColumn();
        $proses = $db->query("SELECT COUNT(*) FROM logbooks WHERE status = 'Proses'")->fetchColumn();
        $selesai = $db->query("SELECT COUNT(*) FROM logbooks WHERE status = 'Selesai'")->fetchColumn();

        return [
            'total' => $total,
            'open' => $open,
            'proses' => $proses,
            'selesai' => $selesai
        ];
    }

    public static function create(array $data): int {
        $db = Database::getConnection();
        $ticket = 'LOG-' . date('Ymd') . '-' . str_pad((string)rand(1, 999), 3, '0', STR_PAD_LEFT);
        $stmt = $db->prepare("INSERT INTO logbooks 
            (ticket_number, template_id, unit_id, category, priority, status, title, description, action_taken, assigned_to, created_by, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $ticket,
            $data['template_id'] ?? 1,
            $data['unit_id'] ?? 1,
            $data['category'] ?? 'SIMRS',
            $data['priority'] ?? 'Sedang',
            $data['status'] ?? 'Open',
            $data['title'],
            $data['description'],
            $data['action_taken'] ?? '',
            $data['assigned_to'] ?? null,
            $data['created_by'] ?? 1
        ]);
        return (int)$db->lastInsertId();
    }

    public static function updateStatus(int $id, string $status): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE logbooks SET status = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
