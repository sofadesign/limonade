<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

ini_set('display_errors', 1);

function configure()
{
  option('env', ENV_DEVELOPMENT);
  option('debug', true);
}

function before()
{
  layout('html_my_layout'); # setting default html layout
}

dispatch('/', 'hello_world');
  function hello_world()
  {
    return html("Hello !");
  }

dispatch('/:who/is_going_wrong', 'wrong');
  function wrong()
  {
    halt(params('who')." is going wrong...");
    return 'this string will never be displayed';
  }
  
dispatch('/welcome/:name', 'welcome');
  function welcome()
  {
    $name = params('name');
    
    if(empty($name))      halt(NOT_FOUND, "Undefined name.");
    switch($name)
    {
      case "bill":
      trigger_error('No bill please', E_USER_WARNING);
      break;
      
      case "leland":
      halt('Fire walk with me', E_USER_ERROR);
      break;
      
      case "bob":
      halt(401, "no, go away!", array(1,2,3));
      break;
      
      default:
      trigger_error("Not sure $name lives in Twin Peaks", E_USER_NOTICE);
      # E_USER_NOTICE doesn't stop app execution
      break;
    } 
    return html("Welcome $name");
  }

dispatch('/how_are_you/:name', 'how_are_you');
  function how_are_you()
  {
    $name = params('name');
    if(empty($name)) halt(NOT_FOUND, "Undefined name.");
    return html("I hope you are fine, $name.");
  }

error(NOT_FOUND, 'my_not_found_error_handler');
  function my_not_found_error_handler($errno, $errstr, $errfile, $errline)
  {
    status(NOT_FOUND);
    return html("I'm not here ($errstr): in $errfile line $errline");
  }
  
// error(HTTP_STATUS_CODES, 'my_other_http_status_handler');
//   function my_other_http_status_handler($errno, $errstr, $errfile, $errline)
//   {
//     status($errno);
//     return http_response_status($errno). " - $errstr ";
//   }

// error(array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE), 'my_php_error_handler');
//   function my_php_error_handler($errno, $errstr, $errfile, $errline)
//   {
// 
//   }
// 
// error(PHP_ERRORS, 'default_error_handler');


run();

# HTML Layouts and templates

function html_my_layout($vars){ extract($vars);?> 
<html>
<head>
	<title>Limonde second example</title>
</head>
<body>
  <h1>Limonde second example: errors</h1>
	<?=$content?>
	<hr>
	<p>
	<a href="<?=url_for('/')?>">Home</a> |
	<a href="<?=url_for('/everything/is_going_wrong')?>">everything is going wrong</a> | 
	<a href="<?=url_for('/welcome/')?>">Welcome !</a> | 
	<a href="<?=url_for('/welcome/bill')?>">Welcome Bill ?</a> | 
	<a href="<?=url_for('/welcome/leland')?>">Welcome Leland ?</a> | 
	<a href="<?=url_for('/welcome/bob')?>">Welcome Bob ?</a> | 
	<a href="<?=url_for('/welcome/audrey')?>">Welcome Audrey ?</a> | 
	</p>
</body>
</html>
<?}



?>
