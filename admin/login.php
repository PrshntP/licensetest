<?php
session_start();
if ($_POST) {
    if ($_POST['user'] === 'prash' && $_POST['pass'] === 'prash') {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit;
    }
    $error = "Invalid Login!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #121212; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #1e1e1e; border: 1px solid #333; width: 100%; max-width: 350px; border-radius: 12px; }
        .form-control { background: #2d2d2d; border: 1px solid #444; color: white; }
        .form-control:focus { background: #333; color: white; border-color: #0d6efd; box-shadow: none; }
    </style>
</head>
<body>
    <div class="login-card p-4 shadow text-center">
        <h4 class="text-white mb-4">Admin Portal</h4>
        <form method="post">
            <input name="user" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="pass" class="form-control mb-3" placeholder="Password" required>
            <button class="btn btn-primary w-100 mb-2">Login</button>
            <a href="../index.php" class="btn btn-link btn-sm text-secondary text-decoration-none">Back to Quiz</a>
            <?php if(isset($error)): ?><p class="text-danger mt-3 small"><?= $error ?></p><?php endif; ?>
        </form>
    </div>
</body>
</html>