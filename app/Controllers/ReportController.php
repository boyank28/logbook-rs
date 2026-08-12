<?php
require_once __DIR__ . '/../Models/Logbook.php';
require_once __DIR__ . '/../Models/AuditLog.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Unit.php';

class ReportController {
    public function index() {
        $stats = Logbook::getStats();
        $templates = LogbookTemplate::getAll();
        $units = Unit::getAll();
        $title = "Laporan Logbook - Logbook RS";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/reports/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function exportExcel() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=laporan_logbook_rs_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        // Add BOM for Excel UTF-8 compatibility
        fputs($output, "\xEF\xBB\xBF");

        // Header Row
        fputcsv($output, ['No Ticket', 'Jenis Logbook', 'Unit', 'Kategori', 'Prioritas', 'Status', 'Judul', 'Petugas', 'Tanggal']);

        $logbooks = Logbook::getAll();
        foreach ($logbooks as $row) {
            fputcsv($output, [
                $row['ticket_number'],
                $row['template_name'] ?? 'Log Gangguan',
                $row['unit_name'] ?? 'General',
                $row['category'],
                $row['priority'],
                $row['status'],
                $row['title'],
                $row['assigned_name'] ?? 'Budi',
                date('d-m-Y H:i', strtotime($row['created_at']))
            ]);
        }
        fclose($output);
        exit();
    }

    public function settings() {
        $settings = get_app_settings();
        $title = "Pengaturan Aplikasi - Logbook RS";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/settings/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function settingsSave() {
        $settings = [
            'app_name' => $_POST['app_name'] ?? 'LOGBOOK DINAMIS',
            'app_subtitle' => $_POST['app_subtitle'] ?? 'RUMAH SAKIT',
            'app_icon' => $_POST['app_icon'] ?? '+',
            'sidebar_color' => $_POST['sidebar_color'] ?? '#0f172a'
        ];
        save_app_settings($settings);
        redirect(BASE_URL . "/index.php?route=settings");
    }

    public function auditLog() {
        $logs = AuditLog::getAll();
        $title = "Audit Log - Logbook RS";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/reports/audit_log.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }
}
