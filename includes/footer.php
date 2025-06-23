<!-- footer.php -->

<!-- Footer -->
<footer class="bg-gray-100 py-12 border-t mt-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

      <!-- Logo & Description -->
      <div>
        <img src="img/logo.png" alt="Logo" class="h-12 mb-4">
        <p class="text-sm text-gray-600 leading-relaxed">
          &copy; <?= date('Y') ?> @@@@@@@@ All Rights Reserved.<br>
          Designed by <span class="font-semibold text-gray-800 hover:text-blue-600 transition">@@@@@@@@@@</span>
        </p>

        <!-- Social Media Icons -->
        <div class="flex gap-3 mt-4">
          <a href="#" class="text-gray-500 hover:text-blue-600 transition text-lg" title="Facebook">📘</a>
          <a href="#" class="text-gray-500 hover:text-sky-400 transition text-lg" title="Twitter">🐦</a>
          <a href="#" class="text-gray-500 hover:text-pink-500 transition text-lg" title="Instagram">📸</a>
          <a href="#" class="text-gray-500 hover:text-red-600 transition text-lg" title="YouTube">▶️</a>
        </div>
      </div>

      <!-- Categories -->
      <div>
        <h3 class="text-xl font-bold text-gray-800 mb-4">📰 Categories</h3>
        <div class="flex flex-wrap gap-2 text-sm text-gray-700">
          <?php
          $categories = ["State", "National", "International", "Health", "Employment", "Future", "Educational", "Entertainment", "Sports"];
          foreach ($categories as $cat) {
            echo "<span class='bg-white px-3 py-1 rounded-full shadow hover:bg-blue-50 transition'>{$cat}</span>";
          }
          ?>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="text-xl font-bold text-gray-800 mb-4">🔗 Quick Links</h3>
        <ul class="space-y-2 text-sm text-gray-700">
          <?php
          $links = [
            "📄 About" => "#",
            "📢 Advertise" => "#",
            "🔐 Privacy & Policy" => "#",
            "📃 Terms & Conditions" => "#",
            "📬 Contact" => "#"
          ];
          foreach ($links as $text => $url) {
            echo "<li><a href='{$url}' class='hover:underline hover:text-blue-600 transition'>{$text}</a></li>";
          }
          ?>
        </ul>
      </div>

    </div>
  </div>
</footer>
