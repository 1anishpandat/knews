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
      /* Changed to display: block for a more traditional layout where fixed elements don't interfere with flex-box of main content */
      /* If you still want the `wrapper` to be flex, ensure it's not fighting with fixed positions */
      min-height: 100vh;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa; /* Light background for overall page */
  }

  .wrapper {
      display: flex; /* This remains to make sidebar and mainContent side-by-side */
      width: 100%;
      flex: 1; /* Allows wrapper to take remaining vertical space */
  }

  /* Assuming sidebar is directly under .wrapper and needs to be fixed */
  /* Add this to your style.css or ensure your sidebar partial has appropriate classes */
  .sidebar { /* Replace .sidebar with the actual class/ID of your sidebar element */
      position: fixed; /* Fix sidebar to the left */
      top: 0; /* Align to the top */
      left: 0;
      width: 250px; /* Adjust sidebar width as needed */
      height: 100vh; /* Full viewport height */
      background-color: #343a40; /* Example sidebar background */
      z-index: 1030; /* Above main content, below navbar if navbar is fixed */
      padding-top: 56px; /* Adjust if your navbar is also fixed and overlaps */
      /* Add any other sidebar styles like padding, color, etc. */
  }

  #mainContent {
      flex: 1; /* Main content takes up remaining horizontal space */
      display: flex;
      flex-direction: column; /* Stack navbar and dashboard content vertically */
      padding: 0;
      margin-left: 250px; /* Make space for the fixed sidebar */
      width: calc(100% - 250px); /* Adjust width to not overflow */
  }

  /* Assuming your navbar is included here and needs to be fixed */
  /* Add this to your style.css or ensure your navbar partial has appropriate classes */
  .navbar { /* Replace .navbar with the actual class/ID of your navbar element */
      position: fixed; /* Fix navbar to the top */
      top: 0;
      left: 250px; /* Align after the fixed sidebar */
      width: calc(100% - 250px); /* Span the remaining width */
      height: 56px; /* Example navbar height, adjust as needed */
      background-color: #ffffff; /* Example navbar background */
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      z-index: 1020; /* Above main content */
      /* Add any other navbar styles */
  }

  .dashboard-content {
      flex: 1; /* Takes remaining vertical space within mainContent */
      padding: 50px;
      /* REMOVE margin-top, use padding-top on mainContent or here if navbar is part of mainContent */
      /* If navbar is fixed and NOT inside mainContent, mainContent needs padding-top */
      padding-top: 76px; /* Adjust this value to push content below your navbar (e.g., 56px navbar + 20px content padding) */
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: calc(100vh - 0px); /* Adjusted for full height */
      overflow-y: auto; /* Allows scrolling if content overflows */
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

  @media (max-width: 768px) {
      .sidebar {
          width: 0; /* Hide sidebar by default on smaller screens */
          padding-top: 0;
          /* You might want to implement a toggle for the sidebar */
      }

      #mainContent {
          margin-left: 0; /* No margin for sidebar on mobile */
          width: 100%;
      }

      .navbar {
          left: 0; /* Full width navbar on mobile */
          width: 100%;
      }

      .dashboard-content {
          padding: 15px;
          padding-top: 70px; /* Adjust for smaller navbar on mobile */
          min-height: calc(100vh - 0px);
      }

      .stat-number {
          font-size: 2rem;
      }
      .chart-container {
          min-height: 300px;
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
                          <small class="text-muted"><?php echo number_format($totalNewsClicks); ?> total clicks</small>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="stats-card text-center">
                          <div class="icon-stat text-warning">
                              <i class="bi bi-play-circle"></i>
                          </div>
                          <div class="stat-number"><?php echo $totalShorts; ?></div>
                          <div class="stat-label">Total Shorts</div>
                          <small class="text-muted"><?php echo number_format($totalShortsViews); ?> total views</small>
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
                          <h4>📈 Real-Time Engagement Metrics</h4>
                          <div class="row">
                              <div class="col-md-4">
                                  <h5><?php echo number_format($totalVisits); ?></h5>
                                  <small>Page Views (30 days)</small>
                              </div>
                              <div class="col-md-4">
                                  <h5><?php echo number_format($totalNewsClicks); ?></h5>
                                  <small>News Article Clicks</small>
                              </div>
                              <div class="col-md-4">
                                  <h5><?php echo number_format($totalShortsViews); ?></h5>
                                  <small>Shorts Video Views</small>
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
                      stepSize: 1 // Ensures steps of 1 for clarity
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
  // Real-time update check every 30 seconds
  // Real-time update check every 30 seconds
  setInterval(function() {
      // Update only the stats without full page reload
      fetch('get_stats.php') // This will now fetch data from your new file
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

              // NEW: Update the Total Shorts card number
              document.querySelector('.stats-card:nth-child(3) .stat-number').textContent = data.totalShorts;


              // Update engagement metrics
              const engagementVisits = document.querySelector('.engagement-stats .col-md-4:nth-child(1) h5');
              if (engagementVisits) {
                  engagementVisits.textContent = data.totalVisits.toLocaleString();
              }

              const engagementNewsClicks = document.querySelector('.engagement-stats .col-md-4:nth-child(2) h5');
              if (engagementNewsClicks) {
                  engagementNewsClicks.textContent = data.totalNewsClicks.toLocaleString();
              }

              const engagementShortsViews = document.querySelector('.engagement-stats .col-md-4:nth-child(3) h5');
              if (engagementShortsViews) {
                  engagementShortsViews.textContent = data.totalShortsViews.toLocaleString();
              }

          })
          .catch(error => console.error('Stats update failed:', error)); // Log detailed error
  }, 30000);

  console.log('📊 Real-time analytics dashboard loaded successfully!');
  console.log('🔄 Auto-refresh enabled every 30 seconds');
  </script>

  </body>
  </html>