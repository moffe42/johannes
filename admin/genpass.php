<?php
require_once('../config/config.php');

try {
    // Initialize DB
    $dbh = new PDO($config['db.dsn'], $config['db.user'], $config['db.pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    exit("Connection to database failed. Please contact admin");
}

$stmt = $dbh->prepare("SELECT * FROM `sjak`");
$stmt->execute();

$sjaks = $stmt->fetchAll();

$insertStmt = $dbh->prepare("UPDATE `sjak` SET password = ? WHERE id = ?");


foreach($sjaks AS $sjak) {
	$res[$sjak['id']]['id'] = $sjak['id'];
	$res[$sjak['id']]['name'] = $sjak['name'];
	$res[$sjak['id']]['pass'] = substr("apo" . mt_rand(), 0, 8);
	$res[$sjak['id']]['sha1'] = sha1($res[$sjak['id']]['pass']);

	if ($_GET['gogo'] === 'yes' ) {
	    $insertStmt->execute(array($res[$sjak['id']]['sha1'], $sjak['id']));
	}
}
?>

<html>
	<head>
		<title>Apokalypseløbet 2013 - Sjak password generator</title>
		<meta charset="utf-8">
		<style type="text/css">
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
	    <table class="content">
	        <thead>
	            <tr>
	                <th>ID</th>
	                <th>Navn</th>
	                <th>Password</th>
	            </tr>
	        </thead>
	<?php
foreach ($res AS $sjak) {
    echo "<tr>";
    echo "<td>{$sjak['id']}</td>";
    echo "<td>{$sjak['name']}</td>";
    echo "<td>{$sjak['pass']}</td>";
    echo "</tr>";
}
?>
        </table>
	</body>
</html>
