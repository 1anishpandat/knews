<?php
include("admin/db.php");
if (!isset($_GET['id'])) {
    echo "No news ID provided.";
    exit;
}



// Assume you get news ID from URL like ?id=123
$newsId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($newsId > 0) {
    $check = $conn->prepare("SELECT id FROM news_analytics WHERE news_id = ? AND date_clicked = CURDATE()");
    $check->bind_param("i", $newsId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $conn->query("UPDATE news_analytics SET clicks = clicks + 1 WHERE news_id = $newsId AND date_clicked = CURDATE()");
    } else {
        $stmt = $conn->prepare("INSERT INTO news_analytics (news_id) VALUES (?)");
        $stmt->bind_param("i", $newsId);
        $stmt->execute();
    }
    $check->close();
}


$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($row['title']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800">
<?php include 'includes/header.php'; ?>
<?php include 'includes/language-switcher.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/notification-bell.php'; ?>
<?php include 'includes/send-push.php'; ?>

<div class="max-w-4xl mx-auto p-6">
<a href="index.php" class="fixed bottom-20 right-6 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition">
  ← Back to Home
</a>
  <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($row['title']); ?></h1>
  <p class="text-sm text-gray-500 mb-2">Updated: <?php echo date("F j, Y", strtotime($row['created_at'])); ?></p>
  <p class="text-xs text-red-600 mb-4">Category: <?php echo htmlspecialchars($row['category']); ?></p>
  <img src="admin/<?php echo htmlspecialchars($row['image']); ?>" alt="News Image" class="w-full h-auto rounded mb-4" />
  <p class="text-lg leading-relaxed"><?php echo nl2br(($row['brief'])); ?></p>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
} else {
    echo "News not found.";
}
?>

