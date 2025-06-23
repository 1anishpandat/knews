<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Karnataka State News</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 🔽 News Section Starts -->
<div class="px-6 py-10">

<?php
$apiKey = "pub_b5aa0080327e436484581c7a4667486b";

// Kannada-specific categories
$categories = ['sports', 'politics', 'education', 'business', 'crime', 'entertainment'];

echo "<div class='px-6 py-10 bg-gray-100'>";
foreach ($categories as $category) {
    // Kannada language in API
    $apiUrl = "https://newsdata.io/api/1/latest?apikey=$apiKey&country=in&language=kn&category=$category&q=karnataka";

    $response = @file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (!$data || !isset($data['results']) || count($data['results']) === 0) {
        echo "<h2 class='text-xl font-semibold text-red-600 mb-4'>⚠️ " . ucfirst($category) . " (ವರ್ಗದ) ಸುದ್ದಿಯನ್ನು ಕಂಡುಹಿಡಿಯಲು ಸಾಧ್ಯವಾಗಿಲ್ಲ</h2>";
        continue;
    }

    echo "<h2 class='text-2xl font-bold text-gray-800 mb-4'>" . ucfirst($category) . " ಸುದ್ದಿ</h2>";
    echo "<div class='grid gap-6 mb-8'>";

    $newsItems = array_slice($data['results'], 0, 5);

    foreach ($newsItems as $news) {
        $title = $news['title'] ?? 'ಶೀರ್ಷಿಕೆ ಲಭ್ಯವಿಲ್ಲ';
        $description = $news['description'] ?? 'ವಿವರಣೆ ಲಭ್ಯವಿಲ್ಲ.';
        $link = $news['link'] ?? '#';
        $pubDate = $news['pubDate'] ?? 'ದಿನಾಂಕ ಲಭ್ಯವಿಲ್ಲ';
        $image = $news['image_url'] ?? 'https://via.placeholder.com/300x200';

        echo "
        <div class='max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row'>
            <div class='md:w-1/3 w-full'>
                <img src='$image' alt='News Image' class='object-cover h-full w-full'>
            </div>
            <div class='md:w-2/3 w-full p-6 flex flex-col justify-center'>
                <h3 class='text-xl font-bold text-gray-800 mb-2'>$title</h3>
                <p class='text-gray-600 text-sm mb-2'>$description</p>
                <ul class='text-sm text-gray-700 space-y-1'>
                    <li><strong>ದಿನಾಂಕ:</strong> $pubDate</li>
                    <li><a href='$link' target='_blank' class='text-blue-600 underline'>ಪೂರ್ಣ ಸುದ್ದಿಯನ್ನು ಓದಿ</a></li>
                </ul>
            </div>
        </div>";
    }

    echo "</div>";
}
echo "</div>";
?>



</div>
<a href="index.php" class="fixed bottom-20 right-6 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition">
  ← Back to Home
</a>

<!-- 🔼 News Section Ends -->

<?php include 'includes/footer.php'; ?>
<script>feather.replace();</script>
</body>
</html>
