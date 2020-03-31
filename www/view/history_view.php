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

    <?php if (count($purchase_data) > 0) { ?>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>注文番号</th>
                    <th>購入日時</th>
                    <th>合計金額</th>
                    <th>購入明細</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($purchase_data) as $data) { ?>
                    <tr class="<?php print htmlspecialchars(is_open($item) ? '' : 'close_item'); ?>">
                        <td><?php print htmlspecialchars($data['buy_id']); ?></td>

                        <td><?php print htmlspecialchars($data['update']); ?></td>

                        <td><?php print htmlspecialchars($data['total_price']); ?>円</td>

                        <td>
                            <form method="post" action="detail.php">
                                <input type="submit" value="購入明細を見る" class="btn btn-secondary">
                                <input type="hidden" name="buy_id" value="<?php print htmlspecialchars($data['buy_id']); ?>">
                                <input type="hidden" name="total" value="<?php print htmlspecialchars($data['total_price']); ?>">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>購入履歴はありません。</p>
    <?php } ?>
</body>

</html>