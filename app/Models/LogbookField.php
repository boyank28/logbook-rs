<?php
require_once __DIR__ . '/../../config/database.php';

if (!class_exists('LogbookField')) {
    class LogbookField {
        public static function getByTemplate(int $templateId): array {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM logbook_fields WHERE template_id = ? ORDER BY sort_order ASC, id ASC");
            $stmt->execute([$templateId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                // Default fields fallback for demo
                return [
                    ['id' => 1, 'template_id' => $templateId, 'label' => 'Lokasi / Ruangan', 'field_type' => 'text', 'is_required' => 1, 'placeholder' => 'Contoh: IGD, Rawat Jalan', 'field_options' => '', 'sort_order' => 1],
                    ['id' => 2, 'template_id' => $templateId, 'label' => 'Rincian Problem', 'field_type' => 'textarea', 'is_required' => 1, 'placeholder' => 'Jelaskan kronologi kejadian...', 'field_options' => '', 'sort_order' => 2],
                    ['id' => 3, 'template_id' => $templateId, 'label' => 'IP Address', 'field_type' => 'text', 'is_required' => 0, 'placeholder' => '192.168.1.100', 'field_options' => '', 'sort_order' => 3]
                ];
            }
            return $rows;
        }

        public static function saveFieldsForTemplate(int $templateId, array $fields): bool {
            $db = Database::getConnection();
            // Delete existing fields for this template
            $stmt = $db->prepare("DELETE FROM logbook_fields WHERE template_id = ?");
            $stmt->execute([$templateId]);

            // Insert new/updated fields
            $insert = $db->prepare("INSERT INTO logbook_fields (template_id, label, field_type, is_required, placeholder, field_options, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $order = 1;
            foreach ($fields as $f) {
                $label = trim($f['label'] ?? '');
                if (empty($label)) continue;
                
                $insert->execute([
                    $templateId,
                    $label,
                    $f['field_type'] ?? 'text',
                    !empty($f['is_required']) ? 1 : 0,
                    $f['placeholder'] ?? '',
                    $f['field_options'] ?? '',
                    $order++
                ]);
            }
            return true;
        }
    }
}
