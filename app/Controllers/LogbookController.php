<?php
require_once __DIR__ . '/../Models/Logbook.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Unit.php';
require_once __DIR__ . '/../Models/LogbookTemplate.php';
require_once __DIR__ . '/../Models/LogbookField.php';
require_once __DIR__ . '/../Models/AuditLog.php';

class LogbookController {
    public function index() {
        $filters = [
            'template_id' => $_GET['template'] ?? '',
            'unit_id' => $_GET['unit'] ?? '',
            'status' => $_GET['status'] ?? '',
            'priority' => $_GET['priority'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $totalItems = Logbook::countAll($filters);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
        $offset = ($currentPage - 1) * $perPage;

        $logbooks = Logbook::getAll($filters, $perPage, $offset);
        $units = Unit::getAll();
        $templates = LogbookTemplate::getAll();

        $selectedTemplate = !empty($filters['template_id']) ? LogbookTemplate::find((int)$filters['template_id']) : null;
        $title = ($selectedTemplate ? $selectedTemplate['name'] : 'Semua Logbook') . " - Logbook RS";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/logbook/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function create() {
        $templateId = (int)($_GET['template'] ?? 1);
        $template = LogbookTemplate::find($templateId);
        $fields = LogbookField::getByTemplate($templateId);
        $units = Unit::getAll();
        $users = User::getAll();
        $templates = LogbookTemplate::getAll();

        $title = "Tambah Logbook - " . ($template['name'] ?? 'Logbook RS');
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/logbook/create.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function store() {
        $user = auth_user();
        $data = [
            'template_id' => $_POST['template_id'] ?? 1,
            'unit_id' => $_POST['unit_id'] ?? 1,
            'category' => $_POST['category'] ?? 'SIMRS',
            'priority' => $_POST['priority'] ?? 'Sedang',
            'status' => $_POST['status'] ?? 'Open',
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'action_taken' => $_POST['action_taken'] ?? '',
            'assigned_to' => !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null,
            'created_by' => $user['id'] ?? 1
        ];

        $id = Logbook::create($data);
        AuditLog::log($id, $user['id'] ?? 1, 'Dibuat oleh ' . ($user['name'] ?? 'Petugas'), 'Logbook dibuat baru.');

        redirect(BASE_URL . "/index.php?route=logbook_detail&id=" . $id);
    }

    public function detail() {
        $id = (int)($_GET['id'] ?? 1);
        $logbook = Logbook::find($id);
        if (!$logbook) {
            redirect(BASE_URL . "/index.php?route=logbook");
        }

        $auditLogs = AuditLog::getByLogbook($id);
        $title = "Detail Logbook #" . $logbook['ticket_number'];

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/logbook/detail.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function updateStatus() {
        $id = (int)($_GET['id'] ?? 0);
        $status = $_GET['status'] ?? 'Selesai';
        $user = auth_user();

        if ($id > 0) {
            Logbook::updateStatus($id, $status);
            AuditLog::log($id, $user['id'] ?? 1, 'Status diubah ke ' . $status, 'Diupdate oleh ' . ($user['name'] ?? 'User'));
        }
        redirect(BASE_URL . "/index.php?route=logbook_detail&id=" . $id);
    }
}
