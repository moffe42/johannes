<!doctype html>
<html lang="da">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta charset="utf-8">
    <title>FEJL</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h2 style="color: red">FEJL</h2><br>
    <ul>
    <?php
    foreach ($error AS $err) {
        echo "<li>\"{$err}\"</li>";
    }
    ?>
    </ul><br><br>
    <p><a href="#" onClick="parent.history.back(); return false;">Tilbage</a></p>
  </body>
</html>
