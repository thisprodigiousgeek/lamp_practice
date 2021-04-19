<?php
/**
 * ワンタイムトークンの生成
 * @param void
 * @return str $token ワンタイムトークン
 */
function get_csrf_token(){
    // get_random_strin()はユーザ定義関数
    $token = get_random_string(30);
    // set_session()はユーザー定義関数
    set_session('csrf_token', $token);
    return $token;
}
/**
 * トークンの照合
 * @param str $token ワンタイムトークン
 * @return bool
 */
function is_valid_csrf_token($token){
    if($token === ''){
        return false;
    }
    // get_session()はユーザ定義関数
    return $token === get_session('csrf_token');
}

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CSRF</title>
        <script src="//code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    </head>
    <body>
        <h1>csrf体験</h1>
        <p>
            CSRFを体験してみましょう。
            勝手にカートに商品が追加されます。
            <a href="cart.php">カートへ移動</a>
        </p>
        <!-- ウィンドウの中に独立して表示される形式のインラインフレームを作成 -->
        <iframe
            id="csrf"
            src="index.php">
        </iframe>
        <script>
            setTimeout(function(){
                $('#csrf').contents().find('form').each(function(){
                    $(this).submit();
                });

                alert('カートに商品を登録しました。');
            }, 3000);

        </script>
    </body>
</html>