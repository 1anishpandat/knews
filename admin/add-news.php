  <?php include("db.php");
  session_start();
  ob_start(); 

  ?>

  <!DOCTYPE html>
  <html>
  <head>
      <title>Add News - Admin Panel</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap CSS & Icons -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Your custom CSS - only once -->
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

      <!-- TinyMCE Editor -->
      <!-- Place the first <script> tag in your HTML's <head> -->
      <script src="https://cdn.tiny.cloud/1/mtizea6vlh1zmz5gvzrqhmx3aw13ve5dm9djsq70q5cezhi8/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

      <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
      <script>
      tinymce.init({
          selector: 'textarea.rich-editor',
          plugins: [
          // Core editing features
          'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
          // Your account includes a free trial of TinyMCE premium features
          // Try the most popular premium features until Jul 7, 2025:
          'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
          ],
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Author name',
          mergetags_list: [
          { value: 'First.Name', title: 'First Name' },
          { value: 'Email', title: 'Email' },
          ],
          ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
      });
      </script>

  </head>
  <body>
  
  <style>
      /* Fix for main content positioning and responsive behavior */
      .wrapper {
        min-height: 100vh;
      }
    
      #mainContent {
        flex: 1;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 0;
      }
    
      /* Force remove ALL spacing between navbar and content */
      #mainContent > * {
        margin: 0 !important;
        padding: 0 !important;
      }
    
      #mainContent .navbar,
      #mainContent nav,
      .navbar,
      nav {
        margin: 0 !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
      }
    
      .dashboard-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start; /* Align items to start */
        text-align: left;
        padding: 20px !important; /* Add some padding for better spacing */
        margin: 0 !important; /* Remove auto centering */
        margin-top: 0 !important;
        margin-left: 25% !important; /* Shift closer to right but not fully */
        padding-top: 0px !important; /* Remove top padding to eliminate space */
        /* Make it stick directly to navbar */
        position: relative;
        top: 0;
        /* Bigger size container */
        max-width: 70%; /* Reduced width since we're positioning it right */
        width: 70%;
      }
    
      .dashboard-content h1,
      .dashboard-content h2 {
        margin: 0 !important;
        padding: 0 !important;
        margin-top: 0 !important;
        padding-top: 0 !important;
        margin-bottom: 20px !important; /* Add some bottom margin for spacing */
      }
      
      /* Remove Bootstrap default spacing */
      .container,
      .container-fluid,
      .row,
      .col,
      [class*="col-"] {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        padding-top: 0 !important;
      }
      
      /* Remove any potential spacing from included files */
      * {
        box-sizing: border-box;
      }
    
      /* Responsive adjustments for sidebar */
      @media (max-width: 768px) {
        .dashboard-content {
          padding: 15px !important;
          margin: 0 auto !important; /* Keep centered on mobile */
          margin-left: 0 !important; /* Reset left margin on mobile */
          padding-top: 0px !important; /* Remove top padding on mobile too */
          max-width: 95%; /* Make it wider on mobile */
          width: 95%;
          align-items: flex-start; /* Keep left alignment for content */
        }
      }
      
      /* Additional responsive breakpoint for tablets */
      @media (max-width: 1024px) and (min-width: 769px) {
        .dashboard-content {
          margin-left: 20% !important; /* Closer to right on tablets */
          max-width: 75%;
          width: 75%;
        }
      }
      
      /* For very large screens */
      @media (min-width: 1400px) {
        .dashboard-content {
          margin-left: 30% !important; /* Even closer to right on large screens */
          max-width: 65%;
          width: 65%;
        }
      }

      /* Shorts section styling */
      .shorts-section {
        border: 2px solid #007bff;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-bottom: 30px;
      }

      .shorts-section .card-header {
        background: rgba(0,0,0,0.2) !important;
        border-bottom: 1px solid rgba(255,255,255,0.2);
      }

      .shorts-section .form-control {
        background: rgba(255,255,255,0.9);
        border: 1px solid rgba(255,255,255,0.3);
      }

      .shorts-section .btn-primary {
        background: #28a745;
        border-color: #28a745;
        font-weight: bold;
      }

      .shorts-section .btn-primary:hover {
        background: #218838;
        border-color: #1e7e34;
      }
  </style>

  <div class="wrapper d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <div id="mainContent">
      <?php include 'partials/navbar.php'; ?>

      <div class="dashboard-content" style="margin-top: -620px !important; padding-top: 0 !important;">
        <h1>ADD News & Shorts</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success auto-hide">
      <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['deleted'])): ?>
    <div class="alert alert-danger auto-hide">
      <?php echo $_SESSION['deleted']; unset($_SESSION['deleted']); ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['shorts_success'])): ?>
    <div class="alert alert-success auto-hide">
      <?php echo $_SESSION['shorts_success']; unset($_SESSION['shorts_success']); ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['shorts_deleted'])): ?>
    <div class="alert alert-danger auto-hide">
      <?php echo $_SESSION['shorts_deleted']; unset($_SESSION['shorts_deleted']); ?>
    </div>
  <?php endif; ?>

        <?php
  $categories = [
      "Trending", "Carousel", "Ad", "Featured News", "Health", "Business", "Technology",
      "Entertainment", "Politics", "Popular News", "Sports", "Trending News"
  ];

  // Handle Shorts Delete Request
  if (isset($_POST['delete_shorts_id'])) {
      $deleteId = $_POST['delete_shorts_id'];

      // Fetch video path before deletion to remove the file
      $getVideo = $conn->prepare("SELECT video_path FROM shorts WHERE id = ?");
      $getVideo->bind_param("i", $deleteId);
      $getVideo->execute();
      $videoResult = $getVideo->get_result();
      if ($videoRow = $videoResult->fetch_assoc()) {
          if (file_exists($videoRow['video_path'])) {
              unlink($videoRow['video_path']); // Delete video file
          }
      }

      $deleteStmt = $conn->prepare("DELETE FROM shorts WHERE id = ?");
      $deleteStmt->bind_param("i", $deleteId);
      $deleteStmt->execute();
      $_SESSION['shorts_deleted'] = "🗑️ Short deleted successfully!";
      header("Location: add-news.php");
      exit();
  }

  // Handle Shorts Upload Request
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['shorts_description'], $_FILES['shorts_video'])) {
      $description = trim($_POST['shorts_description']);
      $videoPath = "";

      if (isset($_FILES['shorts_video']) && $_FILES['shorts_video']['error'] === 0) {
          $uploadDir = "uploads/shorts/";
          if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
          
          $videoName = time() . "_" . basename($_FILES['shorts_video']['name']);
          $targetPath = $uploadDir . $videoName;
          
          // Check file size (50MB limit)
          if ($_FILES['shorts_video']['size'] > 52428800) {
              echo "<div class='alert alert-warning'>⚠️ Video file is too large. Maximum size is 50MB.</div>";
          } else {
              if (move_uploaded_file($_FILES['shorts_video']['tmp_name'], $targetPath)) {
                  $videoPath = $targetPath;
              }
          }
      }

      if ($videoPath !== "") {
          $stmt = $conn->prepare("INSERT INTO shorts (description, video_path) VALUES (?, ?)");
          $stmt->bind_param("ss", $description, $videoPath);
          $stmt->execute();
          $_SESSION['shorts_success'] = "✅ Short uploaded successfully!";
          header("Location: add-news.php");
          exit();
      } else {
          echo "<div class='alert alert-warning'>⚠️ Failed to upload video.</div>";
      }
  }

  // Handle News Delete Request
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
      $_SESSION['deleted'] = "🗑️ News deleted successfully!";
      header("Location: add-news.php");
      exit();
  }

  // Handle News Upload Request
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
          $_SESSION['success'] = "✅ News added successfully to <strong>$category</strong>!";
          header("Location: add-news.php");
          exit();
      } else {
          echo "<div class='alert alert-warning'>⚠️ Failed to upload image.</div>";
      }
  }
  ?>



  <!-- NEWS SECTIONS -->
  <?php foreach ($categories as $category): ?>
    <div class="card mb-4">
      <div class="card-header bg-dark text-white">
        <?php echo $category; ?>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">

          <div class="mb-3">
            <label class="form-label">News Title</label>
            <input type="text" class="form-control" name="title" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Brief Statement</label>
            <textarea class="form-control rich-editor" name="brief"></textarea>
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
        $currentCategory = trim($category);
        $news = $conn->prepare("SELECT id, title FROM news WHERE category = ? ORDER BY created_at DESC LIMIT 5");
        if (!$news) {
            die("Prepare failed: " . $conn->error);
        }
        if (!$news->bind_param("s", $currentCategory)) {
            die("Bind failed: " . $news->error);
        }
        
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
  <!-- SHORTS SECTION -->
  <div class="card mb-5 shorts-section">
      <div class="card-header text-white">
          <h4 class="mb-0"><i class="bi bi-play-circle-fill"></i> Upload Shorts</h4>
      </div>
      <div class="card-body">
          <form method="POST" enctype="multipart/form-data" id="shortsUploadForm">
              <div class="mb-3">
                  <label class="form-label text-white"><strong>Short Description</strong></label>
                  <textarea class="form-control" name="shorts_description" id="shorts_desc" rows="3" placeholder="Enter a catchy description for your short video..." required></textarea>
                  <small class="text-light">Keep it engaging and under 200 characters!</small>
              </div>

              <div class="mb-3">
                  <label class="form-label text-white"><strong>Upload Video</strong></label>
                  <input type="file" class="form-control" name="shorts_video" id="shorts_video" accept="video/*" required>
                  <small class="text-light">Supported formats: MP4, MOV, AVI (Max: 50MB)</small>
              </div>

              <button type="submit" class="btn btn-primary btn-lg">
                  <i class="bi bi-cloud-upload"></i> Upload Short
              </button>
          </form>

          <!-- Show existing shorts -->
          <hr class="my-4" style="border-color: rgba(255,255,255,0.3);">
          <h6 class="mb-3 text-white">Recent Shorts</h6>
          <?php
          $shorts = $conn->prepare("SELECT id, description, created_at FROM shorts ORDER BY created_at DESC LIMIT 5");
          $shorts->execute();
          $shortsResult = $shorts->get_result();
          if ($shortsResult->num_rows > 0) {
              while ($row = $shortsResult->fetch_assoc()) {
                  echo '<div class="d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3) !important;">';
                  echo '<div>';
                  echo '<span class="text-white">' . htmlspecialchars(substr($row['description'], 0, 50)) . (strlen($row['description']) > 50 ? '...' : '') . '</span>';
                  echo '<br><small class="text-light">Uploaded: ' . date('M j, Y g:i A', strtotime($row['created_at'])) . '</small>';
                  echo '</div>';
                  echo '<form method="POST" onsubmit="return confirm(\'Are you sure to delete this short?\')">';
                  echo '<input type="hidden" name="delete_shorts_id" value="' . $row['id'] . '">';
                  echo '<button type="submit" class="btn btn-sm btn-danger">Delete</button>';
                  echo '</form>';
                  echo '</div>';
              }
          } else {
              echo '<p class="text-light">No shorts uploaded yet.</p>';
          }
          ?>
      </div>
  </div>

  <script>
  // Add form validation and feedback for shorts
  document.getElementById('shortsUploadForm').addEventListener('submit', function(e) {
      const description = document.getElementById('shorts_desc').value.trim();
      const videoFile = document.getElementById('shorts_video').files[0];
      
      if (description.length < 5) {
          alert('Description must be at least 5 characters long.');
          e.preventDefault();
          return false;
      }
      
      if (!videoFile) {
          alert('Please select a video file.');
          e.preventDefault();
          return false;
      }
      
      // Check file size (50MB = 52428800 bytes)
      if (videoFile.size > 52428800) {
          alert('File size (' + (videoFile.size / 1024 / 1024).toFixed(2) + 'MB) exceeds 50MB limit.');
          e.preventDefault();
          return false;
      }
      
      // Show loading state
      const submitButton = this.querySelector('button[type="submit"]');
      const originalText = submitButton.innerHTML;
      submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Uploading...';
      submitButton.disabled = true;
      
      // Re-enable button after 30 seconds as fallback
      setTimeout(() => {
          submitButton.innerHTML = originalText;
          submitButton.disabled = false;
      }, 30000);
  });
  </script>

  <script>
  setTimeout(() => {
    const delAlert = document.getElementById('delete-alert');
    if (delAlert) delAlert.style.display = 'none';
    document.querySelectorAll('.auto-hide').forEach(el => el.style.display = 'none');
  }, 2000);
  </script>

  <script>
  setTimeout(function() {
      document.querySelectorAll('.auto-hide').forEach(function(el) {
          el.style.display = 'none';
      });
  }, 2000); // 2 seconds
  </script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <?php ob_end_flush(); ?>

    </body>
  </html>