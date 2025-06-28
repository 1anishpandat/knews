<?php
  // session_start();
  // if (!isset($_SESSION['admin'])) {
  //      header("Location: login.php");
  //      exit;
  // }

  include("db.php"); // Ensure this path is correct for your database connection

  // First, let's create the analytics tables if they don't exist
  $createTables = "
  CREATE TABLE IF NOT EXISTS website_analytics (
      id INT AUTO_INCREMENT PRIMARY KEY,
      visit_date DATETIME DEFAULT CURRENT_TIMESTAMP,
      ip_address VARCHAR(45),
      user_agent TEXT,
      page_url VARCHAR(255),
      page_type ENUM('home', 'news', 'shorts', 'other') DEFAULT 'other'
  );

  CREATE TABLE IF NOT EXISTS news_analytics (
      id INT AUTO_INCREMENT PRIMARY KEY,
      news_id INT,
      clicks INT DEFAULT 1,
      date_clicked DATE DEFAULT (CURRENT_DATE),
      UNIQUE KEY unique_news_date (news_id, date_clicked),
      FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
  );

  CREATE TABLE IF NOT EXISTS shorts_analytics (
      id INT AUTO_INCREMENT PRIMARY KEY,
      shorts_id INT,
      views INT DEFAULT 1,
      date_viewed DATE DEFAULT (CURRENT_DATE),
      UNIQUE KEY unique_shorts_date (shorts_id, date_viewed),
      FOREIGN KEY (shorts_id) REFERENCES shorts(id) ON DELETE CASCADE
  );

  CREATE TABLE IF NOT EXISTS news_likes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      news_id INT NOT NULL,
      user_ip VARCHAR(45) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
      UNIQUE KEY unique_news_like (news_id, user_ip)
  );

  CREATE TABLE IF NOT EXISTS news_comments (
      id INT AUTO_INCREMENT PRIMARY KEY,
      news_id INT NOT NULL,
      user_name VARCHAR(100) NOT NULL,
      comment TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
  );

  CREATE TABLE IF NOT EXISTS shorts_likes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      shorts_id INT NOT NULL,
      user_ip VARCHAR(45) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (shorts_id) REFERENCES shorts(id) ON DELETE CASCADE,
      UNIQUE KEY unique_shorts_like (shorts_id, user_ip)
  );

  CREATE TABLE IF NOT EXISTS shorts_comments (
      id INT AUTO_INCREMENT PRIMARY KEY,
      shorts_id INT NOT NULL,
      user_name VARCHAR(100) NOT NULL,
      comment TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (shorts_id) REFERENCES shorts(id) ON DELETE CASCADE
  );
  ";

  // Execute table creation (split by semicolon)
  $queries = explode(';', $createTables);
  foreach($queries as $query) {
      $query = trim($query);
      if (!empty($query)) {
          $conn->query($query);
      }
  }

  // Get website traffic data (REAL DATA ONLY)
  $trafficQuery = "
  SELECT DATE(visit_date) as date, COUNT(*) as visits 
  FROM website_analytics 
  WHERE visit_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
  GROUP BY DATE(visit_date) 
  ORDER BY date";

  $trafficResult = $conn->query($trafficQuery);
  $trafficData = [];
  $trafficLabels = [];

  // Create array for last 30 days with 0 as default
  for ($i = 29; $i >= 0; $i--) {
      $date = date('Y-m-d', strtotime("-$i days"));
      $trafficLabels[] = date('M j', strtotime($date));
      $trafficData[] = 0; // Initialize with 0 visits
  }

  // Fill in actual traffic data
  while($row = $trafficResult->fetch_assoc()) {
      $dateKey = date('M j', strtotime($row['date']));
      $index = array_search($dateKey, $trafficLabels);
      if ($index !== false) {
          $trafficData[$index] = (int)$row['visits'];
      }
  }

  // Get trending news clicks data (REAL CLICKS ONLY)
  $trendingQuery = "
  SELECT n.title, n.id, COALESCE(SUM(na.clicks), 0) as total_clicks 
  FROM news n 
  LEFT JOIN news_analytics na ON n.id = na.news_id 
  GROUP BY n.id, n.title 
  HAVING total_clicks > 0
  ORDER BY total_clicks DESC 
  LIMIT 10";

  $trendingResult = $conn->query($trendingQuery);
  $trendingLabels = [];
  $trendingData = [];

  while($row = $trendingResult->fetch_assoc()) {
      $trendingLabels[] = strlen($row['title']) > 30 ? substr($row['title'], 0, 30) . '...' : $row['title'];
      $trendingData[] = (int)$row['total_clicks'];
  }

  // Get news upload statistics by category (REAL DATA)
  $newsStatsQuery = "
  SELECT 
      category,
      COUNT(*) as count
  FROM news 
  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
  GROUP BY category
  ORDER BY count DESC";

  $newsStatsResult = $conn->query($newsStatsQuery);
  $newsStatsLabels = [];
  $newsStatsData = [];

  while($row = $newsStatsResult->fetch_assoc()) {
      $newsStatsLabels[] = $row['category'];
      $newsStatsData[] = (int)$row['count'];
  }

  // Get trending shorts data (REAL VIEWS ONLY)
  $shortsQuery = "
  SELECT s.description, s.id, COALESCE(SUM(sa.views), 0) as total_views 
  FROM shorts s 
  LEFT JOIN shorts_analytics sa ON s.id = sa.shorts_id 
  GROUP BY s.id, s.description 
  HAVING total_views > 0
  ORDER BY total_views DESC 
  LIMIT 8";

  $shortsResult = $conn->query($shortsQuery);
  $shortsLabels = [];
  $shortsData = [];

  while($row = $shortsResult->fetch_assoc()) {
      $description = $row['description'];
      $shortsLabels[] = strlen($description) > 25 ? substr($description, 0, 25) . '...' : $description;
      $shortsData[] = (int)$row['total_views'];
  }

  // Get summary statistics (REAL COUNTS ONLY)
  $totalVisitsQuery = "SELECT COUNT(*) as total FROM website_analytics WHERE visit_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
  $totalVisitsResult = $conn->query($totalVisitsQuery);
  $totalVisits = $totalVisitsResult->fetch_assoc()['total'];

  $totalNewsQuery = "SELECT COUNT(*) as total FROM news";
  $totalNews = $conn->query($totalNewsQuery)->fetch_assoc()['total'];

  $totalShortsQuery = "SELECT COUNT(*) as total FROM shorts";
  $totalShorts = $conn->query($totalShortsQuery)->fetch_assoc()['total'];

  $todayNewsQuery = "SELECT COUNT(*) as total FROM news WHERE DATE(created_at) = CURDATE()";
  $todayNews = $conn->query($todayNewsQuery)->fetch_assoc()['total'];

  // Get total clicks on news (for popularity calculation)
  $totalNewsClicksQuery = "SELECT COALESCE(SUM(clicks), 0) as total FROM news_analytics";
  $totalNewsClicks = $conn->query($totalNewsClicksQuery)->fetch_assoc()['total'];

  // Get total views on shorts (for popularity calculation)
  $totalShortsViewsQuery = "SELECT COALESCE(SUM(views), 0) as total FROM shorts_analytics";
  $totalShortsViews = $conn->query($totalShortsViewsQuery)->fetch_assoc()['total'];

  // Get daily news uploads for last 30 days
  $dailyNewsQuery = "
  SELECT 
      DATE(created_at) as date,
      COUNT(*) as daily_count
  FROM news 
  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
  GROUP BY DATE(created_at)
  ORDER BY date";

  $dailyNewsResult = $conn->query($dailyNewsQuery);
  $dailyNewsLabels = [];
  $dailyNewsData = [];

  // Create array for last 30 days with 0 as default
  for ($i = 29; $i >= 0; $i--) {
      $date = date('Y-m-d', strtotime("-$i days"));
      $dailyNewsLabels[] = date('M j', strtotime($date));
      $dailyNewsData[] = 0; // Initialize with 0 uploads
  }

  // Fill in actual data
  while($row = $dailyNewsResult->fetch_assoc()) {
      $dateKey = date('M j', strtotime($row['date']));
      $index = array_search($dateKey, $dailyNewsLabels);
      if ($index !== false) {
          $dailyNewsData[$index] = (int)$row['daily_count'];
      }
  }

  // NEW: Get engagement statistics
  $totalNewsLikesQuery = "SELECT COUNT(*) as total FROM news_likes";
  $totalNewsLikes = $conn->query($totalNewsLikesQuery)->fetch_assoc()['total'];

  $totalNewsCommentsQuery = "SELECT COUNT(*) as total FROM news_comments";
  $totalNewsComments = $conn->query($totalNewsCommentsQuery)->fetch_assoc()['total'];

  $totalShortsLikesQuery = "SELECT COUNT(*) as total FROM shorts_likes";
  $totalShortsLikes = $conn->query($totalShortsLikesQuery)->fetch_assoc()['total'];

  $totalShortsCommentsQuery = "SELECT COUNT(*) as total FROM shorts_comments";
  $totalShortsComments = $conn->query($totalShortsCommentsQuery)->fetch_assoc()['total'];

  // Get most liked news
  $mostLikedNewsQuery = "
  SELECT n.id, n.title, COUNT(nl.id) as like_count 
  FROM news n 
  LEFT JOIN news_likes nl ON n.id = nl.news_id 
  GROUP BY n.id 
  ORDER BY like_count DESC 
  LIMIT 5";
  $mostLikedNews = $conn->query($mostLikedNewsQuery);

  // Get most commented news
  $mostCommentedNewsQuery = "
  SELECT n.id, n.title, COUNT(nc.id) as comment_count 
  FROM news n 
  LEFT JOIN news_comments nc ON n.id = nc.news_id 
  GROUP BY n.id 
  ORDER BY comment_count DESC 
  LIMIT 5";
  $mostCommentedNews = $conn->query($mostCommentedNewsQuery);

  // Get most liked shorts
  $mostLikedShortsQuery = "
  SELECT s.id, s.description, COUNT(sl.id) as like_count 
  FROM shorts s 
  LEFT JOIN shorts_likes sl ON s.id = sl.shorts_id 
  GROUP BY s.id 
  ORDER BY like_count DESC 
  LIMIT 5";
  $mostLikedShorts = $conn->query($mostLikedShortsQuery);

  // Get most commented shorts
  $mostCommentedShortsQuery = "
  SELECT s.id, s.description, COUNT(sc.id) as comment_count 
  FROM shorts s 
  LEFT JOIN shorts_comments sc ON s.id = sc.shorts_id 
  GROUP BY s.id 
  ORDER BY comment_count DESC 
  LIMIT 5";
  $mostCommentedShorts = $conn->query($mostCommentedShortsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Karnataka News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
}

#mainContent {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 0;
    margin-left: 250px;
    width: calc(100% - 250px);
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
}

.dashboard-content {
    flex: 9;
    padding: 50px;
    padding-top: 76px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 0px);
    overflow-y: auto;
}

.stats-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.chart-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 350px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-label {
    color: #7f8c8d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.dashboard-title {
    color: white;
    text-align: center;
    margin-bottom: 30px;
    font-weight: 300;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.chart-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
}

.icon-stat {
    font-size: 3rem;
    margin-bottom: 15px;
}

.no-data-message {
    text-align: center;
    color: #6c757d;
    font-style: italic;
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.engagement-stats {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 15px;
    margin-top: 20px;
    color: white;
    text-align: center;
}

.engagement-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-top: 15px;
}

.engagement-item {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 10px;
    text-align: center;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

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
        min-height: calc(100vh - 0px);
    }

    .stat-number {
        font-size: 2rem;
    }
    .chart-container {
        min-height: 300px;
    }
    
    .engagement-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 576px) {
    .engagement-grid {
        grid-template-columns: 1fr;
    }
}
    </style>
</head>
<body>
<div class="wrapper d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <div id="mainContent">
        <?php include 'partials/navbar.php'; ?>
        
        <div class="dashboard-content">
            <h1 class="dashboard-title">📊 Real-Time Analytics Dashboard</h1>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <div class="icon-stat text-primary">
                            <i class="bi bi-eye"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($totalVisits); ?></div>
                        <div class="stat-label">Monthly Visits</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <div class="icon-stat text-success">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div class="stat-number"><?php echo $totalNews; ?></div>
                        <div class="stat-label">Total News</div>
                        <small class="text-muted"><?php echo number_format($totalNewsClicks); ?> clicks</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <div class="icon-stat text-warning">
                            <i class="bi bi-play-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $totalShorts; ?></div>
                        <div class="stat-label">Total Shorts</div>
                        <small class="text-muted"><?php echo number_format($totalShortsViews); ?> views</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <div class="icon-stat text-info">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <div class="stat-number"><?php echo $todayNews; ?></div>
                        <div class="stat-label">Today's News</div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="engagement-stats">
                        <h4>📈 Engagement Metrics</h4>
                        <div class="engagement-grid">
                            <div class="engagement-item">
                                <h5><?php echo number_format($totalNewsLikes); ?></h5>
                                <small>News Likes</small>
                            </div>
                            <div class="engagement-item">
                                <h5><?php echo number_format($totalNewsComments); ?></h5>
                                <small>News Comments</small>
                            </div>
                            <div class="engagement-item">
                                <h5><?php echo number_format($totalShortsLikes); ?></h5>
                                <small>Shorts Likes</small>
                            </div>
                            <div class="engagement-item">
                                <h5><?php echo number_format($totalShortsComments); ?></h5>
                                <small>Shorts Comments</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">📈 Website Traffic (Last 30 Days)</h3>
                        <?php if (array_sum($trafficData) > 0): ?>
                            <canvas id="trafficChart"></canvas>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-graph-up" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No website traffic recorded yet.</p>
                                <small>Traffic will appear as users visit your pages.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">🔥 Most Popular News Articles</h3>
                        <?php if (!empty($trendingData) && array_sum($trendingData) > 0): ?>
                            <canvas id="trendingChart"></canvas>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-newspaper" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No news clicks recorded yet.</p>
                                <small>Popularity will show as users click on news articles.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">📰 Daily News Publishing</h3>
                        <?php if (array_sum($dailyNewsData) > 0): ?>
                            <canvas id="dailyNewsChart"></canvas>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-calendar-plus" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No news published in the last 30 days.</p>
                                <small>Start publishing news to see daily activity.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">📊 News by Category</h3>
                        <?php if (!empty($newsStatsData) && array_sum($newsStatsData) > 0): ?>
                            <canvas id="categoryChart"></canvas>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-pie-chart" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No categorized news found.</p>
                                <small>Add news to different categories to see distribution.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">❤️ Most Liked News</h3>
                        <?php if ($mostLikedNews->num_rows > 0): ?>
                            <div class="list-group">
                                <?php while ($row = $mostLikedNews->fetch_assoc()): ?>
                                    <a href="news-detail.php?id=<?php echo $row['id']; ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h6>
                                            <small><?php echo $row['like_count']; ?> likes</small>
                                        </div>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-heart" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No news likes recorded yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">💬 Most Commented News</h3>
                        <?php if ($mostCommentedNews->num_rows > 0): ?>
                            <div class="list-group">
                                <?php while ($row = $mostCommentedNews->fetch_assoc()): ?>
                                    <a href="news-detail.php?id=<?php echo $row['id']; ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h6>
                                            <small><?php echo $row['comment_count']; ?> comments</small>
                                        </div>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No news comments recorded yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="chart-container">
                        <h3 class="chart-title">🎬 Most Viewed Shorts</h3>
                        <?php if (!empty($shortsData) && array_sum($shortsData) > 0): ?>
                            <canvas id="shortsChart"></canvas>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-play-circle" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No shorts views recorded yet.</p>
                                <small>Views will appear as users watch your shorts videos.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">❤️ Most Liked Shorts</h3>
                        <?php if ($mostLikedShorts->num_rows > 0): ?>
                            <div class="list-group">
                                <?php while ($row = $mostLikedShorts->fetch_assoc()): ?>
                                    <a href="#" onclick="openShortsModal(<?php echo $row['id']; ?>)" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($row['description']); ?></h6>
                                            <small><?php echo $row['like_count']; ?> likes</small>
                                        </div>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-heart" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No shorts likes recorded yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">💬 Most Commented Shorts</h3>
                        <?php if ($mostCommentedShorts->num_rows > 0): ?>
                            <div class="list-group">
                                <?php while ($row = $mostCommentedShorts->fetch_assoc()): ?>
                                    <a href="#" onclick="openShortsModal(<?php echo $row['id']; ?>)" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($row['description']); ?></h6>
                                            <small><?php echo $row['comment_count']; ?> comments</small>
                                        </div>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-data-message">
                                <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #ccc;"></i>
                                <p>No shorts comments recorded yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script> 

<script>
// Chart.js Configuration
Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
Chart.defaults.font.size = 12;

// Chart colors
const chartColors = {
    primary: '#667eea',
    success: '#28a745',
    warning: '#ffc107',
    info: '#17a2b8',
    danger: '#dc3545'
};

<?php if (array_sum($trafficData) > 0): ?>
// 1. Website Traffic Chart
const trafficCtx = document.getElementById('trafficChart').getContext('2d');
new Chart(trafficCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($trafficLabels); ?>,
        datasets: [{
            label: 'Daily Visits',
            data: <?php echo json_encode($trafficData); ?>,
            borderColor: chartColors.primary,
            backgroundColor: chartColors.primary + '20',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: chartColors.primary,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Visits: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#666',
                    stepSize: 1
                },
                grid: {
                    color: '#e0e0e0'
                }
            },
            x: {
                ticks: {
                    color: '#666'
                },
                grid: {
                    color: '#e0e0e0'
                }
            }
        }
    }
});
<?php endif; ?>

<?php if (!empty($trendingData) && array_sum($trendingData) > 0): ?>
// 2. Trending News Chart
const trendingCtx = document.getElementById('trendingChart').getContext('2d');
new Chart(trendingCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($trendingLabels); ?>,
        datasets: [{
            label: 'Clicks',
            data: <?php echo json_encode($trendingData); ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                '#4BC0C0', '#FF6384'
            ],
            borderColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                '#4BC0C0', '#FF6384'
            ],
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Clicks: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#666',
                    stepSize: 1
                },
                grid: {
                    color: '#e0e0e0'
                }
            },
            x: {
                ticks: {
                    color: '#666',
                    maxRotation: 45
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
<?php endif; ?>

<?php if (array_sum($dailyNewsData) > 0): ?>
// 3. Daily News Chart
const dailyNewsCtx = document.getElementById('dailyNewsChart').getContext('2d');
new Chart(dailyNewsCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($dailyNewsLabels); ?>,
        datasets: [{
            label: 'News Published',
            data: <?php echo json_encode($dailyNewsData); ?>,
            backgroundColor: chartColors.success + '80',
            borderColor: chartColors.success,
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Articles: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#666',
                    stepSize: 1
                },
                grid: {
                    color: '#e0e0e0'
                }
            },
            x: {
                ticks: {
                    color: '#666'
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
<?php endif; ?>

<?php if (!empty($newsStatsData) && array_sum($newsStatsData) > 0): ?>
// 4. Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($newsStatsLabels); ?>,
        datasets: [{
            data: <?php echo json_encode($newsStatsData); ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    color: '#666'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' articles';
                    }
                }
            }
        }
    }
});
<?php endif; ?>

<?php if (!empty($shortsData) && array_sum($shortsData) > 0): ?>
// 5. Shorts Chart
const shortsCtx = document.getElementById('shortsChart').getContext('2d');
new Chart(shortsCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($shortsLabels); ?>,
        datasets: [{
            data: <?php echo json_encode($shortsData); ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    color: '#666'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' views';
                    }
                }
            }
        }
    }
});
<?php endif; ?>

// Real-time update check every 30 seconds
setInterval(function() {
    fetch('get_stats.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Update the displayed numbers
            document.querySelector('.stats-card:nth-child(1) .stat-number').textContent = data.totalVisits.toLocaleString();
            document.querySelector('.stats-card:nth-child(4) .stat-number').textContent = data.todayNews;
            document.querySelector('.stats-card:nth-child(3) .stat-number').textContent = data.totalShorts;

            // Update engagement metrics
            const engagementItems = document.querySelectorAll('.engagement-item h5');
            if (engagementItems.length >= 4) {
                engagementItems[0].textContent = data.totalNewsLikes.toLocaleString();
                engagementItems[1].textContent = data.totalNewsComments.toLocaleString();
                engagementItems[2].textContent = data.totalShortsLikes.toLocaleString();
                engagementItems[3].textContent = data.totalShortsComments.toLocaleString();
            }
        })
        .catch(error => console.error('Stats update failed:', error));
}, 30000);

console.log('📊 Real-time analytics dashboard loaded successfully!');
console.log('🔄 Auto-refresh enabled every 30 seconds');
</script>

</body>
</html>