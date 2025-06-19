<!-- Inside index.php -->
<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="UTF-8">
  <meta name="google" content="notranslate">
  <title>HSR News</title>
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
</head>
<body class="bg-gray-100 text-gray-800">



<?php include 'includes/header.php'; ?>



<?php include 'includes/language-switcher.php'; ?>
<?php include 'includes/navbar.php'; ?>

<?php include 'includes/notification-bell.php'; ?>
<!-- 1. Trending Section-->
<section class="bg-gray-50 px-6 py-10">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-6">

    <!-- 1. Trending Section (Left Column) -->
    <div class="md:col-span-3 space-y-4">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">🔥 Trending</h2>

      <!-- Trending Cards -->
      <div class="bg-white p-4 rounded shadow">
        <img src="a.jpg" alt="Trending" class="w-full h-28 object-cover mb-2 rounded" />
        <h3 class="text-sm font-medium text-gray-800">Trump Says Zelenskyy Wants 'Deal' To Stop Ukraine War</h3>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <img src="a.jpg" alt="Trending" class="w-full h-28 object-cover mb-2 rounded" />
        <h3 class="text-sm font-medium text-gray-800">Jaishankar Meets Ukrainian Counterpart On Sidelines</h3>
      </div>
    </div>

    <!-- News Carousel (Middle Column) -->
    <div class="md:col-span-6 bg-white p-6 rounded-lg shadow relative overflow-hidden">
      <div id="newsSlides" class="relative w-full overflow-hidden">
        <div id="slidesWrapper" class="flex transition-transform duration-500 ease-in-out" style="width: 400%;">

          <!-- Slide 1 -->
          <div class="w-full md:w-full flex-shrink-0 px-4">
            <img src="a.jpg" class="w-full h-64 object-cover rounded mb-4 shadow-sm" alt="News 1">

            <h2 class="text-2xl font-bold text-gray-800 mb-2 leading-snug">🇺🇸 Won't Tolerate Illegal Immigration: US Embassy</h2>
            <p class="text-sm text-gray-500 mb-1">Updated: June 17, 2025</p>
            <p class="text-xs text-red-600 font-medium">Category: Breaking News</p>
          </div>

          <!-- Slide 2 -->
          <div class="w-full md:w-full flex-shrink-0 px-4">
            <img src="a.jpg" class="w-full h-64 object-cover rounded mb-4 shadow-sm" alt="News 2">

            <h2 class="text-2xl font-bold text-gray-800 mb-2 leading-snug">🇮🇳 PM Modi Welcomed in US</h2>
            <p class="text-sm text-gray-500 mb-1">Updated: June 16, 2025</p>
            <p class="text-xs text-red-600 font-medium">Category: World News</p>
          </div>

          <!-- Slide 3 -->
          <div class="w-full md:w-full flex-shrink-0 px-4">
            <img src="a.jpg" class="w-full h-64 object-cover rounded mb-4 shadow-sm" alt="News 3">

            <h2 class="text-2xl font-bold text-gray-800 mb-2 leading-snug">🛰️ ISRO Launches New Satellite</h2>
            <p class="text-sm text-gray-500 mb-1">Updated: June 15, 2025</p>
            <p class="text-xs text-red-600 font-medium">Category: Technology</p>
          </div>

          <!-- Slide 4 -->
          <div class="w-full md:w-full flex-shrink-0 px-4">
            <img src="a.jpg" class="w-full h-64 object-cover rounded mb-4 shadow-sm" alt="News 4">

            <h2 class="text-2xl font-bold text-gray-800 mb-2 leading-snug">⚽ India Qualifies for World Cup</h2>
            <p class="text-sm text-gray-500 mb-1">Updated: June 14, 2025</p>
            <p class="text-xs text-red-600 font-medium">Category: Sports</p>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="absolute top-1/2 left-0 -translate-y-1/2 px-2 z-10">
        <button onclick="prevSlide()" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-3 rounded-full shadow">&larr;</button>
      </div>
      <div class="absolute top-1/2 right-0 -translate-y-1/2 px-2 z-10">
        <button onclick="nextSlide()" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-3 rounded-full shadow">&rarr;</button>
      </div>
    </div>

    <!-- Advertisement (Right Column) -->
    <div class="md:col-span-3 bg-white border border-gray-200 shadow-sm rounded-lg px-4 py-6 text-center">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">📢 Sponsored Ad</h3>
      <a href="https://your-ad-link.com" target="_blank" rel="noopener noreferrer">
        <img src="a.jpg" alt="Advertisement Banner" class="w-full h-auto mx-auto hover:opacity-90 transition rounded" />
      </a>
      <p class="text-sm text-gray-500 mt-2">Advertisement by YourBrand</p>
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






<!-- Shorts Video Section -->
<!-- Shorts Video Section - Centered -->
<section class="py-10 bg-gray-100 rounded-b-lg shadow-inner max-w-7xl mx-auto px-6">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-gray-800">🎬 Shorts Video</h2>
    <div class="space-x-3">
      <button onclick="scrollLeft()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-1 rounded-full shadow transition duration-200">←</button>
      <button onclick="scrollRight()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-1 rounded-full shadow transition duration-200">→</button>
    </div>
  </div>

  <div id="shortsContainer" class="flex overflow-x-auto space-x-4 scroll-smooth scrollbar-hide">
    <!-- Shorts Cards -->
    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 1</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 2</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 3</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 4</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 5</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 6</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 7</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 8</p>
    </div>

    <div class="min-w-[200px] bg-white rounded-xl shadow-md p-2 flex-shrink-0 transition hover:scale-[1.02] duration-200">
      <video class="rounded-lg w-full h-64 object-cover" controls loop muted>
        <source src="a.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <p class="text-sm mt-2 font-semibold text-gray-800">Short 9</p>
    </div>
  </div>
</section>


<script>
  function scrollLeft() {
    document.getElementById('shortsContainer').scrollBy({ left: -220, behavior: 'smooth' });
  }

  function scrollRight() {
    document.getElementById('shortsContainer').scrollBy({ left: 220, behavior: 'smooth' });
  }
</script>

<!-- Slider Script -->
<script>
  const container = document.getElementById("shortsContainer");
  function scrollLeft() {
    container.scrollBy({ left: -220, behavior: 'smooth' });
  }
  function scrollRight() {
    container.scrollBy({ left: 220, behavior: 'smooth' });
  }
</script>

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
    <!-- News Card -->
    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">AI Revolutionizes Healthcare</h3>
      <p class="text-sm text-gray-600 mt-1">New AI tools are changing the way diagnoses are made globally.</p>
    </div>

    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">NASA's Mars Rover Discovers Ice</h3>
      <p class="text-sm text-gray-600 mt-1">Underground ice could help future missions and even support life.</p>
    </div>

    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">Global Markets Rally</h3>
      <p class="text-sm text-gray-600 mt-1">Tech stocks lead the way as investor confidence returns.</p>
    </div>

    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">India Wins T20 World Cup</h3>
      <p class="text-sm text-gray-600 mt-1">Historic victory as India defeats England in the finals.</p>
    </div>

    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">Climate Summit 2025 Begins</h3>
      <p class="text-sm text-gray-600 mt-1">World leaders gather to tackle climate change goals.</p>
    </div>

    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">Major Data Breach Exposes Millions</h3>
      <p class="text-sm text-gray-600 mt-1">Hackers target financial institutions with advanced malware.</p>
    </div>

    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">Humanoid Robots Now in Stores</h3>
      <p class="text-sm text-gray-600 mt-1">Robots assist customers in Japan’s largest department chains.</p>
    </div>

    <div class="min-w-[280px] bg-gray-100 rounded-lg shadow p-3 flex-shrink-0">
      <img src="a.jpg" class="rounded w-full h-40 object-cover mb-2" alt="News">
      <h3 class="font-semibold text-lg text-gray-800">Bitcoin Surges Past $80K</h3>
      <p class="text-sm text-gray-600 mt-1">Crypto sees unexpected boom driven by institutional demand.</p>
    </div>
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
    <!-- Card 1 -->
    <div class="min-w-[250px] bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0">
      <img src="a.jpg" class="w-full h-40 object-cover" alt="Health">
      <div class="p-4">
        <h3 class="font-semibold mb-2">Men's Health Month Isn't Just About Medical Checkups</h3>
        <p class="text-sm text-gray-600">June focuses on mental and physical wellness awareness for men.</p>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="min-w-[250px] bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0">
      <img src="a.jpg" class="w-full h-40 object-cover" alt="Medicine">
      <div class="p-4">
        <h3 class="font-semibold mb-2">From Eye Drops To Paracetamol: Drugs Found Substandard</h3>
        <p class="text-sm text-gray-600">CDSCO flags common medicines for quality issues.</p>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="min-w-[250px] bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0">
      <img src="a.jpg" class="w-full h-40 object-cover" alt="Malaria">
      <div class="p-4">
        <h3 class="font-semibold mb-2">Delhi Schools Launch Anti-Malaria Campaign</h3>
        <p class="text-sm text-gray-600">Students raise awareness on mosquito-borne diseases.</p>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="min-w-[250px] bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0">
      <img src="a.jpg" class="w-full h-40 object-cover" alt="Mental Health">
      <div class="p-4">
        <h3 class="font-semibold mb-2">Therapy Awareness Week Sees Record Participation</h3>
        <p class="text-sm text-gray-600">Counseling sessions rise as stigma declines.</p>
      </div>
    </div>

    <!-- Card 5 -->
    <div class="min-w-[250px] bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0">
      <img src="a.jpg" class="w-full h-40 object-cover" alt="Doctor">
      <div class="p-4">
        <h3 class="font-semibold mb-2">Rural Clinics See Rise in Preventive Checkups</h3>
        <p class="text-sm text-gray-600">Access to health services improves in remote areas.</p>
      </div>
    </div>

    <!-- Card 6 -->
    <div class="min-w-[250px] bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0">
      <img src="a.jpg" class="w-full h-40 object-cover" alt="Wellness">
      <div class="p-4">
        <h3 class="font-semibold mb-2">Yoga and Nutrition Trends Dominate This Season</h3>
        <p class="text-sm text-gray-600">Experts highlight simple lifestyle changes for better health.</p>
      </div>
    </div>
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
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
  
      <!-- 💼 Business -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-2xl font-semibold text-gray-800">💼 Business</h2>
          <div class="space-x-2">
            <button onclick="scrollCards('business',-1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">←</button>
            <button onclick="scrollCards('business',1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">→</button>
          </div>
        </div>
        <div id="scroll-business" class="flex overflow-x-auto space-x-4 scroll-smooth snap-x snap-mandatory pb-2">
          <template id="business-card">
            <div class="min-w-[220px] bg-white border rounded-lg shadow-sm hover:shadow-md flex-shrink-0 snap-start transition-transform duration-200 hover:scale-[1.02] overflow-hidden">
              <img src="a.jpg" class="h-40 w-full object-cover" alt="Business news">
              <div class="p-3">
                <p class="text-sm font-medium text-gray-800">Stock market hits record highs.</p>
              </div>
            </div>
          </template>
        </div>
      </div>
  
      <!-- 🧠 Technology -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-2xl font-semibold text-gray-800">🧠 Technology</h2>
          <div class="space-x-2">
            <button onclick="scrollCards('tech',-1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">←</button>
            <button onclick="scrollCards('tech',1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">→</button>
          </div>
        </div>
        <div id="scroll-tech" class="flex overflow-x-auto space-x-4 scroll-smooth snap-x snap-mandatory pb-2">
          <template id="tech-card">
            <div class="min-w-[220px] bg-white border rounded-lg shadow-sm hover:shadow-md flex-shrink-0 snap-start transition-transform duration-200 hover:scale-[1.02] overflow-hidden">
              <img src="a.jpg" class="h-40 w-full object-cover" alt="Tech news">
              <div class="p-3"><p class="text-sm font-medium text-gray-800">AI tools are transforming work.</p></div>
            </div>
          </template>
        </div>
      </div>
  
      <!-- 🎬 Entertainment -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-2xl font-semibold text-gray-800">🎬 Entertainment</h2>
          <div class="space-x-2">
            <button onclick="scrollCards('entertainment',-1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">←</button>
            <button onclick="scrollCards('entertainment',1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">→</button>
          </div>
        </div>
        <div id="scroll-entertainment" class="flex overflow-x-auto space-x-4 scroll-smooth snap-x snap-mandatory pb-2">
          <template id="entertainment-card">
            <div class="min-w-[220px] bg-white border rounded-lg shadow-sm hover:shadow-md flex-shrink-0 snap-start transition-transform duration-200 hover:scale-[1.02] overflow-hidden">
              <img src="a.jpg" class="h-40 w-full object-cover" alt="Entertainment">
              <div class="p-3"><p class="text-sm font-medium text-gray-800">Blockbuster hits ₹500 cr mark.</p></div>
            </div>
          </template>
        </div>
      </div>
  
      <!-- 🏛️ Politics -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-2xl font-semibold text-gray-800">🏛️ Politics</h2>
          <div class="space-x-2">
            <button onclick="scrollCards('politics',-1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">←</button>
            <button onclick="scrollCards('politics',1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">→</button>
          </div>
        </div>
        <div id="scroll-politics" class="flex overflow-x-auto space-x-4 scroll-smooth snap-x snap-mandatory pb-2">
          <template id="politics-card">
            <div class="min-w-[220px] bg-white border rounded-lg shadow-sm hover:shadow-md flex-shrink-0 snap-start transition-transform duration-200 hover:scale-[1.02] overflow-hidden">
              <img src="a.jpg" class="h-40 w-full object-cover" alt="Politics">
              <div class="p-3"><p class="text-sm font-medium text-gray-800">New budget announced in Parliament.</p></div>
            </div>
          </template>
        </div>
      </div>
  
      <!-- 🔥 Popular -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-2xl font-semibold text-gray-800">🔥 Popular News</h2>
          <div class="space-x-2">
            <button onclick="scrollCards('popular',-1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">←</button>
            <button onclick="scrollCards('popular',1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">→</button>
          </div>
        </div>
        <div id="scroll-popular" class="flex overflow-x-auto space-x-4 scroll-smooth snap-x snap-mandatory pb-2">
          <template id="popular-card">
            <div class="min-w-[220px] bg-white border rounded-lg shadow-sm hover:shadow-md flex-shrink-0 snap-start transition-transform duration-200 hover:scale-[1.02] overflow-hidden">
              <img src="a.jpg" class="h-40 w-full object-cover" alt="Popular">
              <div class="p-3"><p class="text-sm font-medium text-gray-800">Flood alerts in 3 states after heavy rains.</p></div>
            </div>
          </template>
        </div>
      </div>
  
      <!-- 🏏 Sports -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-2xl font-semibold text-gray-800">🏏 Sports</h2>
          <div class="space-x-2">
            <button onclick="scrollCards('sports',-1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">←</button>
            <button onclick="scrollCards('sports',1)" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow">→</button>
          </div>
        </div>
        <div id="scroll-sports" class="flex overflow-x-auto space-x-4 scroll-smooth snap-x snap-mandatory pb-2">
          <template id="sports-card">
            <div class="min-w-[220px] bg-white border rounded-lg shadow-sm hover:shadow-md flex-shrink-0 snap-start transition-transform duration-200 hover:scale-[1.02] overflow-hidden">
              <img src="a.jpg" class="h-40 w-full object-cover" alt="Sports">
              <div class="p-3"><p class="text-sm font-medium text-gray-800">Team India prepares for World Cup 2025.</p></div>
            </div>
          </template>
        </div>
      </div>
  
    </div>
  </section>
  

<!-- ======= JS (1 tiny helper) ======= -->
<script>
  // How many copies of each template to generate
  const CARD_COUNT = 6;

  ['business','tech','entertainment','politics','popular','sports'].forEach(section=>{
    const list   = document.getElementById(`scroll-${section}`);
    const tpl    = document.getElementById(`${section}-card`);
    for(let i=0;i<CARD_COUNT;i++) list.appendChild(tpl.content.cloneNode(true));
  });

  function scrollCards(section,dir){
    document.getElementById(`scroll-${section}`)
            .scrollBy({left: dir*250, behavior:'smooth'});
  }
</script>
<!-- Feather Icons CDN -->
<script src="https://unpkg.com/feather-icons"></script>

<!-- Centered Main Section -->
<section class="mb-12 py-10 max-w-7xl mx-auto px-6">
  <div class="max-w-7xl mx-auto px-4 flex flex-col lg:flex-row gap-8">

    <!-- 🔥 Trending News - Now on Left -->
    <aside class="lg:w-2/3 w-full">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">🔥 Trending News</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- News Card 1 -->
        <div class="bg-white rounded shadow p-3 flex flex-col">
          <img src="a.jpg" alt="News" class="h-32 w-full object-cover rounded mb-2" />
          <h3 class="font-semibold text-gray-800 text-sm">Indian markets close at record high</h3>
          <p class="text-xs text-gray-500">Sensex rises 700+ points as IT, finance lead rally.</p>
        </div>
        <!-- News Card 2 -->
        <div class="bg-white rounded shadow p-3 flex flex-col">
          <img src="a.jpg" alt="News" class="h-32 w-full object-cover rounded mb-2" />
          <h3 class="font-semibold text-gray-800 text-sm">AI startup raises $100M</h3>
          <p class="text-xs text-gray-500">India’s next unicorn leads AI for healthcare innovation.</p>
        </div>
        <!-- News Card 3 -->
        <div class="bg-white rounded shadow p-3 flex flex-col">
          <img src="a.jpg" alt="News" class="h-32 w-full object-cover rounded mb-2" />
          <h3 class="font-semibold text-gray-800 text-sm">Blockbuster crosses ₹500 Cr</h3>
          <p class="text-xs text-gray-500">Film becomes third highest-grossing of all time.</p>
        </div>
        <!-- News Card 4 -->
        <div class="bg-white rounded shadow p-3 flex flex-col">
          <img src="a.jpg" alt="News" class="h-32 w-full object-cover rounded mb-2" />
          <h3 class="font-semibold text-gray-800 text-sm">T20 World Cup Squad Announced</h3>
          <p class="text-xs text-gray-500">BCCI reveals final team ahead of June matches.</p>
        </div>
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