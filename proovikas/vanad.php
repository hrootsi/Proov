<html>
<head>
<title>Proovitöö</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<?php
$myFile = "db_connect.txt";
$fh = fopen($myFile, 'r');
$theData = fread($fh,filesize($myFile));
$conn = pg_connect($theData);
if (!$conn) {
	die('Could not connect: ' . pgsql_error());
	echo "jama";
	}


if ($_GET['ID']==0){	
$result=pg_query($conn,"SELECT date FROM Equity GROUP BY date ORDER BY date");
if (!$result) {
	$errormessage = pg_last_error();
	echo "<br>Päring vigane : ".$errormessage."<br>";
	}  else {
	$rows = pg_num_rows($result);
	while ($row = pg_fetch_row($result)) {
		echo "<a HREF='/proovikas/vanad.php?ID=$row[0]'>$row[0]</a>";
		echo "<br />\n";
		}
	}
	echo "<a HREF='/proovikas/index.php'>Avalehele</a>";
}	
if ($_GET['ID']!=0){
	$query="SELECT * FROM Equity WHERE date='".$_GET['ID']."' ORDER BY date";
	$result = pg_query($conn,$query);		
	if (!$result) {
		$errormessage = pg_last_error();
		echo "<br>Päring vigane : ".$errormessage."<br>";
	}  else {
		$rows = pg_num_rows($result);
		echo "<table border='1' align=center><td>ID</td><td>Ticker</td><td>Name</td><td>ISIN</td><td>Currency</td><td>MarketPlace</td><td>Nimekiri</td>
		<td>Average Price</td><td>Open price</td><td>High Price</td><td>Low Price</td><td>Last Close</td><td>Last Price</td>
		<td>Price change</td><td>Best Bid</td><td>Best ask</td><td>Trades</td><td>Volume</td>
		<td>Turnover</td><td>Date</td>";
			
		while ($row = pg_fetch_row($result)) {
			echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td>
			<td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td><td>$row[13]</td>
			<td>$row[14]</td><td>$row[15]</td><td>$row[16]</td><td>$row[17]</td><td>$row[18]</td><td>$row[19]</td>";
			//echo "<br />\n";
		}
	echo "</table>";
	} // Query
}	
?>
</html>