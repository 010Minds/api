CREATE  TABLE `mydb`.`follows` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `create_date` TIMESTAMP NOT NULL ,
  `follower` INT NOT NULL ,
  `following` INT NOT NULL ,
  PRIMARY KEY (`id`) );