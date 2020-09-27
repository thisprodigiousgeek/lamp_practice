CREATE TABLE `orders` (
    `order_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order_details` (
    `order_detail_id` INT(11) NOT NULL,
    `order_id` INT(11) NOT NULL,
    `order_price` INT(11) NOT NULL,
    `order_amount` INT(11) NOT NULL,
    `item_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `orders`
    ADD PRIMARY KEY (`order_id`);

ALTER TABLE `order_details`
    ADD PRIMARY KEY (`order_detail_id`);

ALTER TABLE `orders`
    MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `order_details`
    MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;