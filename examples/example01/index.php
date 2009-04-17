<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

function configure()
{
  option('env', ENV_DEVELOPMENT);
}

function before()
{
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

dispatch('/how_are_you/:name', 'how_are_you');
  function how_are_you()
  {
    $name = params('name');
    if(empty($name)) halt(NOT_FOUND, "Undefined name.");
    return html("I hope you are fine, $name.");
  }


run();

# HTML Layouts and templates

function html_my_layout($vars){ extract($vars);?> 
<html>
<head>
	<title>Limonde first example</title>
</head>
<body>
  <h1>Limonde first example</h1>
	<?=$content?>
	<hr>
	<a href="<?=url_for('/')?>">Home</a> |
	<a href="<?=url_for('/hello/', $name)?>">Hello</a> | 
	<a href="<?=url_for('/welcome/', $name)?>">Welcome !</a> | 
	<a href="<?=url_for('/how_are_you/', $name)?>">How are you ?</a>
</body>
</html>
<?}

function html_welcome($vars){ extract($vars);?> 
<h3>Hello <?=$name?>!</h3>
<p><a href="<?=url_for('/how_are_you/', $name)?>">How are you <?=$name?>?</a></p>
<?}



?>
