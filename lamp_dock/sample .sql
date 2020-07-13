-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql
-- 生成日時: 2020 年 7 月 13 日 15:57
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
(32, 'ねこ', 347, 30000, 'ny1owjn3yqs0cow8w4ws.jpg', 1, '2019-08-09 09:12:30', '2020-07-14 00:32:03'),
(33, 'ハリネズミ', 285, 50000, '16scmunsexdwcosw88g0.jpg', 1, '2019-08-09 09:13:33', '2020-07-11 20:46:38'),
(34, 'hacked', 296, 18000, '2a76a9tkn7oksg4w8k00.png', 1, '2020-06-15 14:43:57', '2020-07-11 22:53:35'),
(35, 'hacked', 289, 18000, '66xoihmnvi0w0owscc4w.png', 1, '2020-06-15 14:48:14', '2020-07-11 22:51:44'),
(36, 'めっちゃおいしいカルピス', 299, 5000, '5961620h7oso48kwo8sw.png', 1, '2020-06-15 15:41:19', '2020-07-05 03:27:38'),
(37, 'コーラ', 4, 200, '3i719tw22dq80w4osg84.png', 1, '2020-07-13 16:27:44', '2020-07-13 16:27:44'),
(38, 'コーラらしきもの', 1000, 5, '55melwjia1c8g4k00k8g.png', 1, '2020-07-13 16:32:56', '2020-07-13 16:32:56');

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
(1, 133, '2020-07-05 03:37:47'),
(1, 134, '2020-07-05 03:38:07'),
(1, 135, '2020-07-11 20:00:39'),
(1, 136, '2020-07-11 20:46:38'),
(4, 137, '2020-07-11 20:49:37'),
(4, 138, '2020-07-11 22:53:35'),
(4, 139, '2020-07-13 16:09:03'),
(4, 140, '2020-07-14 00:27:49'),
(4, 141, '2020-07-14 00:31:25'),
(4, 142, '2020-07-14 00:32:03');

-- --------------------------------------------------------

--
-- テーブルの構造 `products_information`
--

CREATE TABLE `products_information` (
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
-- テーブルのデータのダンプ `products_information`
--

INSERT INTO `products_information` (`id`, `name`, `author`, `publisher`, `type`, `price`, `img`, `status`, `create_date`, `update_date`) VALUES
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
-- テーブルの構造 `products_stock`
--

CREATE TABLE `products_stock` (
  `id` int(11) NOT NULL,
  `stock` int(255) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `products_stock`
--

INSERT INTO `products_stock` (`id`, `stock`, `create_date`, `update_date`) VALUES
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
(1, 133, 32, 'ねこ', 30000, 1),
(2, 133, 33, 'ハリネズミ', 50000, 1),
(3, 133, 35, 'hacked', 18000, 1),
(4, 134, 32, 'ねこ', 30000, 1),
(5, 134, 33, 'ハリネズミ', 50000, 1),
(6, 135, 32, 'ねこ', 30000, 3),
(7, 135, 33, 'ハリネズミ', 50000, 1),
(8, 135, 34, 'hacked', 18000, 1),
(9, 135, 35, 'hacked', 18000, 1),
(10, 136, 32, 'ねこ', 30000, 1),
(11, 136, 33, 'ハリネズミ', 50000, 10),
(12, 137, 35, 'hacked', 18000, 10),
(13, 138, 32, 'ねこ', 30000, 1),
(14, 138, 34, 'hacked', 18000, 1),
(15, 138, 34, 'hacked', 18000, 1),
(16, 139, 32, 'ねこ', 30000, 2),
(17, 139, 32, 'ねこ', 30000, 1),
(18, 140, 32, 'ねこ', 30000, 1),
(19, 141, 32, 'ねこ', 30000, 3),
(20, 142, 32, 'ねこ', 30000, 3);

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
-- テーブルのインデックス `products_information`
--
ALTER TABLE `products_information`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `products_stock`
--
ALTER TABLE `products_stock`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- テーブルのAUTO_INCREMENT `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- テーブルのAUTO_INCREMENT `order_products`
--
ALTER TABLE `order_products`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- テーブルのAUTO_INCREMENT `products_information`
--
ALTER TABLE `products_information`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT COMMENT '自動連番', AUTO_INCREMENT=33;

--
-- テーブルのAUTO_INCREMENT `products_stock`
--
ALTER TABLE `products_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- テーブルのAUTO_INCREMENT `statements`
--
ALTER TABLE `statements`
  MODIFY `statements_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
