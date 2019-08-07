<?php foreach(get_errors() as $error){ ?>
  <p><?php print $error; ?></p>
<?php } ?>
<?php foreach(get_messages() as $message){ ?>
  <p class="message"><?php print $message; ?></p>
<?php } ?>