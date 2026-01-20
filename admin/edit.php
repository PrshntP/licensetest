<?php
session_start();
if (!isset($_SESSION['admin'])) exit;

$questions = include "../questions.php";
$id = $_GET['id'] ?? null;

if ($id === null || !isset($questions[$id])) {
    header("Location: dashboard.php");
    exit;
}

$q = $questions[$id];

if ($_POST) {
    $questions[$id]['question'] = $_POST['q'];
    
    // Handle Image Upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $targetDir = "../images/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES["image_file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $targetFilePath)) {
            $questions[$id]['image'] = "images/" . $fileName;
        }
    } elseif (!empty($_POST['existing_image'])) {
        $questions[$id]['image'] = $_POST['existing_image'];
    }

    $questions[$id]['options'] = [
        1 => $_POST['o1'], 
        2 => $_POST['o2'], 
        3 => $_POST['o3'], 
        4 => $_POST['o4']
    ]; 
    $questions[$id]['answer'] = (int)$_POST['a'];

    file_put_contents("../questions.php", "<?php\nreturn " . var_export($questions, true) . ";\n");
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; padding: 20px; font-family: 'Inter', sans-serif; }
        .preview-img { max-height: 150px; border-radius: 8px; border: 1px solid #dee2e6; margin-top: 10px; }
        .main-card { max-width: 650px; margin: auto; }
    </style>
</head>
<body>
    <div class="container main-card shadow-sm bg-white p-4 p-md-5 rounded-4">
        <h3 class="mb-4 fw-bold text-primary">Edit Question</h3>
        
        <form method="post" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Question Text</label>
                <textarea name="q" class="form-control" rows="2" required><?= htmlspecialchars($q['question']) ?></textarea>
            </div>

            <div class="mb-4 p-3 bg-light rounded-3 border">
                <label class="form-label fw-semibold mb-2">Question Image</label>
                
                <?php if (!empty($q['image'])): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Current Preview:</small>
                        <img src="../<?= $q['image'] ?>" class="preview-img d-block mb-3">
                        <input type="hidden" name="existing_image" value="<?= $q['image'] ?>">
                    </div>
                <?php endif; ?>

                <input type="file" name="image_file" class="form-control" accept="image/*">
                <small class="text-muted mt-2 d-block">Choose a file only if you want to replace the current image.</small>
            </div>

            <div class="row g-3 mb-4">
                <?php 
                $cleanOptions = array_values($q['options']); 
                foreach($cleanOptions as $i => $opt): 
                ?>
                <div class="col-md-6">
                    <label class="small fw-bold text-secondary">Option <?= $i+1 ?></label>
                    <input name="o<?= $i+1 ?>" class="form-control" value="<?= htmlspecialchars($opt) ?>" required>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-success">Correct Option Index (1-4)</label>
                <input type="number" name="a" class="form-control" min="1" max="4" value="<?= $q['answer'] ?>" required>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary py-3 rounded-3 fw-bold">Save Changes</button>
                <a href="dashboard.php" class="btn btn-outline-secondary">Discard Changes</a>
            </div>
        </form>
    </div>
</body>
</html>