<?php
session_start();
error_reporting(-1);
// Include
require_once('../config/config.php');
	
if ($_GET['gogo'] !== 'yes' ) {
	die('Add ?gogo=yes to reset');
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
    $res = $dbh->query("update objectlog set used = 0, sjakid = 0, useragent = ''; update capturelog set used = 0, sjakid = 0, useragent = '';");
    echo "<pre>";
    var_dump($res);
}catch (Exception $e) {
    echo $e->getMessage();
}
