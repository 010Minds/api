CREATE  TABLE `mydb`.`notification` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `type` INT(1) NOT NULL ,
  `description` VARCHAR(255) NULL ,
  `date` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
