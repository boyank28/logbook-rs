<?php
function json_response(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function redirect(string $url): void {
    header("Location: " . $url);
    exit();
}
