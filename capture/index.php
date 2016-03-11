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
} else if (isset($_SESSION['sjakid']) & $ssi == 0) {
    $sjakid = $_SESSION['sjakid'];
}

$sjakShowId = isset($_GET['ssi']) ? $_GET['ssi'] : 0;

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
    if (!empty($sjakname) & $sjakShowId == 0) {
      echo "<h2>{$sjakname}</h2>";
    }
    ?>
    <form action="handle_capture.php" method="post">
      Spejder kode: <input type="number" name="scoutcode" size="9" maxlength="9" />
      Ting kode 1: <input type="number" name="objectcode[]" size="9" maxlength="9" />
      Ting kode 2: <input type="number" name="objectcode[]" size="9" maxlength="9" />
      Ting kode 3: <input type="number" name="objectcode[]" size="9" maxlength="9" />
      Ting kode 4: <input type="number" name="objectcode[]" size="9" maxlength="9" />
      Ting kode 5: <input type="number" name="objectcode[]" size="9" maxlength="9" />
      <?php
      if ($sjakid > 0 & $sjakShowId == 0) {
        echo "<input type='hidden' name='sjakid' value='{$sjakid}'>";
      } elseif ($sjakid > 0) {
        echo "Sjak kode: <input type='number' name='sjakid' value='{$sjakid}'>";
      } else {
        echo "Sjak kode: <input type='number' name='sjakid'>";
      }
      ?>
      <input type="hidden" name="ssi" value="<?=$sjakShowId?>">
      <input type="submit" value="Send Registrering"/>
    </form>
  <?
  $klokken = date('H:i:s');
  echo "Klokken er lige nu {$klokken}";
  ?>
  </body>
</html>
