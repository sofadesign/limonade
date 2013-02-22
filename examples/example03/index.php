<?php
require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

function configure()
{
  option('env', ENV_DEVELOPMENT);
}

function before($route = array())
{
  #print_r($route); exit;
  #inspect the $route array, looking at various options that may have been passed in
  if (@$route['options']['authenticate'])
    authenticate_user() or halt("Access denied");
  
  if (@$route['options']['validation_function'])
    call_if_exists($route['options']['validation_function'], params()) or halt("Woops! Params did not pass validation");
  
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

# defaults work the same as always... 
dispatch('/', 'hello_world');
  function hello_world()
  {
    return "Hello world!";
  }

# able to pass options to routes, which are also available in the 'before' filter in the $route argument
dispatch('/account', 'user_account', 
  array("authenticate" => TRUE));
  function user_account()
  {
    return "You are authenticated (or rather, you would be if the 'authenticate_user' was real)";
  }

# sometimes there param validation rules that are difficult or impossible to implement as a regex
# Here is an example of attaching a validation function to a route.
# Call this with /validate/1234 to pass, or with any other argument to fail
dispatch('/validate/*', 'validate_test', 
  array('validation_function' => 'a_silly_validation_function'));
  function validate_test(){
    return "Yup! You've passed the validation test";
  }
run();

//------------------------------
//  Utilities
//------------------------------
function authenticate_user()
{
  //auth the user here...
  return true;
}


function a_silly_validation_function($params)
{
  //perhaps this looks something up in the database...
  if (isset($params[0]) && $params[0] == "1234") return true;
  
  return false;
}

