--購入履歴画面では「注文番号」「user_id」「合計金額」「購入日時」--
$sql = "
    CREATE TABLE order_history(
        order_id_history(11) int NOT NULL AUTO_INCREMENT,
        user_id(11) int NOT NULL,
        total_price(11) int NOT NULL,
        created datetime,
        primarykey(order_id_history)
    )
";
--購入明細画面では「注文番号」「購入明細の個別番号」「item_id」「金額」「購入数」--
$sql = "
    CREATE TABLE order_details(
        order_details_id(11) int NOT NULL AUTO_INCREMENT,
        order_id_history(11) int NOT NULL,
        item_id int(11) NOT NULL,
        price int(11) NOT NULL,
        amount int(11) NOT NULL,
        created datetime,
        primarykey(order_details_id)
    )
";