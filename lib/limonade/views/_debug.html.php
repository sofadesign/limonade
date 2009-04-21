<? if(option('env') > ENV_PRODUCTION && option('debug')): ?>
  <? if(!$is_http_error): ?>
  <p>[<?=error_type($errno)?>]
  	<?=$errstr?> (in <strong><?=$errfile?></strong> line <strong><?=$errline?></strong>)
  	</p>
  	<? endif; ?>

  <?if($debug_args = set('_lim_err_debug_args')): ?>
  <p><strong>Debug arguments</strong></p>
  	<pre><code><?=h(print_r($debug_args, true))?></code></pre>
  <? endif; ?>

  <p><strong>Debug Trace</strong></p>
  <pre><code><?=h(print_r(debug_backtrace(), true))?></code></pre>

  <p><strong>Limonade options</strong></p>
  <pre><code><?=h(print_r(option(), true))?></code></pre>
<? endif; ?>