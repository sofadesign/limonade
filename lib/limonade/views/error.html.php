  <h2><?=h(error_http_status($errno));?></h2>
  <? if($is_http_error): ?>
  <p><?=h($errstr)?></p>
  <? endif; ?>
  
  <?= render('_debug.html.php', null, $vars); ?>