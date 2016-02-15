<?php





$db = new mysqli('localhost', 'root', 'root', 'elite_dangerous');





// http://ed-td.space/en/11/Station/idSys/2725/nSys/Baal/idSta/16758/nSta/Oterma+Station
$targetSystemName = 'Baal';
$targetSystem = null;
$high_supply = "'Food Cartridges','Building Fabricators','Geological Equipment','Marine Equipment','Power Generators','Skimmer Components','Thermal Cooling Units','Basic Medicines','Scrap'";
$high_demand = "'Painite','Bootleg Liquor','Meta-Alloys','Ceramic Composites'";




$high_supply_commodity_ids = "SELECT id from commodities where name in ($high_supply)";
// $high_supply_commodity_ids = $db->query($high_supply_commodity_ids);
// $tmp = '(';
// foreach($high_supply_commodity_ids as $high_supply_commodity_id) $tmp .= $high_supply_commodity_id['id'] . "','";
// $tmp .= "')";


$high_demand_commodity_ids = "SELECT id from commodities where name in ($high_demand)";
// $high_demand_commodity_ids = $db->query($high_demand_commodity_ids);
// $tmp = '(';
// foreach($high_demand_commodity_ids as $high_demand_commodity_id) $tmp .= $high_demand_commodity_id['id'] . "','";
// $tmp .= "')";


$query = "select
	stations.name as 'station_name',
	commodities.name as 'commodity_name', 
	supply,
	demand
from listings
	join stations on stations.id = station_id
	join commodities on commodities.id = commodity_id
where
	commodities.name in ($high_demand)
	and sell_price > 0 
limit 50";

$query2 = "select
	stations.name as 'station_name',
	commodities.name as 'commodity_name', 
	supply,
	demand
from listings
	join stations on stations.id = station_id
	join commodities on commodities.id = commodity_id
where
	commodities.name in ($high_supply)
	and demand > 0 
limit 50";

echo "<pre>";
$results = $db->query($query);
if(!$results) echo $db->error;
foreach($results as $result)
{
	print_r($result);
}
exit;


echo "<pre>";
$station_ids = "
	SELECT
		stations.name,
		buys.name,
		sells.name,
		buys.demand,
		sells.supply
	from stations
	join
		listings as buys on (
				buys.station_id = stations.id
			and buys.commodity_id in (select id from commodities where name in ($high_supply))
			)
		join listings as sells on (
				sells.station_id = stations.id
			and sells.commodity_id in (select id from commodities where name in ($high_demand))
			)
	where buys.demand > 0
	and sells.supply > 0
	;";
			
			
$results = $db->query($station_ids);
if(!$results) echo $db->error;
foreach($results as $result)
{
	var_dump($result);
}
exit;



echo "<br>stations:" . sizeof($stations);
echo "<br>systems:" . sizeof($systems);

// find target system and store it
foreach($systems as $system)
{
	// var_dump($system); exit;
	if($system->name == $targetSystemName) $targetSystem = $system;
}


echo '<pre>';
// loop through all the stations to find ones selling desired commodity
$stationsThatSell = array();
foreach($stations as $station)
{
	$station->sellingThings = array();

	

	foreach($station->export_commodities as $commodity)
	{
		
		var_dump($commodity, "is it in?", $highDemand);
		if(in_array($commodity, $highDemand))
		{
			$station->sellingThings[] = $commodity;
		}
	}
	
	if(sizeof($station->sellingThings))
	{
		$stationsThatSell[$station->id] = $station;
	}
}

echo sizeof($stationsThatSell);
print_r($stationsThatSell);

echo "finished looping stations"; exit;

foreach($systems as $system)
{
	echo $system->name;
	echo distance($system->x, $system->y, $system->z, $targetSystem->x, $targetSystem->y, $targetSystem->z);
	echo "<br>";
	exit;
}

// var_dump($targetSystem);
exit;

foreach($stations as $station)
{
	// if($station->faction == 'The White Templars')
	// if($station->name == 'Oterma Station')
	{
		unset($station->selling_modules);
		echo date('c', $station->updated_at);
		echo " ";
		echo $station->name;
		echo "<br>";
		print_r($station);
	}
	exit;
}

function distance($x1, $y1, $z1, $x2, $y2, $z2)
{
	$xd = $x1-$x2;
	$yd = $y1-$y2;
	$zd = $z1-$z2;
	
	$d = sqrt(($xd*$xd) + ($yd+$yd) + ($zd*$zd));
	
	return $d;
}
