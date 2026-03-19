<?php
require_once 'includes/functions.php';
redirectIfLoggedIn();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    $checkUser = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $checkUser->execute([$email]);
    if ($checkUser->fetch()) {
        $errors[] = 'An account with this email already exists.';
    }

    if (!$errors) {
        $statement = $pdo->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
        $statement->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT)]);
        $userId = (int) $pdo->lastInsertId();

        $defaultCategories = ['Learning', 'Business Ideas', 'Personal', 'Projects', 'Study', 'Finance'];
        $categoryStatement = $pdo->prepare('INSERT INTO categories (user_id, name, color) VALUES (?, ?, ?)');
        $palette = ['#7c3aed', '#2563eb', '#0f766e', '#f59e0b', '#db2777', '#475569'];
        foreach ($defaultCategories as $index => $category) {
            $categoryStatement->execute([$userId, $category, $palette[$index]]);
        }

        setFlash('flash_success', 'Account created successfully. Please log in.');
        header('Location: login.php');
        exit;
    }
}

$pageTitle = 'Create Account';
require_once 'header.php';
?>
<div class="auth-shell mx-auto" style="max-width: 560px;">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="text-center mb-4">
                <span class="section-kicker">Get started</span>
                <h1 class="h2 fw-bold">Create your knowledge vault</h1>
                <p class="text-secondary mb-0">Build a modern second-brain workspace in minutes.</p>
            </div>
            <?php if ($errors): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>
            <form method="post" class="row g-3">
                <div class="col-12"><label class="form-label">Full Name</label><input type="text" name="full_name" class="form-control form-control-lg" value="<?= h($_POST['full_name'] ?? '') ?>" required></div>
                <div class="col-12"><label class="form-label">Email</label><input type="email" name="email" class="form-control form-control-lg" value="<?= h($_POST['email'] ?? '') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control form-control-lg" required></div>
                <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" class="form-control form-control-lg" required></div>
                <div class="col-12 d-grid"><button type="submit" class="btn btn-primary btn-lg rounded-pill">Create Account</button></div>
            </form>
            <p class="text-center text-secondary mt-4 mb-0">Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>
