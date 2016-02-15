<?php

echo "downloading latest data\n";
exec("curl https://eddb.io/archive/v4/commodities.json > commodities.json");
exec("curl https://eddb.io/archive/v4/stations.json > stations.json");
exec("curl https://eddb.io/archive/v4/systems.json > systems.json");
exec("curl https://eddb.io/archive/v4/listings.csv > listings.csv");

echo "reloading db\n";
exec("mysql -u root -p < db.sql");
$db = new mysqli('localhost', 'root', 'root', 'elite_dangerous');




/*
// http://edcodex.info/?m=tools&entry=53
$db->query("TRUNCATE systems");
$systems = file_get_contents('systems.json');
$systems = json_decode($systems);
foreach($systems as $system)
{
	$query = "INSERT INTO systems (id,name,x,y,z,data) VALUES("
		. $system->id
		. ",'". $system->name
		. "',". $system->x
		. ",". $system->y
		. ",". $system->z
		. ",'". json_encode($system)
		. "');";
	echo "$query<br>";
	$db->query($query);
}
*/

	
	










$stations = file_get_contents('stations.json');
$stations = json_decode($stations);
foreach($stations as $station)
{
	$query = "INSERT INTO stations (id,name,system_id,data) VALUES("
		. $station->id
		. ",'". $station->name
		. "',". $station->system_id
		. ",'". json_encode($station)
		. "');";
	echo "$query<br>";
	$db->query($query);
}
exit;











echo "<pre>";
$commodities = file_get_contents('commodities.json');
$commodities = json_decode($commodities);
foreach($commodities as $commodity)
{
	$query = "INSERT INTO commodities (id,name) VALUES(" . $commodity->id .",'". $commodity->name . "');";
	echo "$query<br>";
	$db->query($query);
}
exit;









$handle = fopen('listings.csv', 'r');
$first=true;
while($row=fgetcsv($handle))
{
	if($first)
	{
		$first = false;
		continue;
	}
	
	$query = "INSERT INTO listings (id,station_id,commodity_id,supply,sell_price,demand,collected_at,update_count) VALUES("
		. $row[0] . ','
		. $row[1] . ','
		. $row[2] . ','
		. $row[3] . ','
		. $row[4] . ','
		. $row[5] . ','
		. $row[6] . ','
		. $row[7] . ');';
	// echo "$query<br>";
	// exit;
	$db->query($query);
}