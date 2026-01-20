<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
$questions = include "../questions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 50px; }
        .q-row { background: white; border-radius: 8px; margin-bottom: 10px; border-left: 5px solid #0d6efd; }
    .card-stat {
    background: linear-gradient(45deg, #0d6efd, #0dcaf0);
    color: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 30px;
}
.table-hover tbody tr:hover {
    background-color: #f1f5f9;
}
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid container">
        <span class="navbar-brand">Quiz Management</span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Questions Pool</h2>
        <a href="add.php" class="btn btn-success">+ Add New Question</a>
    </div>

    <?php foreach ($questions as $i => $q): ?>
    <div class="q-row p-3 shadow-sm d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-1"><?= $q['question'] ?></h6>
            <small class="text-muted"><?= count($q['options']) ?> Options Available</small>
        </div>
        <div>
            <a href="edit.php?id=<?= $i ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="delete.php?id=<?= $i ?>" onclick="return confirm('Delete this question?')" class="btn btn-sm btn-outline-danger">Delete</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>