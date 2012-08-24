
<html>
<head>
<title>Proovitöö</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<table border="1" height=900 align=center width=800>
<tr >
<td colspan=2 height=50><h1>Proovitöö</h1></td>
</tr>
<tr>
<td width=250 align=left valign=top>
<a HREF="/proovikas/index.php">Lae andmed</a><br>
<a HREF="/proovikas/vanad.php?ID=0">Andmebaasis olevad päevad</a></br>
<a HREF="/proovikas/aruanne.php">Aruanne</a></br></td>
<td >
<?php
date_default_timezone_set('UTC');
$today = date("d.m.y");
$today_db_start=date("Y-m-d"); 
$myFile = "db_connect.txt";
$fh = fopen($myFile, 'r');
$theData = fread($fh,filesize($myFile));

$conn = pg_connect($theData);	
	
	if (!$conn) {
		die('Could not connect: ' . pgsql_error());
		echo "jama";
		} 	


	
	for($i=0;$i < 10;$i++){
	$row = 0;
	$newdate = strtotime ( '-'.$i.' day' , strtotime ( $today ) ) ;
	$newdate_db = strtotime ( '-'.$i.' day' , strtotime ( $today_db_start ) ) ;
	$today=date("d.m.y", $newdate);
	$today_db=date("Y-m-d", $newdate_db);
	if ($today_db==$today_db_start)
		{
			$result1=pg_query($conn,"DELETE FROM Equity Where date='".$today_db."';");
			echo "Tänased andmed lisatud.<br>";
		}
	$result1=pg_query($conn,"SELECT * FROM Equity Where date='".$today_db."';");
	$rows=pg_num_rows($result1);
	if ($rows==0) // ühtegi rida selle kuupäevaga veel tabelis pole
	{
	if (($handle = fopen("http://www.nasdaqomxbaltic.com/market/?pg=mainlist&market=&date=".$today."&downloadcsv=1&csv_style=baltic", "r")) !== FALSE) {
			while (($data = fgetcsv($handle,1000,"	"," ")) !== FALSE) {
				$num = count($data);
				$myArray=array();					
				if ($row > 0){
					for ($c=0; $c < $num; $c++) 
					{							
						$data[$c]=utf8_encode($data[$c]);
						$myArray[$c]=trim(strip_tags($data[$c]));						
					}
					$myArray[]=$today_db;
					$sql="INSERT INTO Equity (ticker, name, isin, currency, marketplace, nimekiri, avg_price,
						open_price, high_price, low_price, last_close, last_price, price_change, best_bid, best_ask, 
						trades, volume, turnover, date) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19)";
					$result=pg_query_params($conn,$sql,$myArray);
					
					if (!$result) {						
						$errormessage = pg_last_error();
						echo "<br>Päring vigane (insert): ".$errormessage."<br>";
					} else {
						
						}
				}				
				$row++;
			}
			echo $today." kuupäeva andmed lisatud tabelisse!<br>";
		fclose($handle);
	}
	} else{ // sellise kuupäeva andmed on tabelis olemas
		
	}
	}

?>

</td>
</tr>
</table>
</html>
