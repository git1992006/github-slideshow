<?php
require_once __DIR__ . '/includes/functions.php';
$user = currentUser($pdo);
$pageTitle = $pageTitle ?? 'Digital Second Brain';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light sticky-top glass-nav border-bottom">
    <div class="container py-2">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= isLoggedIn() ? 'dashboard.php' : 'index.php' ?>">
            <span class="brand-mark"><i class="bi bi-brilliance"></i></span>
            <span>Digital Second Brain</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="notes.php">Notes</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="tags.php">Tags</a></li>
                    <li class="nav-item"><a class="nav-link" href="archived_notes.php">Archive</a></li>
                    <li class="nav-item"><a class="btn btn-primary rounded-pill px-3" href="add_note.php">New Note</a></li>
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= h($user['full_name'] ?? 'Account') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" id="themeToggle" type="button"><i class="bi bi-moon-stars me-2"></i>Toggle dark mode</button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How it works</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary rounded-pill px-3" href="register.php">Get Started</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main>
    <div class="container py-4 py-lg-5">
        <?php if ($success = getFlash('flash_success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"><?= h($success) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($error = getFlash('flash_error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= h($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
