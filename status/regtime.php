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
	$stmt = $dbh->prepare('SELECT day(time) as day, hour(time) AS hour, count(*)
	        AS `count` FROM objectlog WHERE used = 1 AND DATE(time) IN
	        ("2014-03-21","2014-03-22", "2014-03-23") GROUP BY day(time), hour(`time`);');
	$stmt->execute();
	$rows = $stmt->fetchAll();
} catch (Exception $e) {
	var_dump($e);
}

foreach ($rows AS $row) {
	if(!isset($google_JSON)){
		$google_JSON = "{cols: [";
		$google_JSON_cols[]="{id: '0', label: 'Time', type: 'datetime'}";
		$google_JSON_cols[]="{id: '1', label: 'Registrations', type: 'number'}";
		$google_JSON .= implode(",",$google_JSON_cols)."],rows: [";
	}
	$google_JSON_rows[] = "{c:[{v: new Date(2013, 2,{$row['day']},{$row['hour']})}, {v: ".$row['count']."}]}";
}
// you may need to change the above into a function that loops through rows, with $r['id'] etc, referring to the fields you want to inject..
$data = $google_JSON.implode(",",$google_JSON_rows)."]}";
?>
<html>
	<head>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = new google.visualization.DataTable(<?=$data?>);
			var options = {
				title: 'Capture frequenzy',
				pointSize: 4,
				hAxis: {
					format: 'dd/MM HH:mm'
				}
			};

			var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		}
	</script>
	</head>
	<body>
		<h1>Capture frequenzy - Apo 2014</h1>
		<div id="chart_div" style="width: 900px; height: 500px;"></div>
	</body>
</html>
