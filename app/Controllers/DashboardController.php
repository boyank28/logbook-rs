<?php
require_once __DIR__ . '/../Models/Logbook.php';

class DashboardController {
    public function index() {
        $stats = Logbook::getStats();
        $recentLogbooks = Logbook::getAll();

        $title = "Dashboard - Logbook Dinamis Rumah Sakit";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/dashboard/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }
}
