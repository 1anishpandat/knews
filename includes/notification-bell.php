<!-- Floating Notification Bell -->
<div id="notify-bell"
     class="fixed bottom-5 right-5 z-50 bg-white p-3 rounded-full shadow-lg border hover:shadow-xl transition hover:bg-blue-100 cursor-pointer"
     onclick="openSubscriptionModal()">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 animate-pulse" fill="none"
       viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 17h5l-1.405-1.405C18.21 14.79 18 13.918 18 13V9a6 6 0 10-12 0v4c0 .918-.21 1.79-.595 2.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
  </svg>
</div>

<!-- Modal for email -->
<div id="subscriptionModal" style="display:none;" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg w-full max-w-sm">
    <h2 class="text-xl font-bold mb-4">Subscribe for Notifications</h2>
    <input type="email" id="subscriberEmail" placeholder="Enter your email" class="w-full p-2 border rounded mb-4" required>
    <button onclick="submitSubscription()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Subscribe</button>
  </div>
</div>
<script>
function openSubscriptionModal() {
  document.getElementById('subscriptionModal').style.display = 'flex';
}

function submitSubscription() {
  const email = document.getElementById('subscriberEmail').value;
  if (!email) return alert('Please enter an email');

  fetch('includes/subscribe.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'email=' + encodeURIComponent(email)
  })
  .then(res => res.text())
  .then(data => {
    alert(data);
    document.getElementById('subscriptionModal').style.display = 'none';
    askNotificationPermission();
  });
}

function askNotificationPermission() {
  if ('Notification' in window) {
    Notification.requestPermission().then(permission => {
      if (permission === 'granted') {
        new Notification('Thanks for subscribing to updates!');
      }
    });
  }
}
</script>
