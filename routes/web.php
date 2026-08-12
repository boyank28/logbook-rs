<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/auth.php';
require_once __DIR__ . '/../app/Helpers/response.php';

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/LogbookController.php';
require_once __DIR__ . '/../app/Controllers/TemplateController.php';
require_once __DIR__ . '/../app/Controllers/MasterController.php';
require_once __DIR__ . '/../app/Controllers/ReportController.php';

$route = $_GET['route'] ?? 'dashboard';

// If user is not logged in, enforce login page
if (!auth_check() && !in_array($route, ['login', 'do_login'])) {
    redirect(BASE_URL . "/index.php?route=login");
}

// If user is logged in and visits login page, redirect to dashboard
if (auth_check() && $route === 'login') {
    redirect(BASE_URL . "/index.php?route=dashboard");
}

// Apply Role-Based Access Control (RBAC) Route Guards
switch ($route) {
    // Super Admin Only (Role ID 1)
    case 'master_users':
    case 'master_user_store':
    case 'master_user_update':
    case 'master_user_delete':
    case 'settings':
    case 'settings_save':
        require_role([1]);
        break;

    // Super Admin (1) & IT Support (2) Only
    case 'templates':
    case 'template_store':
    case 'template_edit':
    case 'template_save_fields':
    case 'template_delete':
    case 'master_units':
    case 'master_unit_store':
    case 'master_unit_update':
    case 'master_unit_delete':
    case 'master_categories':
    case 'master_priorities':
    case 'audit_log':
        require_role([1, 2]);
        break;
}

switch ($route) {
    case 'login':
        (new AuthController())->showLogin();
        break;
    case 'do_login':
        (new AuthController())->login();
        break;
    case 'change_password':
        (new AuthController())->changePassword();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    case 'dashboard':
        (new DashboardController())->index();
        break;

    case 'logbook':
        (new LogbookController())->index();
        break;
    case 'logbook_create':
        (new LogbookController())->create();
        break;
    case 'logbook_store':
        (new LogbookController())->store();
        break;
    case 'logbook_edit':
        (new LogbookController())->edit();
        break;
    case 'logbook_update':
        (new LogbookController())->update();
        break;
    case 'logbook_detail':
        (new LogbookController())->detail();
        break;
    case 'logbook_update_status':
        (new LogbookController())->updateStatus();
        break;

    case 'templates':
        (new TemplateController())->index();
        break;
    case 'template_store':
        (new TemplateController())->store();
        break;
    case 'template_edit':
        (new TemplateController())->edit();
        break;
    case 'template_save_fields':
        (new TemplateController())->saveFields();
        break;
    case 'template_delete':
        (new TemplateController())->delete();
        break;

    case 'master_units':
        (new MasterController())->units();
        break;
    case 'master_unit_store':
        (new MasterController())->unitStore();
        break;
    case 'master_unit_update':
        (new MasterController())->unitUpdate();
        break;
    case 'master_unit_delete':
        (new MasterController())->unitDelete();
        break;
    case 'master_categories':
        (new MasterController())->categories();
        break;
    case 'master_priorities':
        (new MasterController())->priorities();
        break;
    case 'master_users':
        (new MasterController())->users();
        break;
    case 'master_user_store':
        (new MasterController())->userStore();
        break;
    case 'master_user_update':
        (new MasterController())->userUpdate();
        break;
    case 'master_user_delete':
        (new MasterController())->userDelete();
        break;

    case 'reports':
        (new ReportController())->index();
        break;
    case 'export_excel':
        (new ReportController())->exportExcel();
        break;
    case 'settings':
        (new ReportController())->settings();
        break;
    case 'settings_save':
        (new ReportController())->settingsSave();
        break;
    case 'audit_log':
        (new ReportController())->auditLog();
        break;

    default:
        (new DashboardController())->index();
        break;
}
