CREATE  TABLE `mydb`.`timeline` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(255) NULL ,
  `date` TIMESTAMP NULL ,
  `type` VARCHAR(45) NULL ,
  `user_id` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`id` ASC) ,
  CONSTRAINT `user_id`
    FOREIGN KEY (`id` )
    REFERENCES `mydb`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);