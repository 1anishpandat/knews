<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - Karnataka News</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

</head>
<body>
<div class="d-flex">
  <?php include 'partials/sidebar.php'; ?>
  <div class="flex-grow-1">
    <?php include 'partials/navbar.php'; ?>

    <!-- Profile Section -->
    <div class="container mt-5">
      <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body text-center">
          <h4 class="mb-4">My Profile</h4>
          <img src="https://www.gravatar.com/avatar?d=mp" alt="Profile Picture" id="profileImage" class="rounded-circle mb-3 profile-pic">

          <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
            <input type="file" id="uploadInput" accept="image/*" hidden>
            <button type="button" class="btn btn-outline-primary" id="uploadBtn">Upload New Image</button>
            <button type="button" class="btn btn-outline-danger" id="removeBtn">Remove Photo</button>
          </div>

          <form>
            <div class="mb-3 text-start">
              <label for="fullName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullName" placeholder="Enter your Full Name">
            </div>

            <div class="mb-4 text-start">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email" placeholder="Enter your E-mail">
            </div>

            <button type="submit" class="btn btn-primary w-100">Edit Profile</button>
          </form>
        </div>
      </div>

      <div class="back-button-container text-center mt-3">
        <a href="index.php" class="btn btn-secondary">← Back</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
