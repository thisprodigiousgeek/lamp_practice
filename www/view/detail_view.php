<?php
// クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>

    <title>購入履歴</title>
    <link rel="stylesheet" href="<?php print htmlspecialchars(STYLESHEET_PATH . 'index.css'); ?>">
</head>

<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    <h1>購入履歴</h1>
    <p>注文番号：<?php print htmlspecialchars($buy_id); ?></p>
    <p>合計金額：<?php print htmlspecialchars($total_price); ?></p>



    <?php if (count($purchase_detail) > 0) { ?>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>商品名</th>
                    <th>購入時の価格</th>
                    <th>個数</th>
                    <th>小計</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchase_detail as $dt) { ?>
                    <tr class="<?php print htmlspecialchars(is_open($item) ? '' : 'close_item'); ?>">
                        <td><?php print htmlspecialchars($dt['name']); ?></td>

                        <td><?php print htmlspecialchars($dt['price']); ?></td>

                        <td><?php print htmlspecialchars($dt['amount']); ?></td>

                        <td><?php print htmlspecialchars($subtotal[$dt['item_id']]); ?>円</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>購入履歴はありません。</p>
    <?php } ?>
</body>

</html>