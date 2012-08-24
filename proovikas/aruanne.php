<html>
<head>
<title>Proovitöö</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<form method="post">
<input type="text" name="start" size="15"/> Alguse kuupäev vormingus yyyy-mm-dd (nt. 2012-12-24)<br>
<input type="text" name="end" size="15"/> Lõppkuupäev vormingus yyyy-mm-dd (nt. 2012-12-24)<br>
<input type="submit" value="Start"/ name="Start"><br>
</form>
<a HREF='/proovikas/index.php'>Avalehele</a><br>
<?php
if (isset($_POST["start"])) {
	//$start=$_POST["start"]);
	if(isset($_REQUEST["end"])){
		//$end=$_POST["end"]);
		$myFile = "db_connect.txt";
		$fh = fopen($myFile, 'r');
		$theData = fread($fh,filesize($myFile));
		$conn = pg_connect($theData);
		if (!$conn) {
			die('Could not connect: ' . pgsql_error());
			echo "jama";
		}
		$query="select name, sum(trades) from (SELECT * FROM Equity where date >='".$_POST["start"]."' AND date<='".$_POST["end"]."')as foo group by name order by sum desc LIMIT 10";
		echo $query;
		$result = pg_query($conn,$query);
		if (!$result) {
			$errormessage = pg_last_error();
			echo "<br>Päring vigane : ".$errormessage."<br>";
		}  else {
			$rows = pg_num_rows($result);
			echo "<table border='1' align=center><td>Name</td><td>Trades</td>";			
			while ($row = pg_fetch_row($result)) {
				echo "<tr><td>$row[0]</td><td>$row[1]</td>";
			//echo "<br />\n";
		}
		echo "</table>";
		}		
	}
}	
?>
</html>