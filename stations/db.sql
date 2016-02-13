DROP DATABASE IF EXISTS elite_dangerous;
CREATE DATABASE elite_dangerous;
USE elite_dangerous;

CREATE TABLE listings (
	id 				INT(32),
	station_id 		INT(32),
	commodity_id 	INT(32),
	supply 			INT(32),
	sell_price 		INT(32),
	demand 			INT(32),
	collected_at 	INT(32),
	update_count 	INT(32)
);

CREATE TABLE commodities (
	id 				INT(32),
	name 			VARCHAR(32),
	data 			BLOB
);

CREATE TABLE systems (
	id 				INT(32),
	name 			VARCHAR(32),
	x				INT(32),
	y				INT(32),
	z				INT(32),
	data 			BLOB
);

CREATE TABLE stations (
	id 				INT(32),
	name 			VARCHAR(32),
	system_id		INT(32),
	data 			BLOB
);
