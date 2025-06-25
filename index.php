
<?php
// include("admin/db.php");

// function showNewsSection($category, $conn) {
//     $stmt = $conn->prepare("SELECT * FROM news WHERE category = ? ORDER BY created_at DESC LIMIT 5");
//     $stmt->bind_param("s", $category);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     echo "<div class='section px-4 py-2'>";
//     echo "<h2 class='text-xl font-bold mb-3'>$category</h2>";
//     echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>";
//     while ($row = $result->fetch_assoc()) {
//         echo "<div class='bg-white shadow-md p-2 rounded'>";
//         echo "<img src='{$row['image']}' alt='Image' class='w-full h-40 object-cover rounded mb-2'>";
//         echo "<h3 class='text-lg font-semibold'>{$row['title']}</h3>";
//         echo "<p class='text-sm text-gray-600'>{$row['brief']}</p>";
//         echo "</div>";
//     }
//     echo "</div></div>";
// }

include("admin/db.php");

function showNewsSection($category, $conn) {
    $stmt = $conn->prepare("SELECT * FROM news WHERE category = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='section px-4 py-2'>";
    echo "<h2 class='text-xl font-bold mb-3'>$category</h2>";
    echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='bg-white shadow-md p-2 rounded'>";
        echo "<img src='{$row['image']}' alt='Image' class='w-full h-40 object-cover rounded mb-2'>";
        echo "<h3 class='text-lg font-semibold'>{$row['title']}</h3>";
        echo "<p class='text-sm text-gray-600'>{$row['brief']}</p>";
        echo "</div>";
    }
    echo "</div></div>";
}
// Include DB


// Track visitor
$pageUrl = $_SERVER['REQUEST_URI'];
$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

$stmt = $conn->prepare("INSERT INTO website_analytics (ip_address, user_agent, page_url) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $ip, $userAgent, $pageUrl);
$stmt->execute();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="UTF-8">
  <meta name="google" content="notranslate">
  <title>K News</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- Google Translate Loader -->
  <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,hi,mr,kn',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
      }, 'google_translate_element');
    }

    (function () {
      var gt = document.createElement('script');
      gt.type = 'text/javascript';
      gt.async = true;
      gt.src = "//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
      document.head.appendChild(gt);
    })();
  </script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>

<body class="bg-gray-100 text-gray-800">  <style>
        /* Custom scrollbar styling */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Video container styling */
        .video-container {
            position: relative;
            width: 100%;
            height: 280px;
            background: #000;
            border-radius: 0.5rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        
        /* Loading placeholder */
        .video-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            z-index: 1;
        }
        
        /* Error state */
        .video-error {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        
        /* Play button overlay */
        .play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .play-overlay:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .play-button {
            width: 0;
            height: 0;
            border-left: 20px solid white;
            border-top: 12px solid transparent;
            border-bottom: 12px solid transparent;
            margin-left: 4px;
        }
        
        /* Animation for cards */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .shorts-card {
            animation: slideIn 0.5s ease-out;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .video-container {
                height: 240px;
            }
        }
    </style>
  <!-- Include Layout & Features -->
  <?php include 'includes/header.php'; ?>
  <?php include 'includes/language-switcher.php'; ?>
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/notification-bell.php'; ?>
  <?php include 'includes/send-push.php'; ?>



<!-- <script>
  const clientId = "1054275115401-auq2b0h80ruvlsfm66hhoivemc93i6td.apps.googleusercontent.com"; // ✅ Replace with YOUR CLIENT ID

  window.addEventListener("DOMContentLoaded", () => {
    const bell = document.getElementById("notify-bell");

    // ✅ 1. Initialize Sign-In
    google.accounts.id.initialize({
      client_id: clientId,
      auto_select: false, // false = always show popup
      callback: handleCredentialResponse
    });

    // ✅ 2. Handle Sign-In Response
    function handleCredentialResponse(response) {
      const payload = JSON.parse(atob(response.credential.split('.')[1]));
      const email = payload.email;

      fetch("includes/subscribe.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "email=" + encodeURIComponent(email)
      })
      .then(res => res.text())
      .then(data => {
        alert("✅ Subscribed with: " + email);
      })
      .catch(err => {
        console.error("❌ Error saving email:", err);
      });
    }

    // ✅ 3. Bell click shows confirmation
    bell.addEventListener("click", () => {
      const confirmed = confirm("🔔 Subscribe to notifications with your Google email?");
      if (confirmed) {
        google.accounts.id.prompt((notification) => {
          if (notification.isNotDisplayed()) {
            alert("⚠️ Google Sign-In could not be displayed.");
            console.log("Prompt not displayed:", notification.getNotDisplayedReason());
          }
        });
      }
    });
  });
</script> -->
<!-- CDN fallback if aspect ratio plugin not included -->


<!-- 1. Trending Section -->
<section class="bg-gray-50 px-6 py-10">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-6">

    <!-- Trending -->
    <div class="md:col-span-3 space-y-4">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">🔥 Trending</h2>
      <?php
      include("admin/db.php");
      $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Trending' ORDER BY created_at DESC LIMIT 2");
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        echo '<a href="news-detail.php?id=' . $row['id'] . '" class="block hover:shadow-lg transition rounded overflow-hidden bg-white">';
        echo '<div class="relative w-full h-56 overflow-hidden">';
        echo '<img src="admin/' . htmlspecialchars($row['image']) . '" class="object-cover w-full h-full" alt="' . htmlspecialchars($row['title']) . '">';
        echo '<div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-3 py-2">';
        echo '<h2 class="text-white text-lg font-bold line-clamp-2">' . htmlspecialchars($row['title']) . '</h2>';
        echo '</div>';
        echo '</div>';
        echo '</a>';
      }
      ?>
    </div>



    <div class="md:col-span-6 bg-white rounded-lg shadow relative overflow-hidden mt-6 mx-auto" style="max-width: 500px; height: 500px;">
  <div id="newsSlides" class="relative w-full h-full overflow-hidden">
    <div id="slidesWrapper" class="flex transition-transform duration-500 ease-in-out h-full">
      <?php
      $carousel = $conn->prepare("SELECT * FROM news WHERE category != 'Ad' ORDER BY created_at DESC LIMIT 4");
      $carousel->execute();
      $carousel_result = $carousel->get_result();
      while ($row = $carousel_result->fetch_assoc()) {
        echo '<div class="w-full flex-shrink-0 h-full">';
        echo '<a href="news-detail.php?id=' . $row['id'] . '" class="block w-full h-full">';
        echo '<div class="relative w-full h-full overflow-hidden rounded">';
        echo '<img src="admin/' . htmlspecialchars($row['image']) . '" class="object-cover w-full h-full" alt="' . htmlspecialchars($row['title']) . '">';
        echo '<div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-4 py-3">';
        echo '<h2 class="text-white text-lg font-bold leading-tight">' . htmlspecialchars($row['title']) . '</h2>';
        echo '</div>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
      }
      ?>
    </div>
  </div>

  <!-- Navigation Buttons -->
  <div class="absolute top-1/2 left-0 -translate-y-1/2 px-2 z-10">
    <button onclick="prevSlide()" class="bg-gray-300 hover:bg-gray-100 text-black font-bold py-2 px-3 rounded-full shadow">&larr;</button>
  </div>
  <div class="absolute top-1/2 right-0 -translate-y-1/2 px-2 z-10">
    <button onclick="nextSlide()" class="bg-gray-300 hover:bg-gray-100 text-black font-bold py-2 px-3 rounded-full shadow">&rarr;</button>
  </div>
</div>



<script>
let currentIndex = 0;
const slidesWrapper = document.getElementById("slidesWrapper");
const slideItems = slidesWrapper.children;
const totalSlides = slideItems.length;

function updateSlidePosition() {
  slidesWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
}

function nextSlide() {
  if (currentIndex < totalSlides - 1) {
    currentIndex++;
    updateSlidePosition();
  }
}

function prevSlide() {
  if (currentIndex > 0) {
    currentIndex--;
    updateSlidePosition();
  }
}
</script>

   <!-- Advertisement -->
<div class="md:col-span-3 bg-white border border-gray-200 shadow-sm rounded-lg px-4 py-6 text-center self-start" style="max-width: 300px; height: 350px;">
  <h3 class="text-lg font-semibold text-gray-700 mb-4">📢 Sponsored Ad</h3>
  <?php
  $ad = $conn->prepare("SELECT * FROM news WHERE category = 'Ad' ORDER BY created_at DESC LIMIT 1");
  $ad->execute();
  $ad_result = $ad->get_result();
  if ($ad_row = $ad_result->fetch_assoc()) {
    echo '<div class="relative w-full h-52 overflow-hidden rounded">';
    echo '<img src="admin/' . htmlspecialchars($ad_row['image']) . '" alt="Ad" class="object-cover w-full h-full hover:opacity-90 transition" />';
    echo '<div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-3 py-2">';
    echo '<h4 class="text-white text-sm font-bold line-clamp-2">' . htmlspecialchars($ad_row['title']) . '</h4>';
    echo '</div>';
    echo '</div>';
  } else {
    echo '<p class="text-sm text-gray-500">No Ads Found</p>';
  }
  ?>
</div>

</section>





<!-- JavaScript for Slide Control -->
<script>
  let currentIndex = 0;
  const slidesWrapper = document.getElementById("slidesWrapper");
  const totalSlides = 4;

  function updateSlidePosition() {
    slidesWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function nextSlide() {
    if (currentIndex < totalSlides - 1) {
      currentIndex++;
      updateSlidePosition();
    }
  }

  function prevSlide() {
    if (currentIndex > 0) {
      currentIndex--;
      updateSlidePosition();
    }
  }
</script>
<!-- Center Wrapper -->
<!-- Latest Videos Section -->
<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <section class="bg-blue-900 text-white px-6 py-12 rounded-t-lg shadow-lg w-full max-w-5xl">
    <h2 class="text-3xl font-bold mb-8 border-b border-blue-300 pb-3">🎥 Latest Videos</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">
      
      <!-- Main Video Container -->
      <div id="videoContainer" class="md:col-span-2 flex justify-center">
        <div class="w-full max-w-[300px]" style="aspect-ratio: 16 / 9;">
          <iframe
            id="mainPlayer"
            class="w-full h-full rounded-xl shadow-lg ring-4 ring-blue-400 hover:ring-blue-200 transition duration-300"
            allowfullscreen
          ></iframe>
        </div>
      </div>

      <!-- Thumbnail List -->
      <div>
        <h3 class="text-2xl font-semibold mb-5">🔥 Select a Video</h3>
        <div id="thumbnailGrid" class="flex flex-col gap-4"></div>
      </div>

    </div>
  </section>
</div>

<script>
  const RSS_URL = 'https://www.youtube.com/feeds/videos.xml?channel_id=UCMj-2Il-DtSeGEw_-akRHvg';

  async function fetchRSS() {
    const response = await fetch(`https://api.rss2json.com/v1/api.json?rss_url=${encodeURIComponent(RSS_URL)}`);
    const data = await response.json();
    return data.items;
  }

  function isShortsVideo(item) {
    // Filter if link contains /shorts/ or title has "Shorts" or is very short
    const url = item.link.toLowerCase();
    const title = item.title.toLowerCase();
    return url.includes('/shorts') || title.includes('shorts') || title.length < 8;
  }

  function createThumbnail(item, isFirst = false) {
    const videoId = item.link.split("v=")[1];
    const thumbUrl = `https://i.ytimg.com/vi/${videoId}/mqdefault.jpg`;
    const title = item.title;

    const wrapper = document.createElement('div');
    wrapper.className = 'cursor-pointer bg-blue-800 hover:bg-blue-700 p-3 rounded-lg flex flex-col transition border border-transparent';
    if (isFirst) wrapper.classList.add('border-white', 'border-2');

    wrapper.innerHTML = `
      <div class="flex gap-3">
        <img src="${thumbUrl}" alt="${title}" class="w-28 h-16 object-cover rounded">
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-white truncate">${title}</p>
        </div>
      </div>
    `;

    wrapper.onclick = () => {
      document.getElementById('mainPlayer').src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
      document.querySelectorAll('#thumbnailGrid > div').forEach(el => el.classList.remove('border-2', 'border-white'));
      wrapper.classList.add('border-2', 'border-white');
    };

    return wrapper;
  }

  async function init() {
    const videos = await fetchRSS();
    const container = document.getElementById('thumbnailGrid');

    // Filter out Shorts
    const filtered = videos.filter(video => !isShortsVideo(video)).slice(0, 5);

    if (filtered.length === 0) {
      container.innerHTML = "<p>No regular videos found.</p>";
      return;
    }

    filtered.forEach((video, index) => {
      const thumb = createThumbnail(video, index === 0);
      container.appendChild(thumb);
    });

    // Load first non-short video
    const firstVideoId = filtered[0].link.split("v=")[1];
    document.getElementById('mainPlayer').src = `https://www.youtube.com/embed/${firstVideoId}`;
  }

  init();
</script>






<!-- Shorts Video Section -->
<!-- Shorts Video Section (Dynamic from DB) -->
<section class="py-10 bg-gray-100 rounded-b-lg shadow-inner max-w-7xl mx-auto px-6">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-gray-800">🎮 Shorts Video</h2>
    <div class="space-x-3">
      <button onclick="scrollLeft()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-1 rounded-full shadow transition duration-200">←</button>
      <button onclick="scrollRight()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-1 rounded-full shadow transition duration-200">→</button>
    </div>
  </div>
  <div id="shortsContainer" class="flex overflow-x-auto space-x-4 scroll-smooth scrollbar-hide">
    <?php
    include("admin/db.php");
    $result = $conn->query("SELECT * FROM shorts ORDER BY created_at DESC LIMIT 20");
    if ($result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
        $desc = htmlspecialchars($row['description']);
        $video = $row['video_path'];
        
        // Fix the video path - make sure it's accessible from frontend
        // If your frontend is in root and admin is in admin folder
        $videoUrl = 'admin/' . $video;
        
        // Check if file exists before displaying
        $fileExists = file_exists($videoUrl);
    ?>
      <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
        <?php if ($fileExists): ?>
          <video class="rounded-lg w-full h-64 object-cover" controls loop muted preload="metadata" onloadstart="this.muted=true">
            <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/mp4">
            <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/webm">
            <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/ogg">
            Your browser does not support the video tag.
          </video>
        <?php else: ?>
          <div class="rounded-lg w-full h-64 bg-gray-200 flex items-center justify-center">
            <div class="text-center text-gray-500">
              <i class="text-4xl">📹</i>
              <p class="text-sm mt-2">Video not found</p>
              <small class="text-xs text-gray-400">Path: <?php echo htmlspecialchars($video); ?></small>
            </div>
          </div>
        <?php endif; ?>
        <p class="text-sm mt-2 font-semibold text-gray-800"><?php echo $desc; ?></p>
        
        <!-- Debug info (remove in production) -->
        <?php if (!$fileExists): ?>
          <small class="text-xs text-red-500 block mt-1">
            Debug: Looking for file at: <?php echo htmlspecialchars($videoUrl); ?>
          </small>
        <?php endif; ?>
      </div>
    <?php endwhile; else: ?>
      <div class="min-w-[200px] bg-white rounded-xl shadow-md p-4 flex-shrink-0 text-center">
        <div class="text-6xl mb-4">📱</div>
        <p class="text-gray-600">No shorts uploaded yet.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>
  function scrollLeft() {
    document.getElementById('shortsContainer').scrollBy({ left: -220, behavior: 'smooth' });
  }
  function scrollRight() {
    document.getElementById('shortsContainer').scrollBy({ left: 220, behavior: 'smooth' });
  }
  
  // Auto-mute all videos on page load
  document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('#shortsContainer video');
    videos.forEach(video => {
      video.muted = true;
      video.addEventListener('loadeddata', function() {
        console.log('Video loaded:', this.src);
      });
      video.addEventListener('error', function() {
        console.error('Video failed to load:', this.src);
        // Replace video with error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'rounded-lg w-full h-64 bg-red-100 flex items-center justify-center text-red-600';
        errorDiv.innerHTML = '<div class="text-center"><i class="text-4xl">❌</i><p class="text-sm mt-2">Failed to load video</p></div>';
        this.parentNode.replaceChild(errorDiv, this);
      });
    });
  });
</script>

<style>
  /* Hide scrollbar but keep functionality */
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  
  /* Ensure videos don't exceed container */
  #shortsContainer video {
    max-width: 100%;
    height: 256px;
    object-fit: cover;
  }
</style>

<!-- Featured News Section - Centered -->
<section class="mb-12 py-10 max-w-7xl mx-auto px-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold text-gray-800">📰 Featured News</h2>
    <div class="space-x-2">
      <button onclick="scrollNewsLeft()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded">←</button>
      <button onclick="scrollNewsRight()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded">→</button>
    </div>
  </div>

  <div id="featuredNewsContainer" class="flex overflow-x-auto space-x-4 scroll-smooth scrollbar-hide">
    <?php
    include("admin/db.php");
    $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Featured News' ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      echo '<a href="news-detail.php?id=' . $row['id'] . '" class="w-64 h-64 bg-gray-100 rounded-lg shadow flex-shrink-0 overflow-hidden block">';
      echo '<div class="relative w-full h-full">';
      echo '<img src="admin/' . htmlspecialchars($row['image']) . '" class="object-cover w-full h-full" alt="' . htmlspecialchars($row['title']) . '">';
      echo '<div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-3 py-2">';
      echo '<h3 class="text-white font-bold text-sm leading-tight line-clamp-2">' . htmlspecialchars($row['title']) . '</h3>';
      echo '</div>';
      echo '</div>';
      echo '</a>';
    }
    ?>
  </div>
</section>

<!-- Slider Script -->
<script>
  const featuredContainer = document.getElementById("featuredNewsContainer");
  function scrollNewsLeft() {
    featuredContainer.scrollBy({ left: -300, behavior: 'smooth' });
  }
  function scrollNewsRight() {
    featuredContainer.scrollBy({ left: 300, behavior: 'smooth' });
  }
</script>




<!-- Health Section - Centered -->
<section class="mb-12 py-10 max-w-7xl mx-auto px-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-gray-800">🩺 Health</h2>
    <div>
      <button onclick="scrollHealth(-1)" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">←</button>
      <button onclick="scrollHealth(1)" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">→</button>
    </div>
  </div>

  <!-- Slider Container -->
  <div id="health-slider" class="flex overflow-x-auto space-x-4 scroll-smooth scrollbar-hide">
    <?php
    include("admin/db.php");
    $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Health' ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      echo '<a href="news-detail.php?id=' . $row['id'] . '" class="w-64 h-64 bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0 block">';
      echo '<div class="relative w-full h-full">';
      echo '<img src="admin/' . htmlspecialchars($row['image']) . '" class="object-cover w-full h-full" alt="' . htmlspecialchars($row['title']) . '">';
      echo '<div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-3 py-2">';
      echo '<h3 class="text-white font-bold text-sm leading-tight line-clamp-2">' . htmlspecialchars($row['title']) . '</h3>';
      echo '</div>';
      echo '</div>';
      echo '</a>';
    }
    ?>
  </div>
</section>

<!-- JavaScript for sliding -->
<script>
  function scrollHealth(direction) {
    const container = document.getElementById('health-slider');
    container.scrollBy({ left: direction * 270, behavior: 'smooth' });
  }
</script>




  </main>
  <!-- ======= Category Sliders ======= -->
  <section class="mb-12 py-10 max-w-7xl mx-auto px-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <?php
    include("admin/db.php");
    $sections = [
      'Business' => '💼 Business',
      'Technology' => '🧠 Technology',
      'Entertainment' => '🎬 Entertainment',
      'Politics' => '🏛️ Politics',
      'Popular News' => '🔥 Popular News',
      'Sports' => '🏏 Sports'
    ];

    foreach ($sections as $category => $title):
    ?>
    <div>
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-2xl font-semibold text-gray-800"><?php echo $title; ?></h2>
        <div class="space-x-2">
          <button onclick="scrollCards('<?php echo strtolower(str_replace(' ', '-', $category)); ?>', -1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">←</button>
          <button onclick="scrollCards('<?php echo strtolower(str_replace(' ', '-', $category)); ?>', 1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">→</button>
        </div>
      </div>
      <div id="scroll-<?php echo strtolower(str_replace(' ', '-', $category)); ?>" class="flex overflow-x-auto space-x-4 scroll-smooth snap-x snap-mandatory pb-2">
        <?php
        $stmt = $conn->prepare("SELECT * FROM news WHERE category = ? ORDER BY created_at DESC LIMIT 10");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()):
        ?>
          <a href="news-detail.php?id=<?php echo $row['id']; ?>" class="w-60 h-60 bg-white border rounded-lg shadow-sm hover:shadow-md flex-shrink-0 snap-start transition-transform duration-200 hover:scale-[1.02] overflow-hidden block">
            <div class="relative w-full h-full">
              <img src="admin/<?php echo htmlspecialchars($row['image']); ?>" class="object-cover w-full h-full" alt="<?php echo htmlspecialchars($row['title']); ?>">
              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-3 py-2">
                <p class="text-sm font-bold text-white leading-tight line-clamp-2"><?php echo htmlspecialchars($row['title']); ?></p>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>

<script>
  function scrollCards(section, dir) {
    document.getElementById(`scroll-${section}`)
            .scrollBy({left: dir * 250, behavior: 'smooth'});
  }
</script>
<script src="https://unpkg.com/feather-icons"></script>




<!-- Centered Main Section -->
<section class="mb-12 py-10 max-w-7xl mx-auto px-6">
  <div class="max-w-7xl mx-auto px-4 flex flex-col lg:flex-row gap-8">

    <!-- 🔥 Trending News - Now on Left -->
    <aside class="lg:w-2/3 w-full">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">🔥 Trending News</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <?php
        include("admin/db.php");
        $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Trending News' ORDER BY created_at DESC LIMIT 4");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()):
        ?>
        <!-- News Card -->
        <a href="news-detail.php?id=<?php echo $row['id']; ?>" class="w-full aspect-square relative bg-white rounded shadow overflow-hidden block">
          <img src="admin/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-full object-cover">
          <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3">
            <h3 class="font-semibold text-white text-sm leading-tight line-clamp-2"><?php echo htmlspecialchars($row['title']); ?></h3>
          </div>
        </a>
        <?php endwhile; ?>
      </div>
    </aside>
    <?php include 'includes/sidebar-follow.php'; ?>
  </div>
</section>




<!-- 🏷️ Tags Section -->
<section class="mb-12 py-10 max-w-7xl mx-auto px-6">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">🏷️ Tags</h2>
    <div class="flex flex-wrap gap-3">
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">Business</button>
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">Technology</button>
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">Politics</button>
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">Sports</button>
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">Entertainment</button>
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">India</button>
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">World</button>
      <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm">Finance</button>
    </div>
  </div>
</section>

<!-- Init feather icons -->
<script>
  feather.replace();
</script>


<!-- Activate Feather Icons -->
<script>
  feather.replace();
</script>


  

<?php include 'includes/footer.php'; ?>

</body>
</html>