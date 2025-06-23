
<!-- Inside index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HSR News</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100 text-gray-800">

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/notification-bell.php'; ?>
<?php include 'includes/sidebar-follow.php'; ?>
<?php include 'includes/footer.php'; ?>
<!-- Back Button -->
<a href="index.php" class="fixed bottom-20 right-6 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition">
  ← Back to Home
</a>

</body>
</html>