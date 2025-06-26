<?php
// get_stats.php
header('Content-Type: application/json'); // Tell the client we're sending JSON

// Include your database connection
include("db.php"); // Adjust path if necessary

// Initialize an array to hold all data
$response = [];

// --- Fetch Summary Statistics ---
$totalVisitsQuery = "SELECT COUNT(*) as total FROM website_analytics WHERE visit_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$totalVisitsResult = $conn->query($totalVisitsQuery);
$response['totalVisits'] = $totalVisitsResult ? (int)$totalVisitsResult->fetch_assoc()['total'] : 0;

$totalNewsQuery = "SELECT COUNT(*) as total FROM news";
$response['totalNews'] = $conn->query($totalNewsQuery)->fetch_assoc()['total'];

$totalShortsQuery = "SELECT COUNT(*) as total FROM shorts";
$response['totalShorts'] = $conn->query($totalShortsQuery)->fetch_assoc()['total'];

$todayNewsQuery = "SELECT COUNT(*) as total FROM news WHERE DATE(created_at) = CURDATE()";
$response['todayNews'] = $conn->query($todayNewsQuery)->fetch_assoc()['total'];

$totalNewsClicksQuery = "SELECT COALESCE(SUM(clicks), 0) as total FROM news_analytics";
$totalNewsClicksResult = $conn->query($totalNewsClicksQuery);
$response['totalNewsClicks'] = $totalNewsClicksResult ? (int)$totalNewsClicksResult->fetch_assoc()['total'] : 0;

$totalShortsViewsQuery = "SELECT COALESCE(SUM(views), 0) as total FROM shorts_analytics";
$totalShortsViewsResult = $conn->query($totalShortsViewsQuery);
$response['totalShortsViews'] = $totalShortsViewsResult ? (int)$totalShortsViewsResult->fetch_assoc()['total'] : 0;


// --- Get trending news clicks data (REAL CLICKS ONLY) ---
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
if ($trendingResult) {
    while($row = $trendingResult->fetch_assoc()) {
        $trendingLabels[] = strlen($row['title']) > 30 ? substr($row['title'], 0, 30) . '...' : $row['title'];
        $trendingData[] = (int)$row['total_clicks'];
    }
}
$response['trendingNewsLabels'] = $trendingLabels;
$response['trendingNewsData'] = $trendingData;


// --- Get trending shorts data (REAL VIEWS ONLY) ---
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
if ($shortsResult) {
    while($row = $shortsResult->fetch_assoc()) {
        $description = $row['description'];
        $shortsLabels[] = strlen($description) > 25 ? substr($description, 0, 25) . '...' : $description;
        $shortsData[] = (int)$row['total_views'];
    }
}
$response['shortsLabels'] = $shortsLabels;
$response['shortsData'] = $shortsData;

// --- Get website traffic data (REAL DATA ONLY) ---
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
if ($trafficResult) {
    while($row = $trafficResult->fetch_assoc()) {
        $dateKey = date('M j', strtotime($row['date']));
        $index = array_search($dateKey, $trafficLabels);
        if ($index !== false) {
            $trafficData[$index] = (int)$row['visits'];
        }
    }
}
$response['trafficLabels'] = $trafficLabels;
$response['trafficData'] = $trafficData;

// --- Get news upload statistics by category (REAL DATA) ---
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
if ($newsStatsResult) {
    while($row = $newsStatsResult->fetch_assoc()) {
        $newsStatsLabels[] = $row['category'];
        $newsStatsData[] = (int)$row['count'];
    }
}
$response['newsStatsLabels'] = $newsStatsLabels;
$response['newsStatsData'] = $newsStatsData;

// --- Get daily news uploads for last 30 days ---
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
if ($dailyNewsResult) {
    while($row = $dailyNewsResult->fetch_assoc()) {
        $dateKey = date('M j', strtotime($row['date']));
        $index = array_search($dateKey, $dailyNewsLabels);
        if ($index !== false) {
            $dailyNewsData[$index] = (int)$row['daily_count'];
        }
    }
}
$response['dailyNewsLabels'] = $dailyNewsLabels;
$response['dailyNewsData'] = $dailyNewsData;


// Close connection
$conn->close();

echo json_encode($response);
?>