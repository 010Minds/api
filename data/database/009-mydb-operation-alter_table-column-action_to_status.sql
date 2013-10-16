ALTER TABLE `mydb`.`operation` CHANGE COLUMN `action` `status` INT(1) NOT NULL COMMENT '1=pending, 2=accepted, 3=rejected';
