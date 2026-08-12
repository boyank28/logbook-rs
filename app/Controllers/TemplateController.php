<?php
require_once __DIR__ . '/../Models/LogbookTemplate.php';
require_once __DIR__ . '/../Models/LogbookField.php';

class TemplateController {
    public function index() {
        $templates = LogbookTemplate::getAll();
        $title = "Template Field - Logbook RS";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/templates/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        if (!empty($name)) {
            $id = LogbookTemplate::create($name, $description);
            redirect(BASE_URL . "/index.php?route=template_edit&id=" . $id);
        }
        redirect(BASE_URL . "/index.php?route=templates");
    }

    public function edit() {
        $id = (int)($_GET['id'] ?? 1);
        $template = LogbookTemplate::find($id);
        $fields = LogbookField::getByTemplate($id);
        $title = "Edit Template : " . ($template['name'] ?? 'Template');

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/templates/edit.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function saveFields() {
        $templateId = (int)($_POST['template_id'] ?? 0);
        $fieldsJson = $_POST['fields_json'] ?? '[]';
        $fields = json_decode($fieldsJson, true);

        if ($templateId > 0 && is_array($fields)) {
            LogbookField::saveFieldsForTemplate($templateId, $fields);
        }
        redirect(BASE_URL . "/index.php?route=template_edit&id=" . $templateId);
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            LogbookTemplate::delete($id);
        }
        redirect(BASE_URL . "/index.php?route=templates");
    }
}
