<?php
// Include config
require_once('../config/config.php');

// Start session
session_start();

// Parse postid
if (isset($_GET['postid'])) {
    $postid = $_GET['postid'];
} else if (isset($_SESSION['postid'])) {
    $postid = $_SESSION['postid'];
}
?>
<!DOCTYPE html>
<html>
  <head lang="da">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta charset="utf-8">
  <title>Post <?=$postid ?> - Nødkuvert Registrering</title>
  <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <?php
    if (!empty($postid)) {
      echo "<h2>Post {$postid}</h2>";
    }
    ?>
    <form action="handle_capture.php" method="post">
      Nødkuvert kode: <input type="number" name="1aidcode" size="8" maxlength="8" />
      <?php
      if ($postid > 0) {
        echo "<input type='hidden' name='postid' value='{$postid}'>";
      } else {
        echo "Post nummer: <input type='text' name='postid'>";
      }
      ?>
      <input type="submit" value="Send Registrering"/>
    </form>
  <?
  $klokken = date('H:i:s');
  echo "Klokken er lige nu {$klokken}";
  ?>
  </body>
</html>
