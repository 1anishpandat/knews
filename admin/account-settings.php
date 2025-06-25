<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header("Location: login.php");
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Settings - Karnataka News</title>
  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Your custom CSS - only once -->
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
 

</head>
<body>
<div class="d-flex">
  <?php include 'partials/sidebar.php'; ?>
  <div class="flex-grow-1">
    <?php include 'partials/navbar.php'; ?>

    <!-- Account Settings -->
    <div class="dashboard-content" style="margin-top: -550px !important; padding-top: 0 !important;">
      <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body">
          <h4 class="mb-4">Account Settings</h4>

          <form>
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" placeholder="Enter your username">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" placeholder="Enter your email">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" class="form-control" id="password" placeholder="Enter new password">
            </div>

            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password">
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Settings</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
