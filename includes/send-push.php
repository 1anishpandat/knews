
<?php
$title = "New Update!";
$body = "New news or video just dropped. Click to read/watch.";

echo "<script>
  if ('Notification' in window && Notification.permission === 'granted') {
    new Notification('$title', {
      body: '$body',
      icon: 'img/logo.png'
    });
  }
</script>";
?>
