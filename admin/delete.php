<?php
session_start();
if (!isset($_SESSION['admin'])) exit;

$questions = include "../questions.php";
$id = $_GET['id'];

if ($questions[$id]['image']) {
    $img = "../" . $questions[$id]['image'];
    if (file_exists($img)) unlink($img);
}

unset($questions[$id]);

file_put_contents("../questions.php",
    "<?php\nreturn " . var_export(array_values($questions), true) . ";\n"
);

header("Location: dashboard.php");
