<!DOCTYPE html>
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>注文履歴</title>
</head>
<body>
    <?php 
    include VIEW_PATH . 'templates/header_logined.php'; 
    ?>
    <h1>購入履歴</h1>

    <?php if(count($order) > 0){ ?>
        <table class="table table-bordered">
            <tr>
                <th>注文番号</th>
                <th>購入日時</th>
                <th>合計金額</th>
                <th>詳細確認</th>
            </tr>
            <?php foreach($order as $order){ ?>
            <tr>
                <td><?php print($order['order_id']); ?></td>
                <td><?php print($order['order_datetime']); ?></td>
                <td><?php print($order['SUM((detail.price) * (detail.amount))']); ?>円</td>
                <td>
                    <form method="post" action="./detail.php">
                        <input type="hidden" name="order_id" value="<?php print($order['order_id']); ?>">
                        <input type="submit" value="詳細">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } ?>

</body>