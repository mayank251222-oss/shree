<?php
// BookPrinters & ShreePublications Production Portal
// Update these values with your hosting MySQL details before installation.
define('DB_HOST', 'localhost');
define('DB_NAME', 'YOUR_DATABASE_NAME');
define('DB_USER', 'YOUR_DATABASE_USER');
define('DB_PASS', 'YOUR_DATABASE_PASSWORD');
define('APP_NAME', 'BookPrinters & ShreePublications');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function logged_in(): bool { return !empty($_SESSION['user']); }
function current_user(): array { return $_SESSION['user'] ?? []; }
function require_login(): void {
    if (!logged_in()) { header('Location: index.php'); exit; }
}
function is_admin(): bool { return logged_in() && (current_user()['role'] ?? '') === 'admin'; }
function require_admin(): void {
    require_login();
    if (!is_admin()) { http_response_code(403); exit('Access denied. Admin only.'); }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sent = $_POST['csrf'] ?? '';
        if (!hash_equals(csrf_token(), $sent)) {
            http_response_code(419); exit('Session expired. Please go back, refresh, and try again.');
        }
    }
}
function flash(string $message, string $type='success'): void {
    $_SESSION['flash'] = ['message'=>$message, 'type'=>$type];
}
function redirect(string $url='dashboard.php'): void { header('Location: '.$url); exit; }
function stages(): array {
    return [
        'printing' => ['label'=>'Printing', 'employee'=>'Bhoomika Mahawar', 'step'=>1],
        'binding' => ['label'=>'Binding', 'employee'=>'Ashok Kumar Saini', 'step'=>2],
        'cutting' => ['label'=>'Cutting', 'employee'=>'Rahul Saini', 'step'=>3],
        'lamination' => ['label'=>'Lamination', 'employee'=>'Rahul Saini', 'step'=>4],
        'packing' => ['label'=>'Packing', 'employee'=>'Rahul Saini', 'step'=>5],
    ];
}
function stage_status_label(string $status): string {
    return ['pending'=>'Pending', 'in_progress'=>'In progress', 'completed'=>'Completed'][$status] ?? ucfirst($status);
}
