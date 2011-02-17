<?php
require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

function before()
{
  layout('html_default_layout');
}

dispatch('/', 'index');
  function index()
  {
    flash('notice', 'ON DISPLAY 2');
    return html('<p>Hellooo!</p>');
  }
  
dispatch('/two', 'index_two');
  function index_two()
  {
    flash('notice', 'ON DISPLAY 3');
    return html('<p>Hellooo!</p>');
  }
dispatch('/three', 'index_three');
  function index_three()
  {
    flash('error', 'ON DISPLAY 4');
    return html('<p>Hellooo!</p>');
  }
dispatch('/four', 'index_four');
  function index_four()
  {
    return html('<p>NO FLASH MESSAGE ON NEXT PAGE</p>');
  }
dispatch('/five', 'index_five');
  function index_five()
  {
    flash('error', 'ON DISPLAY 6');
    redirect_to('six');
  }
dispatch('/six', 'index_six');
  function index_six()
  {
    return html('<p>REDIRECTED FROM INDEX FIVE...</p><p>There will be no flash message on next page.</p>');
  }



run();

# _INLINE templates___________________________________________________________
  
function html_default_layout($vars){ extract($vars);?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Flash features test</title>
</head>
<body>
  <article>
    <?php echo $content;?>
    
    <?php if(!empty($flash)): ?>
    <section>
      <h2>Current flash messages ( flash_now() / $flash )</h2>
      <pre><code>
        <?php echo  var_dump(flash_now()); ?>
      </code></pre>
    </section>
    <?php endif; ?>
  </article>
  <hr>
  <nav>
    <p><strong>Menu:</strong>
      <a href="<?php echo url_for('/')?>">One</a> |
      <a href="<?php echo url_for('two')?>">Two</a> |
      <a href="<?php echo url_for('three')?>">Three</a> |
      <a href="<?php echo url_for('four')?>">Four</a> |
      <a href="<?php echo url_for('five')?>">Five</a> |
      <a href="<?php echo url_for('six')?>">Six</a>
    </p>
  </nav>
</body>
</html>
<?};
