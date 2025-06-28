
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


// Fetch all shorts data upfront for the popup
// Fetch all shorts data and store them in an array to pass to JS
$allShortsData = [];
$shortsResult = $conn->query("SELECT id, description, video_path FROM shorts ORDER BY created_at DESC LIMIT 20");
if ($shortsResult->num_rows > 0) {
    while ($row = $shortsResult->fetch_assoc()) {
        // Build the full video URL for JavaScript
        $videoUrl = 'admin/' . $row['video_path']; 
        
        // Check if file exists to prevent broken video tags
        $fileExists = file_exists($videoUrl);

        $allShortsData[] = [
            'id' => $row['id'],
            'description' => htmlspecialchars($row['description']),
            'video_url' => $fileExists ? htmlspecialchars($videoUrl) : null, // Only provide URL if file exists
            'fileExists' => $fileExists // Pass boolean for client-side check
        ];
    }
}
$shortsResult->free_result(); // Free up memory


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
      $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Trending' AND status = 'public' ORDER BY created_at DESC LIMIT 2");
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
      $carousel = $conn->prepare("SELECT * FROM news WHERE category != 'Ad' AND status = 'public' ORDER BY created_at DESC LIMIT 4");
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
let slideInterval;
const slidesWrapper = document.getElementById("slidesWrapper");
const slideItems = slidesWrapper.children;
const totalSlides = slideItems.length;

// Initialize the carousel
function initCarousel() {
  updateSlidePosition();
  startAutoSlide();
  
  // Pause on hover
  const carousel = document.getElementById('newsSlides');
  carousel.addEventListener('mouseenter', pauseAutoSlide);
  carousel.addEventListener('mouseleave', startAutoSlide);
}

function updateSlidePosition() {
  slidesWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
}

function nextSlide() {
  if (currentIndex < totalSlides - 1) {
    currentIndex++;
  } else {
    currentIndex = 0; // Loop back to first slide
  }
  updateSlidePosition();
  resetAutoSlide(); // Reset timer on manual navigation
}

function prevSlide() {
  if (currentIndex > 0) {
    currentIndex--;
  } else {
    currentIndex = totalSlides - 1; // Loop to last slide
  }
  updateSlidePosition();
  resetAutoSlide(); // Reset timer on manual navigation
}

function startAutoSlide() {
  slideInterval = setInterval(nextSlide, 2000); // Change slide every 2 seconds
}

function pauseAutoSlide() {
  clearInterval(slideInterval);
}

function resetAutoSlide() {
  clearInterval(slideInterval);
  startAutoSlide();
}

// Initialize the carousel when DOM is loaded
document.addEventListener('DOMContentLoaded', initCarousel);
</script>

   <!-- Advertisement -->
   <div class="md:col-span-3 bg-white border border-gray-200 shadow-sm rounded-lg px-4 py-6 text-center self-start" style="max-width: 300px; height: 350px;">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">📢 Sponsored Ad</h3>
      <?php
      $ad = $conn->prepare("SELECT * FROM news WHERE category = 'Ad' AND status = 'public' ORDER BY created_at DESC LIMIT 1");
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







<section class="py-10 bg-gray-100 rounded-b-lg shadow-inner max-w-7xl mx-auto px-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-800">🎮 Shorts Video</h2>
        <div class="space-x-3">
            <button onclick="scrollLeft()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-1 rounded-full shadow transition duration-200">←</button>
            <button onclick="scrollRight()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-1 rounded-full shadow transition duration-200">→</button>
        </div>
    </div>
    <div id="shortsContainer" class="flex overflow-x-auto space-x-4 scroll-smooth scrollbar-hide">
        <?php if (!empty($allShortsData)): ?>
            <?php foreach ($allShortsData as $index => $short): ?>
                <div class="shorts-thumbnail min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200 cursor-pointer" 
                     data-short-id="<?php echo $short['id']; ?>"
                     data-short-index="<?php echo $index; ?>">
                    <?php if ($short['fileExists']): ?>
                        <video class="rounded-lg w-full h-64 object-cover" 
                               src="<?php echo $short['video_url']; ?>#t=1" 
                               preload="metadata" 
                               muted 
                               playsinline>
                            Your browser does not support the video tag.
                        </video>
                        <p class="text-sm mt-2 font-semibold text-gray-800 line-clamp-2"><?php echo $short['description']; ?></p>
                    <?php else: ?>
                        <div class="rounded-lg w-full h-64 bg-gray-200 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <i class="text-4xl">📹</i>
                                <p class="text-sm mt-2">Video not found</p>
                                <small class="text-xs text-gray-400">Path: <?php echo htmlspecialchars($short['video_url']); ?></small>
                            </div>
                        </div>
                        <p class="text-sm mt-2 font-semibold text-gray-800"><?php echo $short['description']; ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="min-w-[200px] bg-white rounded-xl shadow-md p-4 flex-shrink-0 text-center">
                <div class="text-6xl mb-4">📱</div>
                <p class="text-gray-600">No shorts uploaded yet.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Featured News Section -->
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
        $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Featured News' AND status = 'public' ORDER BY created_at DESC LIMIT 10");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo '<div class="w-64 h-64 flex-shrink-0">';
            echo '<a href="news-detail.php?id=' . $row['id'] . '" class="block w-full h-full bg-gray-100 rounded-lg shadow overflow-hidden">';
            echo '<div class="relative w-full h-full">';
            echo '<img src="admin/' . htmlspecialchars($row['image']) . '" class="object-cover w-full h-full" alt="' . htmlspecialchars($row['title']) . '">';
            echo '<div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent px-3 py-2">';
            echo '<h3 class="text-white font-bold text-sm leading-tight line-clamp-2">' . htmlspecialchars($row['title']) . '</h3>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
        $stmt->free_result();
        ?>
    </div>
</section>

<script>
// Auto-scrolling functionality
let currentScrollPosition = 0;
let scrollInterval;
const scrollAmount = 268; // Width of card (256px) + gap (12px)
const container = document.getElementById('featuredNewsContainer');
const newsItems = container.querySelectorAll('div');
let maxScroll = (newsItems.length - 1) * scrollAmount;

function scrollNewsLeft() {
    currentScrollPosition = Math.max(0, currentScrollPosition - scrollAmount);
    container.scrollTo({
        left: currentScrollPosition,
        behavior: 'smooth'
    });
    resetAutoScroll();
}

function scrollNewsRight() {
    currentScrollPosition = Math.min(maxScroll, currentScrollPosition + scrollAmount);
    container.scrollTo({
        left: currentScrollPosition,
        behavior: 'smooth'
    });
    resetAutoScroll();
}

function autoScroll() {
    if (currentScrollPosition >= maxScroll) {
        currentScrollPosition = 0;
        container.scrollTo({
            left: 0,
            behavior: 'smooth'
        });
    } else {
        currentScrollPosition += scrollAmount;
        container.scrollTo({
            left: currentScrollPosition,
            behavior: 'smooth'
        });
    }
}

function startAutoScroll() {
    scrollInterval = setInterval(autoScroll, 2000); // Scroll every 2 seconds
}

function resetAutoScroll() {
    clearInterval(scrollInterval);
    startAutoScroll();
}

// Pause on hover
container.addEventListener('mouseenter', () => clearInterval(scrollInterval));
container.addEventListener('mouseleave', startAutoScroll);

// Initialize
startAutoScroll();
</script>

<script>
// Scroll amount (in pixels) for each button click
const SCROLL_AMOUNT = 300;

function scrollNewsLeft() {
    const container = document.getElementById('featuredNewsContainer');
    container.scrollBy({
        left: -SCROLL_AMOUNT,
        behavior: 'smooth'
    });
}

function scrollNewsRight() {
    const container = document.getElementById('featuredNewsContainer');
    container.scrollBy({
        left: SCROLL_AMOUNT,
        behavior: 'smooth'
    });
}

// Optional: Disable/enable buttons based on scroll position
function updateButtonStates() {
    const container = document.getElementById('featuredNewsContainer');
    const leftBtn = document.querySelector('button[onclick="scrollNewsLeft()"]');
    const rightBtn = document.querySelector('button[onclick="scrollNewsRight()"]');
    
    // Disable left button if at start
    leftBtn.disabled = container.scrollLeft <= 0;
    
    // Disable right button if at end (or nearly at end)
    rightBtn.disabled = container.scrollLeft + container.clientWidth >= container.scrollWidth - 10;
}

// Update button states when scrolling
document.getElementById('featuredNewsContainer').addEventListener('scroll', updateButtonStates);

// Initialize button states on load
document.addEventListener('DOMContentLoaded', updateButtonStates);
</script>

<div id="shortsModal" class="shorts-modal hidden">
    <div class="shorts-modal-content">
        <span class="shorts-close-btn">&times;</span>
        <div class="shorts-video-feed">
            </div>
    </div>
</div>

<script>
    // PHP data passed to JavaScript
    const allShorts = <?php echo json_encode($allShortsData); ?>;

    // --- Start of Frontend Shorts UI & Analytics JavaScript ---

    function scrollLeft() {
        document.getElementById('shortsContainer').scrollBy({ left: -220, behavior: 'smooth' });
    }
    function scrollRight() {
        document.getElementById('shortsContainer').scrollBy({ left: 220, behavior: 'smooth' });
    }

    // Function to record a short view via AJAX
    function recordShortView(shortsId) {
        const viewedShortsKey = 'viewedShortsToday';
        let viewedShorts = JSON.parse(sessionStorage.getItem(viewedShortsKey)) || {};
        const today = new Date().toISOString().slice(0, 10); //YYYY-MM-DD format

        if (viewedShorts[shortsId] === today) {
            console.log(`View for Short ID ${shortsId} already recorded in this session today.`);
            return;
        }

        fetch(`admin/view_short.php?shorts_id=${shortsId}`) // Adjust path if needed
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    console.log(`Short ID ${shortsId} view recorded successfully!`);
                    viewedShorts[shortsId] = today;
                    sessionStorage.setItem(viewedShortsKey, JSON.stringify(viewedShorts));
                } else {
                    console.error(`Error recording short view for ID ${shortsId}:`, data.message);
                }
            })
            .catch(error => {
                console.error('Fetch operation failed:', error);
            });
    }

    // Modal elements
    const shortsModal = document.getElementById('shortsModal');
    const shortsCloseBtn = document.querySelector('.shorts-close-btn');
    const shortsVideoFeed = document.querySelector('.shorts-video-feed');
    let currentShortIndex = 0;
    let playingVideoElement = null; // To keep track of the currently playing video

    // Function to render a single short in the modal
    function renderShort(short, index) {
        const shortItem = document.createElement('div');
        shortItem.classList.add('shorts-modal-item');
        shortItem.dataset.shortIndex = index;

        if (short.fileExists && short.video_url) {
            shortItem.innerHTML = `
                <video class="shorts-modal-video" src="${short.video_url}" loop muted playsinline></video>
                <button class="play-pause-btn" aria-label="Play/Pause">
                    <i class="play-pause-icon">▶</i>
                </button>
                <div class="shorts-modal-description">
                    <p>${short.description}</p>
                </div>
            `;
        } else {
            shortItem.innerHTML = `
                <div class="shorts-modal-video-placeholder">
                    <i class="text-4xl">❌</i>
                    <p class="text-sm mt-2">Video not available</p>
                    <small class="text-xs text-gray-400">Path: ${short.video_url || 'N/A'}</small>
                </div>
                <div class="shorts-modal-description">
                    <p>${short.description}</p>
                </div>
            `;
        }
        return shortItem;
    }

    // Function to open the modal
    function openShortsModal(startIndex) {
        currentShortIndex = startIndex;
        shortsVideoFeed.innerHTML = ''; // Clear previous content

        // Load all shorts for smooth scrolling
        for (let i = 0; i < allShorts.length; i++) {
            const short = allShorts[i];
            const shortElement = renderShort(short, i);
            shortsVideoFeed.appendChild(shortElement);
        }
        
        shortsModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent body scrolling

        // Scroll to the selected short and start playing
        setTimeout(() => { // Small delay to allow rendering
            const targetElement = shortsVideoFeed.querySelector(`[data-short-index="${startIndex}"]`);
            if (targetElement) {
                shortsVideoFeed.scrollTop = targetElement.offsetTop;
                playShortAtIndex(startIndex);
            }
            // Add event listeners for play/pause buttons after elements are rendered
            addPlayPauseButtonListeners();
        }, 50); // Adjust delay if needed
    }

    // Function to toggle play/pause for a specific video
    function togglePlayPause(videoElement, buttonElement) {
        if (!videoElement || !buttonElement) return;

        const iconElement = buttonElement.querySelector('.play-pause-icon');

        if (videoElement.paused) {
            videoElement.play().catch(error => {
                console.error("Video play failed:", error);
            });
            // When playing, hide the button
            buttonElement.classList.add('hidden');
        } else {
            videoElement.pause();
            // When paused, show the button and set to play icon
            buttonElement.classList.remove('hidden');
            iconElement.textContent = '▶';
        }
    }

    // Update play/pause button state based on video state
    function updatePlayPauseButtonState(videoElement, buttonElement) {
        if (!videoElement || !buttonElement) return;

        const iconElement = buttonElement.querySelector('.play-pause-icon');
        if (videoElement.paused) {
            buttonElement.classList.remove('hidden'); // Show button
            iconElement.textContent = '▶'; // Set to play icon
        } else {
            buttonElement.classList.add('hidden'); // Hide button
        }
    }

    // Add click listeners to all play/pause buttons
    function addPlayPauseButtonListeners() {
        shortsVideoFeed.querySelectorAll('.play-pause-btn').forEach(button => {
            button.onclick = (event) => {
                event.stopPropagation(); // Prevent clicks from interacting with the video directly if you add video click listeners later
                const videoElement = button.closest('.shorts-modal-item').querySelector('.shorts-modal-video');
                togglePlayPause(videoElement, button);
            };
        });

        // Add a click listener to the video itself to toggle play/pause and show/hide button
        shortsVideoFeed.querySelectorAll('.shorts-modal-video').forEach(video => {
            video.onclick = (event) => {
                event.stopPropagation();
                const buttonElement = video.closest('.shorts-modal-item').querySelector('.play-pause-btn');
                togglePlayPause(video, buttonElement);
            };

            // Event listeners to update button visibility
            video.onplay = () => {
                const buttonElement = video.closest('.shorts-modal-item').querySelector('.play-pause-btn');
                if (buttonElement) buttonElement.classList.add('hidden'); // Hide button when playing
            };
            video.onpause = () => {
                const buttonElement = video.closest('.shorts-modal-item').querySelector('.play-pause-btn');
                if (buttonElement) {
                    buttonElement.classList.remove('hidden'); // Show button when paused
                    buttonElement.querySelector('.play-pause-icon').textContent = '▶'; // Ensure it's a play icon
                }
            };
            video.onended = () => {
                const buttonElement = video.closest('.shorts-modal-item').querySelector('.play-pause-btn');
                if (buttonElement) {
                    buttonElement.classList.remove('hidden'); // Show button when ended (which is a paused state)
                    buttonElement.querySelector('.play-pause-icon').textContent = '▶';
                }
            };
        });
    }


    // Function to play a specific short and pause others
    function playShortAtIndex(index) {
        // Pause and reset the previously playing video
        if (playingVideoElement) {
            playingVideoElement.pause();
            playingVideoElement.currentTime = 0; // Reset for next time
            playingVideoElement.muted = true; // Ensure it stays muted when out of focus
            // Show play button for previously playing video
            const prevButton = playingVideoElement.closest('.shorts-modal-item')?.querySelector('.play-pause-btn');
            if (prevButton) {
                prevButton.classList.remove('hidden');
                prevButton.querySelector('.play-pause-icon').textContent = '▶';
            }
        }

        const targetElement = shortsVideoFeed.querySelector(`[data-short-index="${index}"]`);
        if (targetElement) {
            const video = targetElement.querySelector('.shorts-modal-video');
            const button = targetElement.querySelector('.play-pause-btn');

            if (video) {
                video.muted = false; // Unmute current playing video
                video.play().catch(error => {
                    console.error("Video play failed:", error);
                    // This often happens if not muted on autoplay. The user will have to click the button.
                });
                playingVideoElement = video;
                // Hide button immediately as video attempts to play
                if (button) button.classList.add('hidden'); 
                recordShortView(allShorts[index].id); // Record view when video starts playing
            }
        }
        currentShortIndex = index;
    }

    // Handle scroll to detect current video and play it
    let scrollTimeout;
    shortsVideoFeed.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            // Get all shorts items
            const shortItems = Array.from(shortsVideoFeed.children);
            let closestItem = null;
            let minDistance = Infinity;

            // Determine which short item is most "in view"
            shortItems.forEach(item => {
                const rect = item.getBoundingClientRect();
                // Calculate distance from center of viewport to center of item
                const viewportCenter = window.innerHeight / 2;
                const itemCenter = rect.top + rect.height / 2;
                const distance = Math.abs(viewportCenter - itemCenter);

                if (distance < minDistance) {
                    minDistance = distance;
                    closestItem = item;
                }
            });

            if (closestItem) {
                const newIndex = parseInt(closestItem.dataset.shortIndex);
                // Only play if it's a new short or if the currently playing one is off-screen
                if (newIndex !== currentShortIndex || !playingVideoElement || !closestItem.contains(playingVideoElement)) {
                    playShortAtIndex(newIndex);
                }
            }
        }, 150); // Debounce scroll event
    });

    // Close modal
    shortsCloseBtn.addEventListener('click', () => {
        shortsModal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore body scrolling
        if (playingVideoElement) {
            playingVideoElement.pause();
            playingVideoElement = null; // Clear reference
        }
    });

    // Handle clicks on thumbnails to open modal
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnailElements = document.querySelectorAll('.shorts-thumbnail');
        thumbnailElements.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const startIndex = parseInt(this.dataset.shortIndex);
                openShortsModal(startIndex);
            });
            
            // Also mute initial videos in the carousel
            const carouselVideo = thumbnail.querySelector('video');
            if(carouselVideo) {
                carouselVideo.muted = true;
                // Optional: Play on hover for a preview effect in the carousel
                thumbnail.addEventListener('mouseenter', () => carouselVideo.play().catch(e => console.log("Play failed on hover:",e)));
                thumbnail.addEventListener('mouseleave', () => carouselVideo.pause());
            }
        });

        // Error handling for initial carousel videos
        const videos = document.querySelectorAll('#shortsContainer video');
        videos.forEach(video => {
            video.addEventListener('loadeddata', function() {
                console.log('Carousel Video loaded:', this.src);
            });
            video.addEventListener('error', function() {
                console.error('Carousel Video failed to load:', this.src);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'rounded-lg w-full h-64 bg-red-100 flex items-center justify-center text-red-600';
                errorDiv.innerHTML = '<div class="text-center"><i class="text-4xl">❌</i><p class="text-sm mt-2">Failed to load video</p></div>';
                this.parentNode.replaceChild(errorDiv, this);
            });
        });
    });

    // --- End of Frontend Shorts UI & Analytics JavaScript ---
</script>

---

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

    /* --- New Modal Styles --- */
    .shorts-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9); /* Dark overlay */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000; /* Ensure it's on top */
    }

    .shorts-modal.hidden {
        display: none;
    }

    .shorts-modal-content {
        position: relative;
        width: 100%;
        height: 100%;
        max-width: 400px; /* Max width for the shorts feed */
        max-height: 90vh; /* Max height for the feed */
        background-color: #000; /* Black background for videos */
        border-radius: 10px;
        overflow: hidden; /* For rounded corners */
        display: flex;
        flex-direction: column;
    }

    .shorts-close-btn {
        position: absolute;
        top: 15px;
        right: 25px;
        color: #fff;
        font-size: 35px;
        font-weight: bold;
        cursor: pointer;
        z-index: 1010; /* Above video content */
        background-color: rgba(0,0,0,0.5);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .shorts-close-btn:hover {
        color: #ddd;
    }

    .shorts-video-feed {
        flex-grow: 1;
        overflow-y: scroll; /* Enable vertical scrolling */
        scroll-snap-type: y mandatory; /* Smooth snap effect */
        -webkit-overflow-scrolling: touch; /* For smoother scrolling on iOS */
        display: flex;
        flex-direction: column;
    }

    .shorts-modal-item {
        flex-shrink: 0; /* Important: prevents items from shrinking */
        width: 100%;
        height: 100vh; /* Each item takes full viewport height for scrolling */
        display: flex;
        flex-direction: column;
        justify-content: center; /* Center video vertically */
        align-items: center;
        position: relative;
        scroll-snap-align: center; /* Snap to center when scrolling */
        background-color: #000; /* Fallback for video background */
        /* cursor: pointer; REMOVED to avoid confusion, button handles click */
    }

    .shorts-modal-video {
        width: 100%;
        max-height: 100%; /* Ensure video fits within the height */
        object-fit: contain; /* Maintain aspect ratio and fit within bounds */
        display: block; /* Remove extra space below video */
    }

    .shorts-modal-video-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #333;
        color: #ccc;
        text-align: center;
    }

    .shorts-modal-description {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        color: #fff;
        padding: 20px;
        font-size: 1.1rem;
        text-align: center;
        padding-bottom: 50px; /* Space for future controls/info */
        z-index: 5; /* Ensure description is above video */
        pointer-events: none; /* Description shouldn't block clicks to video/button */
    }
    
    /* --- Custom Play/Pause Button Styles --- */
    .play-pause-btn {
        position: absolute; /* Position over the video */
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); /* Center it perfectly */
        background-color: rgba(0, 0, 0, 0.2); /* Light dark background */
        border: none;
        border-radius: 50%; /* Make it circular */
        width: 70px;
        height: 70px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: background-color 0.2s ease, opacity 0.3s ease;
        z-index: 10; /* Above video, below close button */
        pointer-events: all; /* Make button clickable */
        opacity: 1; /* Default visible state (when paused) */
    }

    .play-pause-btn:hover {
        background-color: rgba(0, 0, 0, 0.4); /* Slightly darker on hover */
    }

    /* When the video is playing, the button itself gets the 'hidden' class */
    .play-pause-btn.hidden {
        opacity: 0; /* Fade out */
        pointer-events: none; /* No interaction when hidden */
    }

    .play-pause-btn .play-pause-icon {
        color: white;
        font-size: 3rem; /* Large icon */
        line-height: 1; /* Adjust vertical alignment */
        pointer-events: none; /* Icon itself shouldn't block clicks on the button */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .shorts-modal-content {
            max-width: 100%; /* Full width on smaller screens */
            max-height: 100vh; /* Full height on smaller screens */
            border-radius: 0; /* No rounded corners on mobile */
        }
        .shorts-close-btn {
            top: 10px;
            right: 10px;
            font-size: 30px;
        }
        .play-pause-btn {
            width: 60px;
            height: 60px;
        }
        .play-pause-btn .play-pause-icon {
            font-size: 2.5rem;
        }
    }
</style>

<!-- Health Section - Centered -->
<section class="mb-12 py-10 max-w-7xl mx-auto px-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-gray-800">🩺 Health</h2>
    <div>
      <button onclick="scrollHealth(-1)" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">←</button>
      <button onclick="scrollHealth(1)" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">→</button>
    </div>
  </div>

  <div id="health-slider" class="flex overflow-x-auto space-x-4 scroll-smooth scrollbar-hide">
    <?php
    $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Health' AND status = 'public' ORDER BY created_at DESC LIMIT 10");
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
        $stmt = $conn->prepare("SELECT * FROM news WHERE category = ? AND status = 'public' ORDER BY created_at DESC LIMIT 10");
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
    <aside class="lg:w-2/3 w-full">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">🔥 Trending News</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <?php
        $stmt = $conn->prepare("SELECT * FROM news WHERE category = 'Trending News' AND status = 'public' ORDER BY created_at DESC LIMIT 4");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()):
        ?>
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