<?php include("db.php");
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
    <title>Add News - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

    <!-- TinyMCE Editor -->
    <script src="https://cdn.tiny.cloud/1/mtizea6vlh1zmz5gvzrqhmx3aw13ve5dm9djsq70q5cezhi8/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: 'textarea.rich-editor',
        plugins: [
            'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 
            'searchreplace', 'table', 'visualblocks', 'wordcount', 'checklist', 'mediaembed', 'casechange', 
            'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 
            'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 
            'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
            'importword', 'exportword', 'exportpdf'
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
        align-items: flex-start;
        text-align: left;
        padding: 20px !important;
        margin: 0 !important;
        margin-top: 0 !important;
        margin-left: 25% !important;
        padding-top: 0px !important;
        position: relative;
        top: 0;
        max-width: 70%;
        width: 70%;
    }
  
    .dashboard-content h1,
    .dashboard-content h2 {
        margin: 0 !important;
        padding: 0 !important;
        margin-top: 0 !important;
        padding-top: 0 !important;
        margin-bottom: 20px !important;
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
            margin: 0 auto !important;
            margin-left: 0 !important;
            padding-top: 0px !important;
            max-width: 95%;
            width: 95%;
            align-items: flex-start;
        }
    }
    
    /* Additional responsive breakpoint for tablets */
    @media (max-width: 1024px) and (min-width: 769px) {
        .dashboard-content {
            margin-left: 20% !important;
            max-width: 75%;
            width: 75%;
        }
    }
    
    /* For very large screens */
    @media (min-width: 1400px) {
        .dashboard-content {
            margin-left: 30% !important;
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

    /* Toggle switch styling */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }

    /* URL preview styling */
    .url-preview {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 8px 12px;
        font-family: monospace;
        color: #495057;
    }
    .search-results {
        margin-top: 20px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        background-color: #f8f9fa;
    }
    .news-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    .news-item:last-child {
        border-bottom: none;
    }
    .news-image {
        max-width: 200px;
        height: auto;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 3px 8px;
        border-radius: 10px;
    }
    .status-public {
        background-color: #28a745;
        color: white;
    }
    .status-private {
        background-color: #dc3545;
        color: white;
    }
    /* Search input styling */
    #searchForm .form-control {
        border-left: none;
        padding-left: 0;
    }
    #searchForm .input-group-text {
        border-right: none;
        background-color: white;
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
                        unlink($videoRow['video_path']);
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

            // Handle News Upload Request
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['brief'], $_FILES['image'])) {
                $category = trim($_POST['category']);
                $title = $_POST['title'];
                $brief = $_POST['brief'];
                $author = isset($_POST['author']) ? $_POST['author'] : 'Admin';
                $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
                $status = isset($_POST['status']) ? 'private' : 'public';
                $imagePath = "";
                
                // Generate URL slug
                $urlSlug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $title), '-'));
                
                // Handle image upload
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
                    $stmt = $conn->prepare("INSERT INTO news (category, title, brief, image, author, keywords, url_slug, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssss", $category, $title, $brief, $imagePath, $author, $keywords, $urlSlug, $status);
                    $stmt->execute();
                    
                    // For Trending category, ensure only 2 latest items are marked as active
                    if ($category == "Trending") {
                        // Get IDs of the 2 most recent trending news
                        $trendingQuery = $conn->prepare("SELECT id FROM news WHERE category = 'Trending' ORDER BY created_at DESC LIMIT 2");
                        $trendingQuery->execute();
                        $trendingResult = $trendingQuery->get_result();
                        $activeIds = [];
                        while ($row = $trendingResult->fetch_assoc()) {
                            $activeIds[] = $row['id'];
                        }
                        
                        // Update all trending news to inactive except the 2 most recent
                        if (!empty($activeIds)) {
                            $placeholders = implode(',', array_fill(0, count($activeIds), '?'));
                            $updateStmt = $conn->prepare("UPDATE news SET is_active = CASE WHEN id IN ($placeholders) THEN 1 ELSE 0 END WHERE category = 'Trending'");
                            $updateStmt->bind_param(str_repeat('i', count($activeIds)), ...$activeIds);
                            $updateStmt->execute();
                        }
                    }
                    
                    $_SESSION['success'] = "✅ News added successfully to <strong>$category</strong>! Status: " . ucfirst($status);
                    header("Location: add-news.php");
                    exit();
                } else {
                    echo "<div class='alert alert-warning'>⚠️ Failed to upload image.</div>";
                }
            }
            
            // Handle News Search
            $searchResults = [];
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
                $searchTerm = trim($_GET['search']);
                if (!empty($searchTerm)) {
                    $searchQuery = "%$searchTerm%";
                    
                    $searchStmt = $conn->prepare("SELECT * FROM news WHERE 
                        (title LIKE ? OR 
                        brief LIKE ? OR 
                        keywords LIKE ? OR 
                        url_slug LIKE ?)
                        ORDER BY created_at DESC");
                    
                    if ($searchStmt) {
                        $searchStmt->bind_param("ssss", $searchQuery, $searchQuery, $searchQuery, $searchQuery);
                        $searchStmt->execute();
                        $result = $searchStmt->get_result();
                        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
                        
                        if ($conn->error) {
                            error_log("Database error: " . $conn->error);
                        }
                    } else {
                        error_log("Failed to prepare search statement");
                    }
                }
            }
            
            // Handle Status Toggle
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_status'])) {
                $newsId = $_POST['news_id'];
                $currentStatus = $_POST['current_status'];
                $newStatus = ($currentStatus == 'public') ? 'private' : 'public';
                
                $toggleStmt = $conn->prepare("UPDATE news SET status = ? WHERE id = ?");
                $toggleStmt->bind_param("si", $newStatus, $newsId);
                $toggleStmt->execute();
                
                $_SESSION['success'] = "Status updated to " . ucfirst($newStatus);
                header("Location: add-news.php");
                exit();
            }
            ?>
<style>
/* Improved Search Results CSS */
.search-results {
    margin-top: 1.5rem;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    background-color: #fff;
}

.search-results .list-group {
    border-radius: 0.5rem;
}

.search-results .list-group-item {
    border-left: 0;
    border-right: 0;
    padding: 1.25rem;
    transition: all 0.2s ease;
}

.search-results .list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
}

.search-results .news-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 0.25rem;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.search-results .bg-light {
    background-color: #f8f9fa !important;
    border-radius: 0.25rem;
}

.search-results .text-truncate {
    max-width: 80%;
}

.search-results .badge {
    font-size: 0.8rem;
    padding: 0.35em 0.65em;
    font-weight: 500;
}

.search-results .bg-success {
    background-color: #198754 !important;
}

.search-results .bg-danger {
    background-color: #dc3545 !important;
}

.search-results .bg-light.text-dark {
    background-color: #e9ecef !important;
    color: #212529 !important;
}

.search-results .small.text-muted {
    font-size: 0.85rem;
    color: #6c757d !important;
}

.search-results .btn {
    font-size: 0.85rem;
    padding: 0.375rem 0.75rem;
}

.search-results .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.search-results .btn-success {
    background-color: #198754;
    border-color: #198754;
}

.search-results .btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
}

.search-results .accordion-button {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

.search-results .accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #212529;
}

.search-results .accordion-body {
    padding: 1rem;
    font-size: 0.9rem;
    line-height: 1.6;
}

/* No results alert */
.search-results .alert {
    border-radius: 0.5rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
}

.search-results .alert .bi {
    font-size: 1.5rem;
    margin-right: 0.75rem;
}

.search-results .alert-heading {
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .search-results .col-md-2 {
        margin-bottom: 1rem;
    }
    
    .search-results .text-truncate {
        max-width: 70%;
    }
    
    .search-results .d-flex.flex-wrap {
        margin-bottom: 0.5rem;
    }
    
    .search-results .d-flex.gap-2 {
        flex-wrap: wrap;
    }
    
    .search-results .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
            <!-- SEARCH FORM CARD -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-search me-2"></i> Search News Posts</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Search Form -->
                    <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="row g-3" id="searchForm">
                        <div class="col-md-9">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">
                                    <i class="bi bi-search text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       placeholder="Search by title, keywords, or content..." 
                                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                       aria-label="Search news posts">
                            </div>
                            <small class="text-muted mt-1 d-block">Search any term - no minimum length required</small>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-success btn-lg w-100 py-2">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                    
                    <!-- Search Results -->
                    <?php if (!empty($searchResults)): ?>
                        <div class="search-results mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Search Results (<?php echo count($searchResults); ?>)</h5>
                                <a href="add-news.php" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i> Clear Search
                                </a>
                            </div>
                            
                            <div class="list-group">
                                <?php foreach ($searchResults as $news): ?>
                                    <div class="list-group-item list-group-item-action p-3 mb-2 rounded">
                                        <div class="row g-3 align-items-center">
                                            <!-- Image Column -->
                                            <div class="col-md-2">
                                                <?php if (!empty($news['image']) && file_exists($news['image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($news['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($news['title']); ?>" 
                                                         class="img-fluid rounded shadow-sm news-image"
                                                         style="max-height: 120px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                                        <i class="bi bi-image text-muted fs-1"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Content Column -->
                                            <div class="col-md-8">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h5 class="mb-1 text-truncate"><?php echo htmlspecialchars($news['title']); ?></h5>
                                                    <span class="badge bg-<?php echo $news['status'] == 'public' ? 'success' : 'danger'; ?> ms-2">
                                                        <?php echo ucfirst($news['status']); ?>
                                                    </span>
                                                </div>
                                                
                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-tag me-1"></i> <?php echo htmlspecialchars($news['category']); ?>
                                                    </span>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-person me-1"></i> <?php echo htmlspecialchars($news['author']); ?>
                                                    </span>
                                                </div>
                                                
                                                <p class="mb-1 small text-muted">
                                                    <i class="bi bi-link-45deg"></i> <?php echo htmlspecialchars($news['url_slug']); ?>
                                                </p>
                                                
                                                <?php if (!empty($news['keywords'])): ?>
                                                    <p class="mb-2 small">
                                                        <i class="bi bi-tags"></i> <?php echo htmlspecialchars($news['keywords']); ?>
                                                    </p>
                                                <?php endif; ?>
                                                
                                                <div class="d-flex gap-2 mt-2">
                                                    <form method="POST" class="mb-0">
                                                        <input type="hidden" name="news_id" value="<?php echo $news['id']; ?>">
                                                        <input type="hidden" name="current_status" value="<?php echo $news['status']; ?>">
                                                        <button type="submit" name="toggle_status" 
                                                                class="btn btn-sm btn-<?php echo $news['status'] == 'public' ? 'warning' : 'success'; ?>">
                                                            <i class="bi bi-eye<?php echo $news['status'] == 'public' ? '-slash' : ''; ?>"></i>
                                                            <?php echo $news['status'] == 'public' ? 'Make Private' : 'Make Public'; ?>
                                                        </button>
                                                    </form>
                                                    
                                                    <a href="edit-news.php?id=<?php echo $news['id']; ?>" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <!-- Date Column -->
                                            <div class="col-md-2 text-end">
                                                <small class="text-muted">
                                                    <?php echo date('M j, Y', strtotime($news['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <!-- Content Preview -->
                                        <?php if (!empty($news['brief'])): ?>
                                            <div class="mt-3">
                                                <div class="accordion border-0" id="accordion<?php echo $news['id']; ?>">
                                                    <div class="accordion-item border-0">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed bg-light py-2" 
                                                                    type="button" 
                                                                    data-bs-toggle="collapse" 
                                                                    data-bs-target="#collapse<?php echo $news['id']; ?>" 
                                                                    aria-expanded="false" 
                                                                    aria-controls="collapse<?php echo $news['id']; ?>">
                                                                <i class="bi bi-card-text me-2"></i> View Content
                                                            </button>
                                                        </h2>
                                                        <div id="collapse<?php echo $news['id']; ?>" 
                                                             class="accordion-collapse collapse bg-white" 
                                                             data-bs-parent="#accordion<?php echo $news['id']; ?>">
                                                            <div class="accordion-body p-3 border-top">
                                                                <?php echo $news['brief']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php elseif (isset($_GET['search'])): ?>
                        <div class="alert alert-info mt-4 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                            <div>
                                <h5 class="alert-heading mb-1">No results found</h5>
                                <p class="mb-0">Try different search terms or check your spelling.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MAIN NEWS FORM CARD -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="bi bi-newspaper"></i> Create News Post</h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="newsForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author</label>
                                <input type="text" class="form-control" name="author" placeholder="Enter author name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">News Title</label>
                            <input type="text" class="form-control" name="title" id="newsTitle" required>
                            <small class="text-muted">This will be used to generate the URL</small>
                            <div class="url-preview mt-2" id="urlPreview">karnatakanews.site/news/</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brief Statement</label>
                            <textarea class="form-control rich-editor" name="brief"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">SEO Keywords</label>
                                <input type="text" class="form-control" name="keywords" placeholder="Enter keywords separated by commas">
                                <small class="text-muted">Helps with search engine visibility</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle" name="status" checked>
                                    <label class="form-check-label" for="statusToggle">Private</label>
                                    <small class="text-muted d-block">Toggle to make this post public</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Featured Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*" required>
                            <small class="text-muted">Recommended size: 1200x630 pixels</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send-fill"></i> Publish News
                        </button>
                    </form>
                </div>
            </div>

            

            <!-- SHORTS SECTION -->
            <!-- <div class="card mb-5 shorts-section">
              
                <div class="card-header text-white">
                    <h4 class="mb-0"><i class="bi bi-play-circle-fill"></i> Upload Shorts</h4> -->
                <!-- </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="shortsUploadForm">
                        <div class="mb-3">
                            <label class="form-label text-white"><strong>Short Description</strong></label>
                            <textarea class="form-control" name="shorts_description" id="shorts_desc" rows="3" placeholder="Enter a catchy description for your short video..." required></textarea>
                            <small class="text-light">Keep it engaging and under 200 characters!</small>
                        </div> -->

                        <!-- <div class="mb-3">
                            <label class="form-label text-white"><strong>Upload Video</strong></label>
                            <input type="file" class="form-control" name="shorts_video" id="shorts_video" accept="video/*" required>
                            <small class="text-light">Supported formats: MP4, MOV, AVI (Max: 50MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-cloud-upload"></i> Upload Short
                        </button>
                    </form> -->

                    <!-- Show existing shorts -->
                    <!-- <hr class="my-4" style="border-color: rgba(255,255,255,0.3);">
                    <h6 class="mb-3 text-white">Recent Shorts</h6> -->
                    <?php
                    // $shorts = $conn->prepare("SELECT id, description, created_at FROM shorts ORDER BY created_at DESC LIMIT 5");
                    // $shorts->execute();
                    // $shortsResult = $shorts->get_result();
                    // if ($shortsResult->num_rows > 0) {
                    //     while ($row = $shortsResult->fetch_assoc()) {
                    //         echo '<div class="d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3) !important;">';
                    //         echo '<div>';
                    //         echo '<span class="text-white">' . htmlspecialchars(substr($row['description'], 0, 50)) . (strlen($row['description']) > 50 ? '...' : '') . '</span>';
                    //         echo '<br><small class="text-light">Uploaded: ' . date('M j, Y g:i A', strtotime($row['created_at'])) . '</small>';
                    //         echo '</div>';
                    //         echo '<form method="POST" onsubmit="return confirm(\'Are you sure to delete this short?\')">';
                    //         echo '<input type="hidden" name="delete_shorts_id" value="' . $row['id'] . '">';
                    //         echo '<button type="submit" class="btn btn-sm btn-danger">Delete</button>';
                    //         echo '</form>';
                    //         echo '</div>';
                    //     }
                    // } else {
                    //     echo '<p class="text-light">No shorts uploaded yet.</p>';
                    // }
                    // ?>
                <!-- </div>
            </div> -->

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
            // URL slug generation from title
            document.getElementById('newsTitle')?.addEventListener('input', function() {
                const title = this.value.trim();
                const slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                document.getElementById('urlPreview').textContent = 'karnatakanews.site/news/' + slug;
            });

            // Only validate the news creation form
            document.getElementById('newsForm')?.addEventListener('submit', function(e) {
                const title = document.getElementById('newsTitle').value.trim();
                if (title.length < 5) {
                    alert('Title must be at least 5 characters long.');
                    e.preventDefault();
                    return false;
                }
                
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Publishing...';
                submitButton.disabled = true;
                
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 30000);
            });

            // Search form doesn't need validation
            document.getElementById('searchForm')?.addEventListener('submit', function(e) {
                // No validation needed for search
                return true;
            });

            // Add form validation and feedback for shorts
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
        </div>
    </div>
</div>
</body>
</html>