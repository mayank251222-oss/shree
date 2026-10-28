<?php
require_once __DIR__ . '/config.php';
$error = '';
$success = '';
try {
    $pdo = db();
    $count = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
    if ($count > 0) {
        $success = 'An admin account already exists. For security, setup is locked.';
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $name = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($name === '' || $username === '' || strlen($password) < 10) {
            $error = 'Name and username are required. Password must be at least 10 characters.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (full_name,username,password_hash,role) VALUES (?,?,?,'admin')");
            $stmt->execute([$name, $username, password_hash($password, PASSWORD_DEFAULT)]);
            $success = 'Admin created. Sign in, then delete setup.php from your server.';
        }
    }
} catch (Throwable $e) {
    $error = 'Database connection failed. Check config.php and import schema.sql first. Details: ' . $e->getMessage();
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Portal Setup</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-body"><main class="login-card"><div class="brand-mark">BP</div><p class="eyebrow">ONE-TIME INSTALLATION</p><h1>Create Admin</h1><p class="muted">Set up the owner account for your production portal.</p>
<?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><?php if($success): ?><div class="alert success"><?=e($success)?></div><?php endif; ?>
<?php if(!$success || strpos($success,'already exists')===false): ?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Admin full name</label><input name="full_name" required maxlength="120"><label>Username</label><input name="username" required maxlength="80" autocomplete="username"><label>Password (minimum 10 characters)</label><input type="password" name="password" required minlength="10" autocomplete="new-password"><button class="btn primary full" type="submit">Create admin account</button></form><?php endif; ?>
<p class="small muted">After setup, remove <code>setup.php</code> from public_html.</p></main></body></html>
