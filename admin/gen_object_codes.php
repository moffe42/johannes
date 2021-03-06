<?php
error_reporting(-1);

require_once('../config/config.php');
require_once('database.php');

if ($_GET['gogo'] !== 'yes' ) {
	die('Add ?gogo=yes to generate codes');
}

$stmt = $dbh->prepare('SELECT * FROM object');
$stmt->execute();
$res = $stmt->fetchAll();

$codeCount = $_GET['cc'];

echo "<pre>";
/**
 * En capture kode er 9 tegn lang
 * De første 3 tegn er object id
 * De sidste 6 er random
 */
foreach ($res as $object) {
	$randomCode = rand(100000, 899999);
	$stmt2 = $dbh->prepare('INSERT INTO objectlog (code, objectid) VALUES (:code, :objectid)');

	for ($i = 0; $i < $codeCount; $i++) {
		$randomCode = $randomCode + rand(1, 10);
		$code = "{$object['id']}{$randomCode}";
		try {
			$stmt2->execute(array('code' => $code, 'objectid' => $object['id']));
		} catch (Exception $e) {
			var_dump($e); exit;
		}
	}
	echo "Added {$codeCount} object codes for {$object['name']} id={$object['id']}" . PHP_EOL;
}

