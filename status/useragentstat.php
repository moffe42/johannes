<?php
// Include config
require_once('../config/config.php');
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
	$stmt = $dbh->prepare('SELECT day(time) as day, hour(time) AS hour, count(*) AS `count` FROM objectlog WHERE used = 1 GROUP BY day(time), hour(`time`);');
	$stmt->execute();
	$rows = $stmt->fetchAll();
} catch (Exception $e) {
	var_dump($e);
}
echo "<pre>";
var_dump($rows);
