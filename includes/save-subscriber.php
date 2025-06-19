
<?php
// DB config
$conn = new mysqli("localhost", "YOUR_DB_USERNAME", "YOUR_DB_PASSWORD", "YOUR_DB_NAME");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

if (!empty($email)) {
    $stmt = $conn->prepare("INSERT INTO notifications_subscribers (email, phone) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    echo "Saved!";
    $stmt->close();
} else {
    echo "Email is required.";
}

$conn->close();
?>

document.getElementById('notifyForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;

  fetch('save-subscriber.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}`
  })
  .then(res => res.text())
  .then(msg => alert(msg));
});
