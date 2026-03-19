<?php
require_once 'includes/functions.php';
redirectIfLoggedIn();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $statement = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
    $statement->execute([$email]);
    $user = $statement->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        setFlash('flash_success', 'Welcome back!');
        header('Location: dashboard.php');
        exit;
    }

    $errors[] = 'Invalid email or password.';
}

$pageTitle = 'Login';
require_once 'header.php';
?>
<div class="auth-shell mx-auto" style="max-width: 520px;">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="text-center mb-4">
                <span class="section-kicker">Welcome back</span>
                <h1 class="h2 fw-bold">Log in to your vault</h1>
                <p class="text-secondary mb-0">Access your notes, linked ideas, and dashboards.</p>
            </div>
            <?php if ($errors): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>
            <form method="post" class="row g-3">
                <div class="col-12"><label class="form-label">Email</label><input type="email" name="email" class="form-control form-control-lg" required></div>
                <div class="col-12"><label class="form-label">Password</label><input type="password" name="password" class="form-control form-control-lg" required></div>
                <div class="col-12 d-grid"><button type="submit" class="btn btn-primary btn-lg rounded-pill">Login</button></div>
            </form>
            <p class="text-center text-secondary mt-4 mb-0">Need an account? <a href="register.php">Create one</a>.</p>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>
