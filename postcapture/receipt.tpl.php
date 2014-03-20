<!doctype html>
<html lang="da">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta charset="utf-8">
    <title><?=$sjak['name'] ?> - Registrering</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h2>Tillykke <?= $sjak['name'] ?></h2><br>
    <?php
    if (empty($foundobject)) {
        echo "<p>I har ikke registreret nogle ting fra {$scout['name']}.</p>";
    } else {
        echo "<p>I har registreret følgende ting</p><br>";
        echo "<ul>";
        foreach ($foundobject AS $object) {
            echo "<li>\"{$object['name']}\"</li>";
        }
        echo "</ul>";
    }
    ?>
    <br><br>
    <p><a href="index.php">Videre</a></p>
  </body>
</html>
