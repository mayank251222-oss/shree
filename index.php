<?php
require_once __DIR__ . '/config.php';
if (logged_in()) { redirect('dashboard.php'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $stmt = db()->prepare("SELECT id,full_name,username,password_hash,role,assigned_stage,active FROM users WHERE username=? LIMIT 1");
        $stmt->execute([trim($_POST['username'] ?? '')]);
        $user = $stmt->fetch();
        if ($user && (int)$user['active'] === 1 && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
            session_regenerate_id(true);
            unset($user['password_hash'], $user['active']);
            $_SESSION['user'] = $user;
            redirect('dashboard.php');
        }
        $error = 'Username or password is incorrect, or this account is inactive.';
    } catch (Throwable $e) {
        $error = 'Database not configured yet. Check config.php, import schema.sql, then open setup.php.';
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#101d3a"><title>Login | <?=e(APP_NAME)?></title><link rel="stylesheet" href="assets/style.css"></head><body class="login-body"><main class="login-layout"><section class="login-visual"><div class="brand-lockup"><div class="brand-mark">BP</div><div><strong>BOOKPRINTERS</strong><span>& SHREEPUBLICATIONS</span></div></div><div class="hero-copy"><p class="eyebrow">PRODUCTION CONTROL CENTER</p><h1>Every book.<br><em>Every stage.</em><br>One dashboard.</h1><p>Track printing, binding, cutting, lamination and packing from one place.</p></div><div class="login-foot">Made for your production floor · <?=date('Y')?></div></section><section class="login-panel"><div class="mobile-brand"><div class="brand-mark">BP</div><strong>BookPrinters Portal</strong></div><div class="login-card-inner"><p class="eyebrow">EMPLOYEE & ADMIN ACCESS</p><h2>Welcome back</h2><p class="muted">Sign in to view your work and update production status.</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Username</label><input name="username" required autocomplete="username" placeholder="Enter your username"><label>Password</label><input type="password" name="password" required autocomplete="current-password" placeholder="Enter your password"><button class="btn primary full" type="submit">Sign in <span>→</span></button></form><div class="login-note"><span class="lock">▣</span> Secure employee access. Contact your admin if you need an account.</div></div></section></main></body></html>
