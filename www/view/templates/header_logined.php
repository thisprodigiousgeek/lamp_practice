<header>
  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <a class="navbar-brand" href="<?php print htmlspecialchars(HOME_URL,ENT_QUOTES,'UTF-8');?>">Market</a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#headerNav" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="ナビゲーションの切替">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="headerNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php print htmlspecialchars(CART_URL,ENT_QUOTES,'UTF-8');?>">カート</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php print htmlspecialchars(LOGOUT_URL,ENT_QUOTES,'UTF-8');?>">ログアウト</a>
        </li>
        <?php if(is_admin($user)){ ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php print htmlspecialchars(ADMIN_URL,ENT_QUOTES,'UTF-8');?>">管理</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </nav>
  <p>ようこそ、<?php print htmlspecialchars($user['name'],ENT_QUOTES,'UTF-8'); ?>さん。</p>
</header>