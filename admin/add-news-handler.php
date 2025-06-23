<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $brief = $_POST['brief'];
    $category = $_POST['category'];

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['image'])) {
        $targetDir = "../uploads/";
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
    }

    $stmt = $conn->prepare("INSERT INTO news (title, brief, image, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $brief, $imagePath, $category);
    $stmt->execute();

    header("Location: add-news.php?success=1");
    exit();
}
?>
