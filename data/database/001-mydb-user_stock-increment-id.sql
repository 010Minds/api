ALTER TABLE `mydb`.`user_stock` CHANGE COLUMN `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT  
, DROP PRIMARY KEY 
, ADD PRIMARY KEY (`id`, `user_id`, `stock_id`) ;
