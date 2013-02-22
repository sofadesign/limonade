<?php
require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

function configure()
{
  option('env', ENV_DEVELOPMENT);
}

function before($route)
{
  header("X-LIM-route-function: ".$route['callback']);
  layout('html_my_layout');
}

dispatch('/', 'hello_world');
  function hello_world()
  {
    return "Hello world!";
  }

dispatch('/hello/:who', 'hello');
  function hello()
  {
    set_or_default('name', params('who'), "everybody");
    return html("Hello %s!");
  }
  
dispatch('/welcome/:name', 'welcome');
  function welcome()
  {
    set_or_default('name', params('name'), "everybody");    
    return html("html_welcome");
  }

dispatch('/are_you_ok/:name', 'are_you_ok');
  function are_you_ok($name = null)
  {
    if(is_null($name))
    {
      $name = params('name');
      if(empty($name)) halt(NOT_FOUND, "Undefined name.");

    }
    set('name', $name);
    return html("Are you ok $name ?");
  }
    
dispatch('/how_are_you/:name', 'how_are_you');
  function how_are_you()
  {
    $name = params('name');
    if(empty($name)) halt(NOT_FOUND, "Undefined name.");
    # you can call an other controller function if you want
    if(strlen($name) < 4) return are_you_ok($name);
    set('name', $name);
    return html("I hope you are fine, $name.");
  }
  

  
dispatch('/images/:name/:size', 'image_show');
  function image_show()
  {
    $ext = file_extension(params('name'));
    $filename = option('public_dir').basename(params('name'), ".$ext");
    if(params('size') == 'thumb') $filename .= ".thb";
    $filename .= '.jpg';
    
    if(!file_exists($filename)) halt(NOT_FOUND, "$filename doesn't exists");
    render_file($filename);
  }

dispatch('/*.jpg/:size', 'image_show_jpeg_only');
  function image_show_jpeg_only()
  {
    $ext = file_extension(params(0));
    $filename = option('public_dir').params(0);
    if(params('size') == 'thumb') $filename .= ".thb";
    $filename .= '.jpg';
  
    if(!file_exists($filename)) halt(NOT_FOUND, "$filename doesn't exists");
    render_file($filename);
  }

function after($output, $route)
{
  $time = number_format( microtime(true) - LIM_START_MICROTIME, 6);
  $output .= "\n<!-- page rendered in $time sec., on ".date(DATE_RFC822)." -->\n";
  $output .= "<!-- for route\n";
  $output .= print_r($route, true);
  $output .= "-->";
  return $output;
}


run();

# HTML Layouts and templates

function html_my_layout($vars){ extract($vars);?> 
<html>
<head>
	<title>Limonade first example</title>
</head>
<body>
  <h1>Limonade first example</h1>
	<?php echo $content?>
	<hr>
	<a href="<?php echo url_for('/')?>">Home</a> |
	<a href="<?php echo url_for('/hello/', $name)?>">Hello</a> | 
	<a href="<?php echo url_for('/welcome/', $name)?>">Welcome !</a> | 
	<a href="<?php echo url_for('/are_you_ok/', $name)?>">Are you ok ?</a> | 
	<a href="<?php echo url_for('/how_are_you/', $name)?>">How are you ?</a>
</body>
</html>
<?php }

function html_welcome($vars){ extract($vars);?> 
<h3>Hello <?php echo $name?>!</h3>
<p><a href="<?php echo url_for('/how_are_you/', $name)?>">How are you <?php echo $name?>?</a></p>
<hr>
<p><a href="<?php echo url_for('/images/soda_glass.jpg')?>">
   <img src="<?php echo url_for('/soda_glass.jpg/thumb')?>"></a></p>
<?php }
