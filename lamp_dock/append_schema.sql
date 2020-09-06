SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

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
    ADD PRIMARY KEY (`result_id`),
    ADD KEY ('user_id');

ALTER TABLE `detail`
    ADD PRIMARY KEY (`detail_id`),
    ADD KEY ('user_id'),
    ADD KEY ('item_id'),
    ADD KEY ('cart_id');

ALTER TABLE 'result'
    MODIFY 'result_id' int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE 'detail'
    MODIFY 'detail_id' int(11) NOT NULL AUTO_INCREMENT;

    aaaaaaaaaaaaa