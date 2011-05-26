<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

ini_set('display_errors', 1);

function configure()
{
  option('env', ENV_DEVELOPMENT);
  option('debug', true);
}

function before($route)
{
  header("X-LIM-route-function: ".$route['function']);
  header("X-LIM-route-params: ".json_encode($route['params']));
  header("X-LIM-route-options: ".json_encode($route['options']));
  layout('html_my_layout'); # setting default html layout
  //error_layout('html_my_layout');
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
      halt(HTTP_UNAUTHORIZED, "no, go away!", array(1,2,3));
      break;
      
      case "david":
      redirect_to('/');
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

error(HTTP_FORBIDDEN, 'my_not_found_error_handler'); /* HTTP 403 Forbiden */
  function my_forbiden_error_handler($errno, $errstr, $errfile, $errline)
  {
    status(HTTP_FORBIDDEN);
    return html("<p>$errstr</p><p>Unauthorized access !!!</p>");
  }

/* just change the not found error output */   
// function not_found($errno, $errstr, $errfile, $errline)
// {
//   return html("<h2>My NotFound Page</h2><p>".$errstr."</p><p>I'm not here...</p>");
// }

/* just change the server error output */
// function server_error($errno, $errstr, $errfile, $errline)
// {
//   return   "<h2>Internal server error</h2><p>[".error_type($errno)."] $errstr ".
//            "(in <strong>$errfile</strong> line <strong>$errline</strong>)</p>";
// }
  
// error(E_LIM_HTTP, 'my_other_http_status_handler'); // only http errors
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
// error(E_LIM_PHP, 'error_default_handler'); // all other php errors


run();

# HTML Layouts and templates

function html_my_layout($vars){ extract($vars);?> 
<html>
<head>
	<title>Limonade second example</title>
</head>
<body>
  <h1>Limonade second example: errors</h1>
	<?php echo $content?>
	<hr>
	<p>
	<a href="<?php echo url_for('/')?>">Home</a> |
	<a href="<?php echo url_for('/everything/is_going_wrong')?>">everything is going wrong</a> | 
	<a href="<?php echo url_for('/welcome/')?>">Welcome !</a> | 
	<a href="<?php echo url_for('/welcome/bill')?>">Welcome Bill ?</a> | 
	<a href="<?php echo url_for('/welcome/leland')?>">Welcome Leland ?</a> | 
	<a href="<?php echo url_for('/welcome/bob')?>">Welcome Bob ?</a> | 
	<a href="<?php echo url_for('/welcome/david')?>">Welcome David ?</a> | 
	<a href="<?php echo url_for('/welcome/audrey')?>">Welcome Audrey ?</a> | 
	</p>
</body>
</html>
<!--
<?php print_r(benchmark()); ?>
-->
<?};
