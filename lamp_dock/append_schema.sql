CREATE TABLE 'result'(
    'result_id' int(11) NOT NULL,
    'user_id' int(11) NOT NULL,
    'created' datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE 'detail'(
    'detail_id' int(11) NOT NULL,
    'user_id' int(11) NOT NULL,
    'item_id' int(11) NOT NULL,
    'cart_id' int(11) NOT NULL,
    'created' datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `result`
    ADD PRIMARY KEY (`result_id`);

ALTER TABLE `detail`
    ADD PRIMARY KEY (`detail_id`);
    
ALTER TABLE 'result'
    MODIFY 'result_id' int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE 'detail'
    MODIFY 'detail_id' int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;