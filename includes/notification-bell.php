

<!-- notification-bell.php -->
<div id="notify-bell"
     class="fixed bottom-5 right-5 z-50 bg-white p-3 rounded-full shadow-lg border hover:shadow-xl transition hover:bg-blue-100 cursor-pointer">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 animate-pulse" fill="none"
       viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 17h5l-1.405-1.405C18.21 14.79 18 13.918 18 13V9a6 6 0 10-12 0v4c0 .918-.21 1.79-.595 2.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
  </svg>
</div>



<!-- Script -->
<script>
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

      fetch("includes/save-subscriber.php", {
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
</script>