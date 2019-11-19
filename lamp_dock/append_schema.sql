CREATE TABLE `sample`.`histories` 
( 
    `history_id` INT NOT NULL AUTO_INCREMENT , 
    `user_id` INT NOT NULL , 
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `updated` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY (`history_id`)
) ENGINE = InnoDB;

CREATE TABLE `sample`.`history_details` 
( 
    `history_detail_id` INT NOT NULL AUTO_INCREMENT ,
    `item_id` INT NOT NULL , `history_id` INT NOT NULL ,
    `purchased_price` INT NOT NULL , `amount` INT NOT NULL ,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `updated` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    PRIMARY KEY (`history_detail_id`)
) ENGINE = InnoDB;

