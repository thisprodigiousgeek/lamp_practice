<header>
  <p>ようこそ、<?php print($user['name']); ?>さん。</p>
  <ul>
    <li><a href="<?php print(HOME_URL);?>">ホーム</a></li>
    <li><a href="<?php print(LOGOUT_URL);?>">ログアウト</a></li>
    <li><a href="<?php print(CART_URL);?>">カート</a></li>
    <?php if(is_admin($user)){ ?>
      <li><a href="<?php print(ADMIN_URL);?>">管理</a></li>
    <?php } ?>
  </ul>
</header>