ALTER TABLE `bjjheroesapi`.`fighters` 
ADD COLUMN `full_page` text NOT NULL AFTER `extra_info`, 
ADD COLUMN `url` varchar(250) NOT NULL AFTER `full_page`;

CREATE TABLE `bjjheroesapi`.`website_index` (
	`website_index` text NOT NULL,
	`modified` datetime NOT NULL
);

ALTER TABLE `bjjheroesapi`.`fighters` ADD COLUMN `dataSignature` varchar(50) NOT NULL AFTER `url`;