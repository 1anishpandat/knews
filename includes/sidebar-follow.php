<section class="mb-24 py-10 max-w-7xl mx-auto px-6">
<aside class="lg:w-1/3 w-full">
  <h2 class="text-xl font-semibold text-gray-800 mb-4">📢 Follow Us</h2>
  <ul class="space-y-4 text-gray-700 text-sm">
    <?php
      $socialLinks = [
        ['icon' => 'facebook', 'name' => 'Facebook', 'url' => '#', 'color' => 'hover:text-blue-600'],
        ['icon' => 'twitter', 'name' => 'Twitter', 'url' => '#', 'color' => 'hover:text-sky-400'],
        ['icon' => 'linkedin', 'name' => 'LinkedIn', 'url' => '#', 'color' => 'hover:text-blue-700'],
        ['icon' => 'instagram', 'name' => 'Instagram', 'url' => '#', 'color' => 'hover:text-pink-600'],
        ['icon' => 'youtube', 'name' => 'YouTube', 'url' => '#', 'color' => 'hover:text-red-600'],
      ];

      foreach ($socialLinks as $link) {
        echo '
          <li class="flex items-center gap-2">
            <i data-feather="' . $link['icon'] . '"></i> 
            <a href="' . $link['url'] . '" class="' . $link['color'] . '">' . $link['name'] . '</a>
          </li>
        ';
      }
    ?>
  </ul>
</aside>
</section>