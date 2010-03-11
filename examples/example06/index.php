<?php
# Before render example

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';



function configure()
{
  option('env', ENV_DEVELOPMENT);
  layout('html_default_layout');
}

function before_render($content_or_func, $layout, $locals, $view_path)
{
  if(is_callable($content_or_func))
  {
    
  }
  elseif(file_exists($view_path))
  {
    # we can distinguish layouts from normal views if we want
    # $is_a_layout = array_key_exists('content', $locals);
    $view_path = render_filter_rewrite_short_tags($view_path);
  }
  else
  {
    # it's a string
    $content_or_func .= "<small>added before render…</small>";
  }
  
  return array($content_or_func, $layout, $locals, $view_path);
}

/**
 * a filter for rewriting views without short_open_tags
 *
 * @param string $view_path 
 * @return string $path to converted view file
 */
function render_filter_rewrite_short_tags($view_path)
{
  if (option('rewrite_short_tags') == true && (boolean)@ini_get('short_open_tag') === false)
  {
    # cache path, maybe in tmp/cache/rsot_views/
    $cache_path = file_path(option('rewrite_sot_cache_dir'), $view_path);
    if(!file_exists($cache_path) || (filemtime($cache_path) != filemtime($view_path)))
    {
      $view = file_get_contents($view_path);
      $transformed_view = preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $view));
      # TODO: store it in cache
      # …
    }
    $view_path = $cache_path;
  }
  return $view_path;
}

dispatch('/', 'index');
  function index()
  {
    return html('<p>Hellooo!</p>');
  }
  
dispatch('/error', 'index_error');
  function index_error()
  {
    return halt('Error!');
  }

run();

# _INLINE templates___________________________________________________________
  
function html_default_layout($vars){ extract($vars);?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Before render filter test</title>
</head>
<body>
  <article>
    <?=$content;?>
  </article>
  <hr>
  <nav>
    <p><strong>Menu:</strong>
      <a href="<?=url_for('/')?>">Index</a> |
      <a href="<?=url_for('/error')?>">Error</a>
    </p>
  </nav>
  <hr>
</body>
</html>
<?};

?>