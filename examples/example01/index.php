<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

function configure()
{
  option('env', ENV_DEVELOPMENT);
}

dispatch('/', 'hello_world');
  function hello_world()
  {
    return "Hello world!";
  }

dispatch('/hello/:who', 'hello');
  function hello()
  {
    $who = params('who');
    if(empty($who)) $who = "everybody";
    set('name', $who);
    return html("Hello %s!");
  }
  
dispatch('/welcome/:name', 'welcome');
  function welcome()
  {
    $name = params('name');
    if(empty($name)) $name = "everybody";
    set('name', $name);
    layout('html_my_layout');
    return html("html_welcome");
  }



run();

# --- Views

function html_my_layout($vars){ extract($vars);?> 
<html>
<head>
	<title>HELLO !</title>
</head>
<body>
	<?=$content?>
</body>
</html>
<?}

function html_welcome($vars){ extract($vars);?> 
<h3>Hello <?=$name?>!</h3>
<p><a href="<?=url_for('/how_are_you/', $name)?>">How are you <?=$name?>?</a></p>
<?}



?>
