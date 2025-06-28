<?php 
include("db.php");
session_start();
ob_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Shorts - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            width: 100%;
            flex: 1;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            z-index: 1030;
            padding-top: 56px;
            transition: all 0.3s;
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        #mainContent {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0;
            margin-left: 250px;
            width: calc(100% - 250px);
            transition: all 0.3s;
        }

        #mainContent.expanded {
            width: 100%;
            margin-left: 0;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            height: 56px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1020;
            transition: all 0.3s;
        }

        #mainContent.expanded .navbar {
            left: 0;
            width: 100%;
        }

        .dashboard-content {
            flex: 9;
            padding: 20px;
            padding-top: 76px;
            min-height: calc(100vh - 0px);
            overflow-y: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Shorts specific styling */
        .shorts-container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }

        .shorts-section {
            border: 2px solid #007bff;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .shorts-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding-top: 0;
            }

            #mainContent {
                margin-left: 0;
                width: 100%;
            }

            .navbar {
                left: 0;
                width: 100%;
            }

            .dashboard-content {
                padding: 15px;
                padding-top: 70px;
            }

            .shorts-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar bg-white">
        <?php include 'partials/sidebar.php'; ?>
    </div>
    
    <!-- Main Content -->
    <div id="mainContent">
        <?php include 'partials/navbar.php'; ?>

        <div class="dashboard-content">
            <div class="shorts-container">
                <h1 class="text-center mb-4">Add Shorts</h1>
                
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
                            unlink($videoRow['video_path']);
                        }
                    }

                    $deleteStmt = $conn->prepare("DELETE FROM shorts WHERE id = ?");
                    $deleteStmt->bind_param("i", $deleteId);
                    $deleteStmt->execute();
                    $_SESSION['shorts_deleted'] = "🗑️ Short deleted successfully!";
                    header("Location: add-short.php");
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
                        header("Location: add-short.php");
                        exit();
                    } else {
                        echo "<div class='alert alert-warning'>⚠️ Failed to upload video.</div>";
                    }
                }
                ?>

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

                            <button type="submit" class="btn btn-primary btn-lg w-100">
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
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('[data-bs-toggle="sidebar"]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        });
    }
});

// Form validation for shorts
document.getElementById('shortsUploadForm')?.addEventListener('submit', function(e) {
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
    
    if (videoFile.size > 52428800) {
        alert('File size (' + (videoFile.size / 1024 / 1024).toFixed(2) + 'MB) exceeds 50MB limit.');
        e.preventDefault();
        return false;
    }
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Uploading...';
    submitButton.disabled = true;
    
    setTimeout(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }, 30000);
});

// Auto-hide alerts
setTimeout(() => {
    document.querySelectorAll('.auto-hide').forEach(el => el.style.display = 'none');
}, 2000);
</script>
<?php ob_end_flush(); ?>
<script src="script.js"></script>
</body>
</html>