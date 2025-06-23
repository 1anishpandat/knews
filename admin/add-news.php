
<?php include("db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Add News - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Bootstrap CSS & Icons -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<!-- Your custom CSS - only once -->
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'partials/navbar.php'; ?>


<div class="container py-4">

    <h2 class="mb-4">Add News to Sections</h2>

    <?php
$categories = [
    "Trending", "Carousel", "Ad", "Featured News", "Health", "Business", "Technology",
    "Entertainment", "Politics", "Popular News", "Sports", "Trending News"
];
// Handle Delete Request
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Fetch image path before deletion to remove the file
    $getImage = $conn->prepare("SELECT image FROM news WHERE id = ?");
    $getImage->bind_param("i", $deleteId);
    $getImage->execute();
    $imageResult = $getImage->get_result();
    if ($imgRow = $imageResult->fetch_assoc()) {
        if (file_exists($imgRow['image'])) {
            unlink($imgRow['image']); // Delete image file
        }
    }

    $deleteStmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    echo "<div class='alert alert-danger' id='delete-alert'>🗑️ News deleted successfully!</div>";


}
// Handle Upload Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['brief'], $_FILES['image'])) {
    $category = trim($_POST['category']);

    $title = $_POST['title'];
    $brief = $_POST['brief'];
    $imagePath = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir);
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    if ($imagePath !== "") {
        $stmt = $conn->prepare("INSERT INTO news (category, title, brief, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $category, $title, $brief, $imagePath);
        $stmt->execute();
        echo "<div class='alert alert-success auto-hide'>✅ News added successfully to <strong>$category</strong>!</div>";

    } else {
        echo "<div class='alert alert-warning'>⚠️ Failed to upload image.</div>";
    }
}

?>


<?php foreach ($categories as $category): ?>
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <?php echo $category; ?>
        </div>
        <div class="card-body">
        <select name="category" class="form-select mb-3" readonly>
<option selected><?php echo $category; ?></option>
</select>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
<div class="mb-3">
    <label class="form-label">News Title</label>
    <input type="text" class="form-control" name="title" required>
</div>
<div class="mb-3">
    <label class="form-label">Brief Statement</label>
    <textarea class="form-control" name="brief" rows="2" required></textarea>
</div>
<div class="mb-3">
    <label class="form-label">Upload Image</label>
    <input type="file" class="form-control" name="image" accept="image/*" required>
</div>
<button type="submit" class="btn btn-primary">Upload News</button>
</form>



            <!-- Show existing news under this category -->
            <hr class="my-4">
            <h6 class="mb-3">Existing News in "<?php echo $category; ?>"</h6>
            <?php
        $currentCategory = trim($category); // Trim any spaces
        $news = $conn->prepare("SELECT id, title FROM news WHERE category = ? ORDER BY created_at DESC LIMIT 5");
        $news->bind_param("s", $currentCategory);
        
            $news->execute();
            $newsResult = $news->get_result();
            if ($newsResult->num_rows > 0) {
                while ($row = $newsResult->fetch_assoc()) {
                    echo '<div class="d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2">';
                    echo '<span>' . htmlspecialchars($row['title']) . '</span>';
                    echo '<form method="POST" onsubmit="return confirm(\'Are you sure to delete this news?\')">';
                    echo '<input type="hidden" name="delete_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="btn btn-sm btn-danger">Delete</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted">No news found.</p>';
            }
            ?>
        </div>
    </div>
<?php endforeach; ?>
<script>
setTimeout(function() {
    const alert = document.getElementById('delete-alert');
    if (alert) {
    alert.style.display = 'none';
    }
}, 2000); // 2000 milliseconds = 2 seconds
</script>

<script>
setTimeout(function() {
    document.querySelectorAll('.auto-hide').forEach(function(el) {
    el.style.display = 'none';
    });
}, 2000); // 2 seconds
</script>