<!DOCTYPE html>
<html>
<head>
  <title>Test Sidebar Toggle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
    }

    #layoutWrapper {
      display: flex;
      width: 100%;
    }

    .sidebar {
      width: 250px;
      min-height: 100vh;
      background: #f8f9fa;
      transition: all 0.3s ease;
    }

    .sidebar.collapsed {
      width: 0;
      overflow: hidden;
    }

    #mainContent {
      width: calc(100% - 250px);
      transition: all 0.3s ease;
      padding: 20px;
    }

    .sidebar.collapsed + #mainContent {
      width: 100%;
    }

    .navbar {
      background-color: #e9ecef;
    }
  </style>
</head>
<body>

<div id="layoutWrapper">
  <!-- Sidebar -->
  <div id="sidebar" class="sidebar border-end">
    <ul class="nav flex-column p-3">
      <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Add News</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div id="mainContent">
    <!-- Top Navbar -->
    <nav class="navbar px-3">
      <button class="btn btn-outline-primary" id="sidebarToggle">&#9776;</button>
      <span class="ms-3">Add News Page</span>
    </nav>

    <!-- Page Content -->
    <div class="container mt-4">
      <h2>Add News</h2>
      <p>This is where your news form will go.</p>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toggle Script -->
<script>
  const toggleButton = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");

  toggleButton.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
  });
</script>

</body>
</html>
