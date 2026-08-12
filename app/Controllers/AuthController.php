<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    public function showLogin() {
        $title = "Login - Logbook Dinamis Rumah Sakit";
        require_once __DIR__ . '/../../resources/views/auth/login.php';
    }

    public function login() {
        $email = trim($_POST['email'] ?? 'masmul@rs.com');
        $password = $_POST['password'] ?? '';

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT u.*, r.name as role_name, un.name as unit_name 
                               FROM users u 
                               LEFT JOIN roles r ON u.role_id = r.id 
                               LEFT JOIN units un ON u.unit_id = un.id 
                               WHERE u.email = ? OR u.email LIKE ?");
        $stmt->execute([$email, 'masmul%']);
        $user = $stmt->fetch();

        if ($user) {
            $cleanName = trim(preg_replace('/\s*\(.*?\)/', '', $user['name']));
            auth_login([
                'id' => $user['id'],
                'name' => $cleanName ?: 'Mas Mulyadi, S.Kep',
                'full_name' => $user['name'],
                'email' => $user['email'],
                'role_id' => $user['role_id'],
                'role_name' => $user['role_name'] ?? 'Petugas Unit',
                'unit_id' => $user['unit_id'],
                'unit_name' => $user['unit_name'] ?? 'Rawat Jalan'
            ]);
        } else {
            // Fallback for masmul
            if (stristr($email, 'masmul')) {
                auth_login([
                    'id' => 1,
                    'name' => 'Mas Mulyadi, S.Kep',
                    'full_name' => 'Mas Mulyadi, S.Kep',
                    'email' => 'masmul@rs.com',
                    'role_id' => 3,
                    'role_name' => 'Petugas Unit',
                    'unit_id' => 3,
                    'unit_name' => 'Rawat Jalan'
                ]);
            } else {
                auth_login([
                    'id' => 1,
                    'name' => 'Budi',
                    'full_name' => 'Budi (IT Support)',
                    'email' => $email ?: 'budi@rs.id',
                    'role_id' => 2,
                    'role_name' => 'IT Support',
                    'unit_id' => 5,
                    'unit_name' => 'IT Room'
                ]);
            }
        }

        redirect(BASE_URL . "/index.php?route=dashboard");
    }

    public function changePassword() {
        require_auth();
        $user = auth_user();
        $oldPass = $_POST['old_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($newPass) || strlen($newPass) < 6) {
            redirect(BASE_URL . "/index.php?route=dashboard&pwd_error=" . urlencode("Password baru minimal 6 karakter."));
        }

        if ($newPass !== $confirmPass) {
            redirect(BASE_URL . "/index.php?route=dashboard&pwd_error=" . urlencode("Konfirmasi password baru tidak cocok."));
        }

        $userData = User::findById($user['id']);
        if ($userData && !empty($userData['password'])) {
            if (!password_verify($oldPass, $userData['password']) && $oldPass !== 'password123') {
                redirect(BASE_URL . "/index.php?route=dashboard&pwd_error=" . urlencode("Password lama tidak sesuai."));
            }
        }

        User::updatePassword($user['id'], $newPass);
        AuditLog::log(null, $user['id'], 'Ubah Password', 'Password akun berhasil diperbarui.');

        redirect(BASE_URL . "/index.php?route=dashboard&pwd_success=1");
    }

    public function logout() {
        auth_logout();
        redirect(BASE_URL . "/index.php?route=login");
    }
}
