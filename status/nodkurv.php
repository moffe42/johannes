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
	// Grab 1aid info
	$stmt = $dbh->prepare('SELECT cl.time, sc.name AS scout, sc.count AS scout_count, sj.name AS sjak, sj.count AS sjak_count FROM `1aid` cl JOIN `scout` sc ON cl.scoutid = sc.id JOIN `sjak` sj ON cl.sjakid = sj.id WHERE cl.`sjakid` > 0 ORDER BY cl.`time` DESC');
	$stmt->execute();
    $rows = $stmt->fetchAll();
} catch (PDOException $e) {
	var_dump($e);
}
?>
<html>
	<head>
		<title>Apokalypseløbet 2015 - APOKASTATUS</title>
		<meta charset="utf-8">
		<meta http-equiv="refresh" content="30" >
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
			table#objectcount td:nth-child(2), table#capturecount td:nth-child(2) {
				text-align: center;
			}
			table.container {
				width: 100%;
			}
			table.container td {
				vertical-align: top;
				padding-left: 5px;
				padding-right: 5px;
			}
			table.container>td:nth-child(1) {
				width: 50%;
			}
			table.container>td:nth-child(2), table.container>td:nth-child(3) {
				width: 25%;
			}
			.headline {
				text-align: center;
			}
			.smalltext {
				font-size: 10px;
				color: #555;
			}
		</style>
	</head>
	<body>
		<div class="headline">
			<img src="http://www.apokalypse.dk/wp-content/uploads/2012/03/apokalypse_logo2.png" alt="Apokalypseløbet – Danmarks mest udfordrende spejderløb">
			<h1>STATUS</h1>
		</div>
		<p class="smalltext">Last updated: <?=date('c')?></p>
		<table class="container">
			<tr>
				<td>
					<div class="headline">
						<h3>Registreringer</h3>
					</div>
					<table class="content" id="log">
						<thead>
							<tr>
								<th>Tid</th>
								<th>Patrulje</th>
								<th>Sjak</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($rows AS $row):
							?>
							<tr>
								<td><?=$row['time'] ?></td>
								<td><?=$row['scout'] ?> (<?=$row['scout_count']?>)</td>
								<td><?=$row['sjak'] ?> (<?=$row['sjak_count']?>)</td>
							</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				</td>
				<td>
					<div class="headline">
						<h3>Fanget</h3>
					</div>
					<table class="content" id="capturecount">
						<thead>
							<tr>
								<th>Sjak</th>
								<th>Antal fangne patruljer</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($sjakrows AS $row):
							?>
							<tr>
								<td><?=$row['name'] ?></td>
								<td><?=$row['count'] ?></td>
							</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
					<br />
					<table class="content" id="capturecount">
						<thead>
							<tr>
								<th>Patrulje</th>
								<th>Antal gange fanget</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($scoutrows AS $row):
							?>
							<tr>
								<td><?=substr($row['name'], 0, 25)?></td>
								<td><?=$row['count'] ?></td>
							</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
					<br />
					<table class="content" id="capturecount">
						<thead>
							<tr>
								<th>Post</th>
								<th>Antal fangene patruljer</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($postrows AS $row):
							?>
							<tr>
								<td><?=substr($row['name'], 0, 25)?></td>
								<td><?=$row['count'] ?></td>
							</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				</td>
				<td>
					<div class="headline">
						<h3>Ting</h3>
					</div>
					<table class="content" id="objectcount">
						<thead>
							<tr>
								<th>Sjak</th>
								<th>Antal fundne ting</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($objectrows AS $row):
							?>
							<tr>
								<td><?=$row['name'] ?></td>
								<td><?=$row['count'] ?></td>
							</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
					<br />
					<table class="content" id="objectcount">
						<thead>
							<tr>
								<th>Patrulje</th>
								<th>Antal tagede ting</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($objectcapturerows AS $row):
							?>
							<tr>
								<td><?=substr($row['name'], 0, 25)?></td>
								<td><?=$row['count'] ?></td>
							</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
					<br />
					<table class="content" id="objectcount">
						<thead>
							<tr>
								<th>Post</th>
								<th>Registrerede ting</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($postobjectrows AS $row):
							?>
							<tr>
								<td><?=$row['name'] ?></td>
								<td><?=$row['count'] ?></td>
							</tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
