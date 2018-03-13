<?php
error_reporting(-1);

require_once('../config/config.php');
require_once('validate.php');

try {
    // Initialize DB
    $dbh = new PDO($config['db.dsn'], $config['db.user'], $config['db.pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    exit("Connection to database failed. Please contact admin");
}

$stmt = $dbh->prepare('SELECT * FROM scout');
$stmt->execute();
$res = $stmt->fetchAll();

echo "<pre>";
/**
 * En capture kode er 9 tegn lang
 * De første 4 tegn er scout id
 * De sidste 5 er random
 */
foreach ($res as $scout) {
	var_dump($scout);
	$randomCode = rand(10000, 89999);
	$stmt2 = $dbh->prepare('INSERT INTO capturelog (code, scoutid) VALUES (:code, :scoutid)');

	for ($i = 0; $i < 100; $i++) {
		$randomCode = $randomCode + rand(1, 10);
		$code = "{$scout['id']}{$randomCode}";
		try {
			$stmt2->execute(array('code' => $code, 'scoutid' => $scout['id']));
		} catch (Exception $e) {
			var_dump($e); exit;
		}
		echo "Insert\n";
	}
}
