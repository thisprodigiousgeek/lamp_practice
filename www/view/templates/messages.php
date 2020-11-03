<?php foreach(get_errors() as $error){ ?>
  <p class="alert alert-danger"><span><?php print htmlspecialchars($error,ENT_QUOTES,'UTF-8'); ?></span></p>
<?php } ?>
<?php foreach(get_messages() as $message){ ?>
  <p class="alert alert-success"><span><?php print htmlspecialchars($message,ENT_QUOTES,'UTF-8'); ?></span></p>
<?php } ?>