-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql
-- 生成日時: 2020 年 8 月 02 日 11:46
-- サーバのバージョン： 5.7.30
-- PHP のバージョン: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `sample`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `comics_carts`
--

CREATE TABLE `comics_carts` (
  `id` int(255) NOT NULL COMMENT 'カートID',
  `user_id` int(255) NOT NULL COMMENT 'ユーザID ',
  `product_id` int(255) NOT NULL COMMENT '商品ID',
  `amount` int(100) NOT NULL DEFAULT '0' COMMENT '購入予定商品数',
  `create_date` datetime NOT NULL COMMENT '登録日時',
  `update_date` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comics_carts`
--

INSERT INTO `comics_carts` (`id`, `user_id`, `product_id`, `amount`, `create_date`, `update_date`) VALUES
(270, 5, 30, 1, '2020-07-30 01:20:04', '2020-07-30 01:20:04'),
(271, 0, 30, 1, '2020-08-02 00:22:35', '2020-08-02 00:22:35');

-- --------------------------------------------------------

--
-- テーブルの構造 `comics_form`
--

CREATE TABLE `comics_form` (
  `form_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `tel` varchar(13) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `sex` varchar(5) DEFAULT NULL,
  `help` varchar(1000) DEFAULT NULL,
  `create_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comics_form`
--

INSERT INTO `comics_form` (`form_id`, `name`, `tel`, `mail`, `sex`, `help`, `create_datetime`) VALUES
(4, 'yamadataro', '0993-41-3356', 'asasas@hotmail.co.jp', 'woman', 'あああいいいうううえええおおおかかかきききくくくけけけこここっささささ', '2020-07-26 02:33:46'),
(5, 'sampleuser', '080-5260-3811', 'st-niguchi@hotmail.co.jp', 'man', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2020-07-26 02:34:38'),
(6, 'sampleuser', '080-5260-3811', 'st-niguchi@hotmail.co.jp', 'man', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2020-07-26 02:38:13'),
(7, 'doraemon', '0993-21-4578', 'niguchi@hotmail.co.jp', 'man', 'うまくいかないです', '2020-07-26 02:39:12'),
(8, 'tanakasachiko', '0993-41-3356', 'asasasasas@hotmail.co.jp', 'woman', 'いつもありがとうございます', '2020-07-26 02:40:08'),
(9, 'sampleuser', '0993-41-3356', 'niguchi@hotmail.co.jp', 'woman', 'aaa', '2020-08-02 01:51:07'),
(10, 'tanakasachiko', '0993-41-3356', 'asasas@hotmail.co.jp', 'man', 'asasasasddddddddd', '2020-08-02 10:28:03');

-- --------------------------------------------------------

--
-- テーブルの構造 `comics_information`
--

CREATE TABLE `comics_information` (
  `id` int(255) NOT NULL COMMENT '自動連番',
  `name` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `publisher` int(11) NOT NULL COMMENT '検索用',
  `type` int(11) NOT NULL COMMENT '検索用',
  `price` int(255) NOT NULL,
  `img` varchar(100) NOT NULL DEFAULT '' COMMENT '商品画像ファイル名',
  `status` int(11) DEFAULT '0' COMMENT '0:非公開　1:公開',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comics_information`
--

INSERT INTO `comics_information` (`id`, `name`, `author`, `publisher`, `type`, `price`, `img`, `status`, `create_date`, `update_date`) VALUES
(16, '秀才ポコペン', '青塚不三夫', 2, 2, 420, '4d3a1fe887fe9fcdb54e14e6ecc453dc921ce333.png', 1, '2020-05-21 01:22:53', '2020-05-21 04:30:24'),
(17, 'BE RED 〜赤になれ〜', '田仲モトハル', 3, 3, 460, '38340cb36dba1fbab2866d024ff4397a4566e266.png', 1, '2020-05-21 01:23:53', '2020-05-21 04:30:26'),
(18, '実は僕は', '増太栄二', 4, 4, 430, '75647df74bcfde5727b039942bd85e40365926cb.png', 1, '2020-05-21 01:24:35', '2020-05-21 04:30:23'),
(19, 'yesterday', '夏目景', 1, 4, 600, 'b56f2eeea247bac63e27fdb969a034ea76971852.png', 1, '2020-05-21 01:32:44', '2020-05-21 04:30:21'),
(20, 'はじめての一歩', '山川ジョー', 2, 3, 600, '88b02e1b6e397d2a8f1fc5e0f5e3483cd829e242.png', 1, '2020-05-21 01:34:57', '2020-05-21 04:30:19'),
(21, '覚悟完了　完全版', 'ヤマグチ', 4, 1, 800, '8636d6ca26ea204c684921813caa6b9c4eaccc87.png', 1, '2020-05-21 01:36:31', '2020-05-21 04:30:16'),
(23, 'BO!!BO!!-BO!!-BO!!', '沢井哲也', 1, 2, 500, '0f7bf880de0af8a09990290ce8b5aa9384af4536.png', 1, '2020-05-21 01:40:43', '2020-05-21 04:30:05'),
(24, '山王紀', '河原雅敏', 2, 1, 650, 'aacee3fe74094df45ee61718b2f1bcbc3263dc9a.png', 1, '2020-05-21 01:42:02', '2020-05-21 04:30:08'),
(25, 'タコ娘！', '綾部正弘', 4, 2, 390, '057aa756d8e3c74376aa36a4db2b421544858802.png', 1, '2020-05-21 01:44:56', '2020-05-21 04:30:11'),
(28, '黄金のアッシュ', 'LIKE', 3, 1, 700, 'c1f142a468a00382c70798b048242fb741ea005c.png', 1, '2020-05-21 01:48:05', '2020-05-24 13:52:38'),
(30, 'アンピース', '小田栄一朗', 1, 1, 450, '1f3ca64ebe4fa8b5b6ac89b53a9c4ee306d11324.png', 1, '2020-06-02 05:13:39', '2020-06-02 05:13:39'),
(31, 'ふるここ', '米波羅', 4, 1, 500, '7c943d6172c488e9a81dc23b45f03b66c382eadf.png', 1, '2020-06-02 05:15:33', '2020-06-02 05:15:33'),
(32, 'あっちむいてみいちゃん', 'おのまええりか', 3, 2, 390, '71e42ad84b0e8ed96d2d51406c30cce5103f5b41.png', 1, '2020-06-02 05:22:12', '2020-06-02 05:22:12');

-- --------------------------------------------------------

--
-- テーブルの構造 `comics_orders`
--

CREATE TABLE `comics_orders` (
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comics_orders`
--

INSERT INTO `comics_orders` (`user_id`, `order_id`, `order_datetime`) VALUES
(4, 16, '2020-07-27 01:42:05'),
(5, 17, '2020-07-27 01:42:57'),
(5, 18, '2020-07-27 01:44:33'),
(7, 19, '2020-07-27 02:37:48'),
(4, 20, '2020-07-27 04:43:31'),
(5, 21, '2020-07-28 22:42:58'),
(5, 22, '2020-07-29 02:05:55'),
(5, 23, '2020-07-29 02:06:17'),
(5, 24, '2020-07-29 02:12:23'),
(1, 133, '2020-07-05 03:37:47'),
(1, 134, '2020-07-05 03:38:07'),
(1, 135, '2020-07-11 20:00:39'),
(1, 136, '2020-07-11 20:46:38'),
(4, 138, '2020-07-11 22:53:35'),
(4, 139, '2020-07-13 16:09:03'),
(4, 140, '2020-07-14 00:27:49'),
(4, 141, '2020-07-14 00:31:25'),
(4, 142, '2020-07-14 00:32:03'),
(1, 143, '2020-07-23 22:12:12'),
(1, 144, '2020-07-23 22:13:25'),
(1, 145, '2020-07-23 23:57:13'),
(1, 146, '2020-07-26 21:30:01'),
(1, 147, '2020-07-26 21:30:28'),
(4, 148, '2020-07-28 22:13:14');

-- --------------------------------------------------------

--
-- テーブルの構造 `comics_statements`
--

CREATE TABLE `comics_statements` (
  `statements_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comics_statements`
--

INSERT INTO `comics_statements` (`statements_id`, `order_id`, `item_id`, `item_name`, `price`, `amount`) VALUES
(29, 16, 16, '秀才ポコペン', 420, 1),
(30, 16, 17, 'BE RED 〜赤になれ〜', 460, 1),
(31, 16, 19, 'yesterday', 600, 2),
(32, 17, 16, '秀才ポコペン', 420, 1),
(33, 17, 25, 'タコ娘！', 390, 1),
(34, 18, 20, 'はじめての一歩', 600, 1),
(35, 18, 21, '覚悟完了　完全版', 800, 1),
(36, 19, 17, 'BE RED 〜赤になれ〜', 460, 1),
(37, 19, 28, '黄金のアッシュ', 700, 1),
(38, 20, 19, 'yesterday', 600, 1),
(39, 20, 20, 'はじめての一歩', 600, 1),
(40, 21, 20, 'はじめての一歩', 600, 2),
(41, 21, 16, '秀才ポコペン', 420, 3),
(42, 22, 28, '黄金のアッシュ', 700, 1),
(43, 23, 28, '黄金のアッシュ', 700, 4),
(44, 24, 17, 'BE RED 〜赤になれ〜', 460, 2),
(45, 24, 18, '実は僕は', 430, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `comics_stock`
--

CREATE TABLE `comics_stock` (
  `id` int(11) NOT NULL,
  `stock` int(255) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comics_stock`
--

INSERT INTO `comics_stock` (`id`, `stock`, `create_date`, `update_date`) VALUES
(16, 87, '2020-05-21 01:22:53', '2020-06-06 22:09:32'),
(17, 96, '2020-05-21 01:23:53', '2020-06-06 22:09:32'),
(18, 97, '2020-05-21 01:24:35', '2020-06-06 22:09:32'),
(19, 97, '2020-05-21 01:32:44', '2020-06-06 22:09:32'),
(20, 98, '2020-05-21 01:34:57', '2020-05-31 23:53:45'),
(21, 98, '2020-05-21 01:36:31', '2020-05-31 23:53:45'),
(23, 98, '2020-05-21 01:40:43', '2020-05-31 23:53:45'),
(24, 98, '2020-05-21 01:42:02', '2020-05-31 23:53:45'),
(25, 98, '2020-05-21 01:44:56', '2020-05-31 23:53:45'),
(28, 90, '2020-05-21 01:48:05', '2020-06-02 05:09:33'),
(30, 98, '2020-06-02 05:13:39', '2020-06-02 05:17:04'),
(31, 0, '2020-06-02 05:15:33', '2020-06-02 05:15:33'),
(32, 100, '2020-06-02 05:22:12', '2020-06-02 05:22:12');

-- --------------------------------------------------------

--
-- テーブルの構造 `comics_users`
--

CREATE TABLE `comics_users` (
  `id` int(11) NOT NULL COMMENT 'ユーザID',
  `user_name` varchar(20) NOT NULL COMMENT 'ユーザ名',
  `password` varchar(20) NOT NULL COMMENT 'パスワード',
  `mail` varchar(50) NOT NULL COMMENT 'メールアドレス',
  `sex` int(11) NOT NULL COMMENT '性別 1:男性 2:女性',
  `birthday` varchar(15) NOT NULL COMMENT '生年月日',
  `create_date` datetime NOT NULL COMMENT 'データ作成日',
  `update_date` datetime NOT NULL COMMENT 'データ更新日'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comics_users`
--

INSERT INTO `comics_users` (`id`, `user_name`, `password`, `mail`, `sex`, `birthday`, `create_date`, `update_date`) VALUES
(4, 'yamadataro', 'abcd1234', 'Ya_ma-da.taro@ezweb.ne.jp', 1, '1993/6/23', '2020-05-17 14:38:50', '2020-05-17 14:38:50'),
(5, 'tanakasachiko', 'papapa1111sasa', 'Tanaka.sachiko@gmail.com', 2, '1995/12/20', '2020-05-17 23:33:33', '2020-05-17 23:33:33'),
(6, 'kawanotaichi', 'kawano0623', 'kawano@ezweb.ne.jp', 1, '1998/3/4', '2020-05-20 22:15:07', '2020-05-20 22:15:07'),
(7, 'doraemon', 'dorayaki0147', 'dorayaki_love@gmail.co.jp', 1, '1969/12/31', '2020-05-20 22:29:49', '2020-05-20 22:29:49'),
(8, 'taniguchishono', '3821aaaaaa', 'kamen@ezweb.ne.jp', 1, '1993/6/23', '2020-05-27 05:31:34', '2020-05-27 05:31:34'),
(9, 'admin', 'admin', 'Wa-da.taro@ezweb.ne.jp', 1, '1993/6/23', '2020-06-01 04:32:10', '2020-06-01 04:32:10');

-- --------------------------------------------------------

--
-- テーブルの構造 `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `items`
--

INSERT INTO `items` (`item_id`, `name`, `stock`, `price`, `image`, `status`, `created`, `updated`) VALUES
(32, 'ねこ', 340, 30000, 'ny1owjn3yqs0cow8w4ws.jpg', 1, '2019-08-09 09:12:30', '2020-07-30 00:56:28'),
(33, 'ハリネズミ', 278, 50000, '16scmunsexdwcosw88g0.jpg', 1, '2019-08-09 09:13:33', '2020-07-28 22:13:14'),
(38, 'コーラらしきもの', 1000, 5, '55melwjia1c8g4k00k8g.png', 0, '2020-07-13 16:32:56', '2020-07-23 22:25:18'),
(40, '四角形', 1000, 30, 'l0h9f1b3ipcs0kw80kgw.png', 0, '2020-07-17 01:44:15', '2020-07-23 22:25:43'),
(41, 'ピカチュウ', 44, 45000, '6b09840cs7oc4g8g8w0k.png', 1, '2020-07-17 01:46:10', '2020-07-30 00:57:00'),
(42, '漫画', 500, 600, '4z0abqxq6w84o4kggw88.png', 0, '2020-07-17 01:47:01', '2020-07-23 22:25:28'),
(43, '王冠', 100, 120, '2rf9z0mip2g4sc4008c8.png', 1, '2020-07-17 01:49:01', '2020-07-17 01:49:01'),
(44, '本', 296, 2000, '3y6sbvx91scgo8ocgsss.png', 1, '2020-07-17 01:58:23', '2020-07-30 00:58:22'),
(45, '自転車', 997, 20000, '5psx7yeub8sogkkogoos.png', 1, '2020-07-23 22:24:31', '2020-07-30 00:57:11');

-- --------------------------------------------------------

--
-- テーブルの構造 `order_products`
--

CREATE TABLE `order_products` (
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `order_products`
--

INSERT INTO `order_products` (`user_id`, `order_id`, `order_datetime`) VALUES
(4, 1, '2020-07-30 00:56:28'),
(4, 2, '2020-07-30 00:57:00'),
(4, 3, '2020-07-30 00:57:11'),
(1, 4, '2020-07-30 00:58:22');

-- --------------------------------------------------------

--
-- テーブルの構造 `statements`
--

CREATE TABLE `statements` (
  `statements_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `statements`
--

INSERT INTO `statements` (`statements_id`, `order_id`, `item_id`, `item_name`, `price`, `amount`) VALUES
(27, 1, 32, 'ねこ', 30000, 5),
(28, 2, 41, 'ピカチュウ', 45000, 6),
(29, 3, 45, '自転車', 20000, 3),
(30, 4, 44, '本', 2000, 4);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '2',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`user_id`, `name`, `password`, `type`, `created`, `updated`) VALUES
(1, 'sampleuser', 'password', 2, '2019-08-07 01:17:12', '2019-08-07 01:17:12'),
(4, 'admin', 'admin', 1, '2019-08-07 10:45:11', '2019-08-07 10:45:11');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- テーブルのインデックス `comics_carts`
--
ALTER TABLE `comics_carts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `comics_form`
--
ALTER TABLE `comics_form`
  ADD PRIMARY KEY (`form_id`);

--
-- テーブルのインデックス `comics_information`
--
ALTER TABLE `comics_information`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `comics_orders`
--
ALTER TABLE `comics_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- テーブルのインデックス `comics_statements`
--
ALTER TABLE `comics_statements`
  ADD PRIMARY KEY (`statements_id`);

--
-- テーブルのインデックス `comics_stock`
--
ALTER TABLE `comics_stock`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `comics_users`
--
ALTER TABLE `comics_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- テーブルのインデックス `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- テーブルのインデックス `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`order_id`);

--
-- テーブルのインデックス `statements`
--
ALTER TABLE `statements`
  ADD PRIMARY KEY (`statements_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルのAUTO_INCREMENT `comics_carts`
--
ALTER TABLE `comics_carts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'カートID', AUTO_INCREMENT=272;

--
-- テーブルのAUTO_INCREMENT `comics_form`
--
ALTER TABLE `comics_form`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- テーブルのAUTO_INCREMENT `comics_information`
--
ALTER TABLE `comics_information`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT '自動連番', AUTO_INCREMENT=33;

--
-- テーブルのAUTO_INCREMENT `comics_orders`
--
ALTER TABLE `comics_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- テーブルのAUTO_INCREMENT `comics_statements`
--
ALTER TABLE `comics_statements`
  MODIFY `statements_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- テーブルのAUTO_INCREMENT `comics_stock`
--
ALTER TABLE `comics_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- テーブルのAUTO_INCREMENT `comics_users`
--
ALTER TABLE `comics_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ユーザID', AUTO_INCREMENT=10;

--
-- テーブルのAUTO_INCREMENT `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- テーブルのAUTO_INCREMENT `order_products`
--
ALTER TABLE `order_products`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルのAUTO_INCREMENT `statements`
--
ALTER TABLE `statements`
  MODIFY `statements_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
