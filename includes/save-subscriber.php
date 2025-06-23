

<!-- // DB config
// $conn = new mysqli("localhost", "u745008374_admin", "@Knews123", "u745008374_knews");
// $conn = new mysqli("localhost", "root", "", "knews"); // <-- for local XAMPP

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $email = $_POST['email'] ?? '';
// $phone = $_POST['phone'] ?? '';

// if (!empty($email)) {
//     $stmt = $conn->prepare("INSERT INTO notifications_subscribers (email, phone) VALUES (?, ?)");
//     $stmt->bind_param("ss", $email, $phone);
//     $stmt->execute();
//     echo "Saved!";
//     $stmt->close();
// } else {
//     echo "Email is required.";
// }

// $conn->close(); -->

<?php
// Remote server DB config
$host = "localhost";
$user = "u745008374_admin";
$pass = "@Knews123";
$dbname = "u745008374_knews";

// Connect
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

if (!empty($email)) {
    $stmt = $conn->prepare("INSERT INTO subscribers (email, phone) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $phone);

    if ($stmt->execute()) {
        echo "✅ Email saved!";
    } else {
        echo "❌ Failed to save email.";
    }

    $stmt->close();
} else {
    echo "⚠️ Email is required.";
}

$conn->close();
?>


<!-- $host = "localhost";
$user = "u745008374_admin";
$pass = "@Knews123";
$dbname = "u745008374_knews";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
  $email = $conn->real_escape_string($_POST['email']);
  $stmt = $conn->prepare("INSERT IGNORE INTO subscribers (email) VALUES (?)");
  $stmt->bind_param("s", $email);
  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error";
  }
  $stmt->close();
}
$conn->close(); -->