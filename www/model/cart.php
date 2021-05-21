<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//fetch...日本語訳「持ってくる」
function get_user_carts($db, $user_id){//ユーザーのカート内のデータを持ってくる関数
    $sql = "
      SELECT
        items.item_id,
        items.name,
        items.price,
        items.stock,
        items.status,
        items.image,
        carts.cart_id,
        carts.user_id,
        carts.amount
      FROM
        carts
      JOIN
        items
      ON
        carts.item_id = items.item_id
      WHERE
        carts.user_id = ?
    ";//$user_idはブレースホルダーで何かしらのidが入る
  $params = array($user_id);
  return fetch_all_query($db, $sql, $params);//取得した全部の情報を返す
}

function get_user_cart($db, $user_id, $item_id){//どのアイテムか指定した上で、ユーザーのカート内のデータを持ってくる関数
    $sql = "
        SELECT
          items.item_id,
          items.name,
          items.price,
          items.stock,
          items.status,
          items.image,
          carts.cart_id,
          carts.user_id,
          carts.amount
        FROM
          carts
        JOIN
          items
        ON
          carts.item_id = items.item_id
        WHERE
          carts.user_id = ?
        AND
          items.item_id = ?
      ";////$user_idと$item_idは何かしらのidが入る
  $params = array($user_id, $item_id);
  return fetch_query($db, $sql, $params);//取得した１行の情報を返す
}

function add_cart($db, $user_id, $item_id) {//カートに商品を追加する関数
  $cart = get_user_cart($db, $user_id, $item_id);//get_user_cart関数でカートの中を配列で取得する
  if($cart === false){//取得できひんかったら
    return insert_cart($db, $item_id, $user_id, $amount = 1);//カートテーブルにデータを新規登録する
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);//それ以外ならもうすでに何かしらデータ入ってるはずやから、１を足す
}

function insert_cart($db, $item_id, $user_id, $amount = 1){//カートのデータを新規追加する関数
    $sql = "
      INSERT INTO
        carts(
          item_id,
          user_id,
          amount
        )
      VALUES(?, ?, ?) 
    ";//$は何が入るかお楽しみ、amountは１やで
  $params = array($item_id, $user_id, $amount);
  return execute_query($db, $sql, $params);// execute_query関数を実行して、インサート完了
}

function update_cart_amount($db, $cart_id, $amount){//カートのデータを更新する関数
    $sql = "
      UPDATE
        carts
      SET
        amount = ?
      WHERE
        cart_id = ?
      LIMIT 1
    ";//$は何が入るかお楽しみ。１行だけやで
  $params = array($amount, $cart_id);
  return execute_query($db, $sql, $params);//execute_query関数を実行して、アップデート完了
}

function delete_cart($db, $cart_id){//カート内情報を削除する
    $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";//１行だけやで
  $params = array($cart_id);
  return execute_query($db, $sql, $params);//execute_query関数を実行して、削除完了
}

function purchase_carts($db, $carts){//カート内の購入結果を出す関数
  if(validate_cart_purchase($carts) === false){//カート内のバリデに失敗したら
    return false;//処理やめぴ
  }
  foreach($carts as $cart){//フォーリーチでカート内ぐるぐる
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){//アイテムのストックを更新したけど、カート内の個数のほうが多いせいでfalseになったら
      set_error($cart['name'] . 'の購入に失敗しました。');//セッション箱にエラーメッセージ入れる
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);//エラーなかったらdelete_user_cartsを実行
}

function delete_user_carts($db, $user_id){//カート内の情報全部削除する関数
    $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";//
  $params = array($user_id);
  execute_query($db, $sql, $params);//execute_query関数で削除完了
}


function sum_carts($carts){//カート内の計算する関数
  $total_price = 0;//最初はもちろん0
  foreach($carts as $cart){//フォーリーチでカート内ぐるぐる
    $total_price += $cart['price'] * $cart['amount'];//足したりかけたり
  }
  return $total_price;//最終的になんぼになったかを吐き出す
}

function validate_cart_purchase($carts){//購入結果のバリデ関数
  if(count($carts) === 0){//カートの中からやったら
    set_error('カートに商品が入っていません。');//セッション箱にエラーメッセージ入れる
    return false;//処理やめぴ
  }
  foreach($carts as $cart){//フォーリーチでカート内ぐるぐる
    if(is_open($cart) === false){//非公開のやつやったら
      set_error($cart['name'] . 'は現在購入できません。');//セッション箱にエラーメッセージ入れる
    }
    if($cart['stock'] - $cart['amount'] < 0){//ストックと見比べて足りんかったら
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);//セッション箱にエラーメッセージ入れる
    }
  }
  if(has_error() === true){//エラーみっけ！！
    return false;//処理やめぴ
  }
  return true;//エラーなかったら、何事もなかったかのように澄まし顔
}

