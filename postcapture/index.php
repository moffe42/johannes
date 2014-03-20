<?php
// Include config
require_once('../config/config.php');

// Start session
session_start();

// Initialize variables
$sjakid   = 0;
$sjakname = '';
$hidden   = false;

// Parse sjakid
if (isset($_GET['sid'])) {
    $sjakid = $_GET['sid'];
} else if (isset($_SESSION['sjakid'])) {
    $sjakid = $_SESSION['sjakid'];
}

// Grab info from DB
$dbh = new PDO($config['db.dsn'], $config['db.user'], $config['db.pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
$stmt = $dbh->prepare("SELECT `name` FROM `sjak` WHERE `id` = ?;");
$stmt->execute(array($sjakid));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$sjakname = $row['name'];
?>
<!DOCTYPE html>
<html>
  <head lang="da">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta charset="utf-8">
  <title><?=$sjakname ?> - Registrering</title>
  <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <?php
    if (!empty($sjakname)) {
      echo "<h2>{$sjakname}</h2>";
    }
    ?>
    <form action="handle_capture.php" method="post">
      Ting kode: <input type="number" name="objectcode[]" size="8" maxlength="8" />
      <?php
      if ($sjakid > 0) {
        echo "<input type='hidden' name='sjakid' value='{$sjakid}'>";
      } else {
        echo "Post kode: <input type='number' name='sjakid'>";
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
