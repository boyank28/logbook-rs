<?php
require_once __DIR__ . '/../Models/User.php';

class MasterController {
    public function units() {
        $units = Unit::getAll();
        $title = "Master Unit - Logbook RS";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/master/units.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function unitStore() {
        $user = auth_user();
        $code = $_POST['code'] ?? '';
        $name = $_POST['name'] ?? '';
        if (!empty($code) && !empty($name)) {
            Unit::create($code, $name);
            AuditLog::log(null, $user['id'] ?? 1, 'Tambah Unit ' . $name, 'Unit baru ' . $code . ' ditambahkan.');
        }
        redirect(BASE_URL . "/index.php?route=master_units");
    }

    public function unitUpdate() {
        $user = auth_user();
        $id = (int)($_POST['id'] ?? 0);
        $code = $_POST['code'] ?? '';
        $name = $_POST['name'] ?? '';
        if ($id > 0 && !empty($code) && !empty($name)) {
            Unit::update($id, $code, $name);
            AuditLog::log(null, $user['id'] ?? 1, 'Ubah Unit ' . $name, 'Unit ' . $code . ' diperbarui.');
        }
        redirect(BASE_URL . "/index.php?route=master_units");
    }

    public function unitDelete() {
        $user = auth_user();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            Unit::delete($id);
            AuditLog::log(null, $user['id'] ?? 1, 'Hapus Unit ID #' . $id, 'Unit dihapus dari sistem.');
        }
        redirect(BASE_URL . "/index.php?route=master_units");
    }

    public function categories() {
        $title = "Master Kategori - Logbook RS";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/master/categories.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function priorities() {
        $title = "Master Prioritas - Logbook RS";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/master/priorities.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function users() {
        $users = User::getAll();
        $units = Unit::getAll();
        $title = "Master User - Logbook RS";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/master/users.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function userStore() {
        $authUser = auth_user();
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? 'password123';
        $roleId = (int)($_POST['role_id'] ?? 3);
        $unitId = !empty($_POST['unit_id']) ? (int)$_POST['unit_id'] : null;

        if (!empty($name) && !empty($email)) {
            User::create($name, $email, $password, $roleId, $unitId);
            AuditLog::log(null, $authUser['id'] ?? 1, 'Tambah User ' . $name, 'User baru ' . $email . ' dibuat.');
        }
        redirect(BASE_URL . "/index.php?route=master_users");
    }

    public function userUpdate() {
        $authUser = auth_user();
        $id = (int)($_POST['id'] ?? 0);
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $roleId = (int)($_POST['role_id'] ?? 3);
        $unitId = !empty($_POST['unit_id']) ? (int)$_POST['unit_id'] : null;

        if ($id > 0 && !empty($name) && !empty($email)) {
            User::update($id, $name, $email, $roleId, $unitId);
            AuditLog::log(null, $authUser['id'] ?? 1, 'Ubah User ' . $name, 'Informasi user ' . $email . ' diperbarui.');
        }
        redirect(BASE_URL . "/index.php?route=master_users");
    }

    public function userDelete() {
        $authUser = auth_user();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            User::delete($id);
            AuditLog::log(null, $authUser['id'] ?? 1, 'Hapus User ID #' . $id, 'User dihapus dari sistem.');
        }
        redirect(BASE_URL . "/index.php?route=master_users");
    }
}
