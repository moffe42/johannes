<?php
error_reporting(-1);

require_once('../config/config.php');
require_once('database.php');

if ($_GET['gogo'] !== 'yes' ) {
	die('Add ?gogo=yes to generate codes');
}

$stmt = $dbh->prepare('SELECT * FROM scout');
$stmt->execute();
$res = $stmt->fetchAll();

$codeCount = $_GET['cc'];

echo "<pre>";
/**
 * En capture kode er 9 tegn lang
 * De første 4 tegn er scout id
 * De sidste 5 er random
 */
foreach ($res as $scout) {
	$randomCode = rand(10000, 89999);
	$stmt2 = $dbh->prepare('INSERT INTO capturelog (code, scoutid) VALUES (:code, :scoutid)');

	for ($i = 0; $i < $codeCount; $i++) {
		$randomCode = $randomCode + rand(1, 10);
		$code = "{$scout['id']}{$randomCode}";
		try {
			$stmt2->execute(array('code' => $code, 'scoutid' => $scout['id']));
		} catch (Exception $e) {
			var_dump($e); exit;
		}
	}
	echo "Added {$codeCount} capture codes for {$scout['name']} id={$scout['id']}" . PHP_EOL;
}
