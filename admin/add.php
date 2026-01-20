<?php
session_start();
if (!isset($_SESSION['admin'])) exit;
$questions = include "../questions.php";

if ($_POST) {
    $imgPath = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imgName = "q_" . time() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/$imgName");
        $imgPath = "images/$imgName";
    }
    $questions[] = [
        "question" => $_POST['q'],
        "image" => $imgPath,
        "options" => [$_POST['o1'], $_POST['o2'], $_POST['o3'], $_POST['o4']],
        "answer" => (int)$_POST['a']
    ];
    file_put_contents("../questions.php", "<?php\nreturn " . var_export($questions, true) . ";\n");
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{ background: #f4f7f6; padding: 40px; }</style>
</head>
<body>
    <div class="container shadow bg-white p-5 rounded" style="max-width: 600px;">
        <h3 class="mb-4">Add New Question</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Question Text</label>
                <input name="q" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Optional Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6"><input name="o1" class="form-control" placeholder="Option 1" required></div>
                <div class="col-6"><input name="o2" class="form-control" placeholder="Option 2" required></div>
                <div class="col-6"><input name="o3" class="form-control" placeholder="Option 3" required></div>
                <div class="col-6"><input name="o4" class="form-control" placeholder="Option 4" required></div>
            </div>
            <div class="mb-4">
                <label class="form-label">Correct Option (1-4)</label>
                <input type="number" name="a" class="form-control" min="1" max="4" required>
            </div>
            <button class="btn btn-primary w-100">Save Question</button>
            <a href="dashboard.php" class="btn btn-link w-100 mt-2 text-muted">Cancel</a>
        </form>
    </div>
</body>
</html>