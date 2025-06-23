<?php
include("admin/db.php");
if (!isset($_GET['id'])) {
    echo "No news ID provided.";
    exit;
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
  <p class="text-lg leading-relaxed"><?php echo nl2br(htmlspecialchars($row['brief'])); ?></p>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
} else {
    echo "News not found.";
}
?>

