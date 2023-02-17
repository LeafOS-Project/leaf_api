CREATE DATABASE IF NOT EXISTS leaf_ota;
GRANT ALL ON leaf_ota.* TO 'leaf'@'localhost' IDENTIFIED BY 'leaf';
CREATE TABLE IF NOT EXISTS leaf_ota.leaf_ota (
	device varchar(255) NOT NULL,
	datetime BIGINT NOT NULL,
	filename varchar(255) NOT NULL,
	id varchar(255) NOT NULL,
	romtype varchar(255) NOT NULL,
	size BIGINT NOT NULL,
	url varchar(255) NOT NULL,
	version varchar(255) NOT NULL,
	flavor varchar(255) NOT NULL,
	incremental varchar(255) NOT NULL,
	incremental_base varchar(255),
	upgrade varchar(255),
	PRIMARY KEY (incremental)
);
