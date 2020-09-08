CREATE TABLE `history` (
 `history_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `created` datetime NOT NULL,
 PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `detail` (
 `detaail_id` int(11) NOT NULL AUTO_INCREMENT,
 `item_id` int(11) NOT NULL,
 `cart_id` int(11) NOT NULL,
 `history_id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 PRIMARY KEY (`detaail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8