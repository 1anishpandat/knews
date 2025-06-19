<?php
$host = "localhost";
$user = "	u745008374_admin";
$pass = "@Knews123";
$dbname = "u745008374_knews";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
  $email = $conn->real_escape_string($_POST['email']);
  $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
  $stmt->bind_param("s", $email);
  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error";
  }
  $stmt->close();
}
$conn->close();
?>
