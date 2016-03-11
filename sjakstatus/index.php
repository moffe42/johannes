<?php
// Include config
require_once('../config/config.php');
require_once('library.php');

// Start session
session_start();

$sjakid = end(explode("/", trim($_SERVER['REQUEST_URI'], "/")));

if (!ctype_digit($sjakid)) {
	errorView('Sjak id not correct. Please put ID in URL');
}

try {
    // Initialize DB
    $dbh = new PDO($config['db.dsn'], $config['db.user'], $config['db.pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    exit("Connection to database failed. Please contact admin");
}

try {
	// Grab sjak info
	$stmt = $dbh->prepare('SELECT * FROM `sjak` WHERE `id` = :id;');
	$stmt->execute(array('id' => $sjakid));
    $rows = $stmt->fetchAll();
    if (empty($rows)) {
		errorView('Sjak id invalid');
    }
    $sjakdata = $rows[0];

	// Handle login
	if (!(isset($_SESSION['loginok']) && $_SESSION['sjakid'] == $sjakid)) {
		loginView($sjakdata);
	}

    $stmt = $dbh->prepare("SELECT cl.*, s.name, s.count FROM `capturelog` cl JOIN `scout` s ON cl.scoutid = s.id WHERE cl.sjakid = ? ORDER BY cl.time ASC;");
    $stmt->execute(array($sjakid));
    $captures = $stmt->fetchAll();

    $stmt = $dbh->prepare("SELECT ol.*, s.name AS scoutname, o.name AS objectname FROM `objectlog` ol JOIN `scout` s ON ol.scoutid = s.id JOIN `object` o ON ol.objectid = o.id WHERE ol.sjakid = ? ORDER BY ol.time ASC;");
    $stmt->execute(array($sjakid));
    $objects = $stmt->fetchAll();
} catch (Exception $e) {
    exit($e->getMessage());
}

echo<<<EOT
<!DOCTYPE html>
<html>
  <head lang="da">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta charset="utf-8">
    <title>Sjakstatus - {$sjakdata['name']}</title>
    <link rel="stylesheet" href="/apo/capture/style.css">
    <style>
    body {
        font-family: "Droid", sans-serif;
        background-image: url( http://www.apokalypse.dk/wp-content/uploads/2012/06/topographic1.jpg ) !important;
        background-repeat: repeat !important;
        background-position: top left !important;
        color: #FFF;
    }
    table.content{
        background-color: #000;
        margin-left: auto;
        margin-right: auto;
        border: 1px solid #FFF;
        border-collapse: collapse;
        width: 99%;
    }
        table.content td, table .content th {
        border: 1px solid #FFF;
        padding: 5px;
    }
    </style>
  </head>
  <body>
	<h2>Sjakstatus - {$sjakdata['name']}</h2>
EOT;
echo '<br />';
echo '<p>Patruljer</p>';
echo '<table border="1" class="content">';
echo '<thead>';
echo '<tr>';
echo "<th>Tid</th>";
echo "<td>code</td>";
#echo "<td>scoutid</td>";
#echo "<td>sjakid</td>";
#echo "<td>used</td>";
echo "<th>Patrulje</th>";
#echo "<th>Antal</th>";
#echo "<th>User Agent</t>";
echo '</tr>';
echo '</thead>';
foreach ($captures AS $row) {
    echo '<tr>';
    echo "<td>{$row['time']}</td>";
    echo "<td>{$row['code']}</td>";
#    echo "<td>{$row['scoutid']}</td>";
#   echo "<td>{$row['sjakid']}</td>";
#   echo "<td>{$row['used']}</td>";
    echo "<td>{$row['name']}</td>";
#    echo "<td>{$row['count']}</td>";
#    echo "<td>{$row['useragent']}</td>";
    echo '</tr>';
}
echo '</table>';

echo '<br />';
echo '<p>Ting</p>';
echo '<table class="content">';
echo '<thead>';
echo '<tr>';
echo "<th>Tid</th>";
echo "<th>Ting</th>";
echo "<th>Patrulje</th>";
echo '</tr>';
echo '</thead>';
foreach ($objects AS $row) {
    echo '<tr>';
    echo "<td>{$row['time']}</td>";
    echo "<td>{$row['objectname']}</td>";
    echo "<td>{$row['scoutname']}</td>";
    echo '</tr>';
}
echo '</table>';
echo '</body>';
echo '</html>';
