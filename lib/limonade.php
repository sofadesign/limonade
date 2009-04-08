<?php
## SET BASIC SECURITY

# 1. Unsets all global variables set from a superglobal array
function unregister_globals()
{
  $args = func_get_args();
  foreach($args as $k => $v)
    if(array_key_exists($k, $GLOBALS)) unset($GLOBALS[$key]);
}

if(ini_get('register_globals'))
{
  unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
  ini_set('register_globals', 0);
}

# 2. removing magic quotes
function remove_magic_quotes($array)
{
  foreach ($array as $k => $v)
    $array[$k] = is_array($v) ? remove_magic_quotes($v) : stripslashes($v);
  return $array;
}

if (get_magic_quotes_gpc())
{
  $_GET    = remove_magic_quotes($_GET);
  $_POST   = remove_magic_quotes($_POST);
  $_COOKIE = remove_magic_quotes($_COOKIE);
  ini_set('magic_quotes_gpc', 0);
}

if(get_magic_quotes_runtime()) set_magic_quotes_runtime(false);

# 3. Disable error display
ini_set('display_errors', 0); # by default, no error reporting; 
                              # will be switched on later if in dev. mode



## SOME CONSTANTS
define_unless_exists('NOT_FOUND', 404);
define_unless_exists('SERVER_ERROR', 500);
define_unless_exists('ENV_PRODUCTION', 10);
define_unless_exists('ENV_DEVELOPMENT', 100);


## Abstracts ___________________________________________________________________

# function configure(){}
# function before(){}
# function after(){}
# function not_found(){}
# function error(){}

## Main framework functions ____________________________________________________

/**
 * Set and returns options values
 * 
 * If multiple values are provided, set $name option with an array of those values.
 * If only ther is only one value, set $name option with the provided $values
 *
 * @param string $name 
 * @param mixed  $values,... 
 * @return mixed option value for $name if $name argument is provided, else return all options
 */
function option($name = null, $values = null)
{
   static $options = array();
   $args = func_get_args();
   $name = array_shift($args);
   if(is_null($name)) return $options;
   if(!empty($args))
   {
     $options[$name] = count($args) > 1 ? $args : $args[0];
   }
   if(array_key_exists($name, $options)) return $options[$name];
   return;
}

/**
 * Set and returns params
 * 
 * Depending on provided arguments:
 * 
 *  * Reset params if first argument is null
 * 
 *  * If first argument is an array, merge it with current params
 * 
 *  * If there is a second argument $value, set param $name (first argument) with $value
 * <code>
 *  params('name', 'Doe') // set 'name' => 'Doe'
 * </code>
 *  * If there is more than 2 arguments, set param $name (first argument) value with
 *    an array of next arguments
 * <code>
 *  params('months', 'jan', 'feb', 'mar') // set 'month' => array('months', 'jan', 'feb', 'mar')
 * </code>
 * 
 * @param mixed $name_or_array_or_null could be null || array of params || name of a param (optional)
 * @param mixed $value,... for the $name param (optional)
 * @return mixed all params, or one if a first argument $name is provided
 */
function params($name_or_array_or_null = null, $value = null)
{
  static $params = array();
  $args = func_get_args();

  if(func_num_args() > 0)
  {
    $name = array_shift($args);
    if(is_null($name))
    {
      # Reset params
      $params = array();
      return $params;
    }
    if(is_array($name))
    {
      $params = array_merge($params, $name);
      return $params;
    }
    $nargs = count($args);
    if($nargs > 0)
    {
      $value = $nargs > 1 ? $args : $args[0];
      $params[$name] = $value;
    }
    return $params[$name];
  }

  return $params;
}

/**
 * Returns limonade environment variables:
 *
 * 'SERVER', 'FILES', 'REQUEST', 'SESSION', 'ENV', 'COOKIE', 
 * 'GET', 'POST', 'PUT', 'DELETE'
 * 
 * If a null argument is passed, reset and rebuild environment
 *
 * @param null @reset reset and rebuild environment
 * @return array
 */
function env($reset = null)
{
  static $env = array();
  if(func_num_args() > 0)
  {
    $args = func_get_args();
    if(is_null($args[0])) $env = array();
  }
  
  if(empty($env))
  {
    $glo_names = array('SERVER', 'FILES', 'REQUEST', 'SESSION', 'ENV', 'COOKIE');
      
    $vars = array_merge($glo_names, request_methods());
    foreach($vars as $var)
    {
      $varname = "_$var";
      if(!array_key_exists("$varname", $GLOBALS)) $GLOBALS[$varname] = array();
      $env[$var] =& $GLOBALS[$varname];
    }
    
    $method = request_method($env);
    if($method == 'PUT' || $method == 'DELETE')
    {
      $varname = "_$method";
      if(array_key_exists('_method', $_POST) && $_POST['_method'] == $method)
      {
        foreach($_POST as $k => $v)
        {
          if($k == "_method") continue;
          $GLOBALS[$varname][$k] = $v;
        }
      }
      else
      {
        parse_str(file_get_contents('php://input'), $GLOBALS[$varname]);
      }
    }
  }
  return $env;
}

/**
 * Set and returns template variables
 * 
 * If multiple values are provided, set $name variable with an array of those values.
 * If only ther is only one value, set $name variable with the provided $values
 *
 * @param string $name 
 * @param mixed  $values,... 
 * @return mixed variable value for $name if $name argument is provided, else return all variables
 */
function set($name = null, $values = null)
{
  static $vars = array();
  $args = func_get_args();
  $name = array_shift($args);
  if(is_null($name)) return $vars;
  if(!empty($args))
  {
    $vars[$name] = count($args) > 1 ? $args : $args[0];
  }
  if(array_key_exists($name, $vars)) return $vars[$name];
  return $vars;
}

/**
 * Call the error function, passing a given error type and an optional message,
 * then exit.
 *
 * @param string $error 
 * @param string $msg
 * @return void
 */
function halt($error, $msg = null, $debug_args = null)
{
   $args = func_get_args();
   $error = array_shift($args);
   if(!empty($args)) $msg = array_shift($args);
   if(empty($msg) && $error == NOT_FOUND) $msg = request_uri();
   if(empty($msg)) $msg = "";
   if(!empty($args)) $debug_args = $args;
   echo error($error, $msg, $debug_args);
   
   exit;
}

function run($env = null)
{
  if(is_null($env)) $env = env();
   
  # Configure
  option('public_dir',      'public/');
  option('views_dir',       'views/');
  option('controllers_dir', 'controllers/');
  option('libs_dir',        'lib/');
  option('env',             ENV_PRODUCTION);
  option('encoding',        'utf-8');

  # loading libs
  
  call_if_exists('configure');
  
  if(option('env') > ENV_PRODUCTION)
  {
   ini_set('display_errors', 1);
  }
  
  # Set some default methods
  if(!function_exists('error'))
  {
    function error($name, $msg="", $debug_args = null)
    {
    	switch($name)
    	{	
    		case 404:
    		status(404);
    		return not_found($msg);
    		break;

    		default:
    		status(500);
    		$o = "<h1>Internal Server Error:</h1><p>{h($msg)}</p>";
    		if(option('env') > ENV_PRODUCTION)
    		{
    		  $o .= "<p><strong>Debug arguments</strong></p>";
    		  $o .= "<pre><code>$debug_args</code></pre>";
    		  $o .= "<p><strong>Debug Trace</strong></p>";
    		  $o .= "<pre><code>{h(debug_backtrace())}</code></pre>";
    		}
    		  
    		return html($o, "default_layout");
    		break;
    	}
    }
  }
  
  if(!function_exists('not_found'))
  {
    function not_found($msg="")
    {
      option('views_dir', dirname(__FILE__).'/limonade/views/');
      $msg = h($msg);
      return html("<h1>Page not found:</h1><p>{$msg}</p>", "default_layout.php");
    }
  }
  
  if(!function_exists('after'))
  {
    function after($output)
    {
      return $output;
    }
  }
  
  
  # Check request
  if($rm = request_method())
  {
    # Check matching route
    if($route = route_find($rm, request_uri()))
    {
      params($route['params']);
      
      if(function_exists($route['function']))
      {
        call_if_exists('before');
        if($output = call_user_func($route['function']))
        {
          echo after($output);
        }
        exit;
      }
      else halt(SERVER_ERROR, "Routing error: undefined function '{$route['function']}'", $route);      
    }
    else halt(NOT_FOUND);
    
  }
  else halt(SERVER_ERROR, "Unknown request method <code>$rm</code>");
  
}

function session()
{
  
}

## Router functions ____________________________________________________________

/**
 * an alias of dispatch_get
 *
 * @return void
 */
function dispatch($path_or_array, $function, $agent_regexp = null)
{
  dispatch_get($path_or_array, $function, $agent_regexp);
}

function dispatch_get($path_or_array, $function, $agent_regexp = null)
{
  route("GET", $path_or_array, $function, $agent_regexp);
}

function dispatch_post($path_or_array, $function, $agent_regexp = null)
{
   route("GET", $path_or_array, $function, $agent_regexp);
}

function dispatch_put($path_or_array, $function, $agent_regexp = null)
{
   route("GET", $path_or_array, $function, $agent_regexp);
}

function dispatch_delete($path_or_array, $function, $agent_regexp = null)
{
   route("GET", $path_or_array, $function, $agent_regexp);
}


## Output function _____________________________________________________________
function status($code = 500)
{
	switch($code)
	{
		case 304:
		$str = 'HTTP/1.1 304 Not Modified';
		break;
		
		case 400:
		$str = 'HTTP/1.1 404 Not Found';
		break;
		
		default:
		$str = 'HTTP/1.1 500 Internal Server Error';
		break;
	}
	header($str);
}

function render($content_or_func, $layout = '', $locals = array())
{
	$args = func_get_args();
	$content_or_func = array_shift($args);
	$layout = count($args) > 0 ? array_shift($args) : layout();
	$view_path = option('views_dir').$content_or_func;
	$vars = array_merge(set(), $locals);

	if(file_exists($view_path))
	{
		ob_start();
		extract($vars);
		
		include $view_path;
		$content = ob_get_clean();
	}
	elseif(function_exists($content_or_func))
	{
		ob_start();
		call_user_func($content_or_func, $vars);
		$content = ob_get_clean();
	}
	else
	{
	  $content = vsprintf($content_or_func, $vars);
	}

	if(empty($layout)) return $content;

	return render($layout, null, array('content' => $content));
}

function layout($function_or_file = null)
{
	static $layout = null;
	if(func_num_args() > 0) $layout = $function_or_file;
	return $layout;
}

function html($content_or_func, $layout = '', $locals = array())
{
   $args = func_get_args();
   return call_user_func_array('render', $args);
}

function xml()
{
   
}

function json()
{
   
}

function txt()
{
   
}

# ==============================================================================
#    HELPERS
# ==============================================================================
function url_for($params = null)
{
  #TODO enhanced url_for (url rewriting or not...)

  $env = env();
  $request_uri = $env['SERVER']['REQUEST_URI'];
  $base_path   = $env['SERVER']['SCRIPT_NAME'];

  if(strpos($request_uri, '?') !== FALSE) $base_path .= "?";

  $paths = array();
  $params = func_get_args();
  foreach($params as $param)
  {
    $p = explode('/',$param);
    foreach($p as $v)
    {
      if(!empty($v)) $paths[] = urlencode($v);
    }
  }
  
  return $base_path."/".implode('/', $paths);
}

function h($str, $quote_style = ENT_NOQUOTES, $charset = null)
{
	if(is_null($charset)) $charset = strtoupper(option('encoding'));
	return htmlspecialchars($str, $quote_style, $charset); 
}

# ==============================================================================
#    PRIVATE
# ==============================================================================

/**
 * Calls a function if exists
 *
 * @param string $func the function name
 * @param mixed $arg,.. (optional)
 * @return mixed
 */
function call_if_exists($func)
{
  $args = func_get_args();
  $func = array_shift($args);
  if(function_exists($func)) return call_user_func_array($func, $args);
  return;
}

function define_unless_exists($name, $value)
{
  if(!defined($anme)) define($name, $value);
}

/**
 * Returns application root file path
 *
 * @return void
 */
function app_file()
{
  static $file;
  if(empty($file))
  {
    $stacktrace = array_pop(debug_backtrace());
    $file = $stacktrace['file'];
  }
  return $file;
}

/**
 * Add route if required params are provided.
 * Delete all routes if null is passed as a unique argument
 * Return all routes
 * 
 *
 * @param string $method 
 * @param string $path_or_array 
 * @param string $func 
 * @param string $agent_regexp 
 * @return array
 */
function route()
{
	static $routes = array();
	$nargs = func_num_args();
	if( $nargs > 0)
	{
	  $args = func_get_args();
	  if($nargs === 1 && is_null($args[0])) $routes = array();
	  else if($nargs < 3) trigger_error("Missing arguments for route()", E_USER_ERROR);
	  else
	  {
	    $method        = $args[0];
  	  $path_or_array = $args[1];
  	  $func          = $args[2];
  	  $agent_regexp  = array_key_exists(3, $args) ? $args[3] : null;

  	  $routes[] = route_build($method, $path_or_array, $func, $agent_regexp);
	  }
	  
	}
	return $routes;
}

/**
 * An alias of route(null): reset all routes
 *
 * @return void
 */
function route_reset()
{
  route(null);
}

/**
 * Build a route and return it
 *
 * @param string $method 
 * @param string $path_or_array 
 * @param string $func 
 * @param string $agent_regexp 
 * @return array
 */
function route_build($method, $path_or_array, $func, $agent_regexp = null)
{
   $method = strtoupper($method);
   if(!in_array($method, request_methods())) 
      trigger_error("'$method' request method is unkown or unavailable.", E_USER_ERROR);
   
   if(is_array($path_or_array))
   {
      $path  = array_shift($path_or_array);
      $names = $path_or_array[0];
   }
   else
   {
      $path  = $path_or_array;
      $names = array();
   }
   
   $single_asterisk_subpattern = "(?:/([^\/]*))?";
   $double_asterisk_subpattern = "(?:/(.*))?";
   $optionnal_slash_subpattern = "(?:/*?)";
   
   if($path[0] == "^")
   {
     if($path{strlen($path) - 1} != "$") $path .= "$";
     $pattern = "#".$path."#i";
   }
   else if(empty($path) || $path == "/")
   {
     $pattern = "#^".$optionnal_slash_subpattern."$#";
   }
   else
   {
     $parsed = array();
     $elts = explode('/', $path);
     $parameters_count = 0;
     
     foreach($elts as $elt)
     {
       if(empty($elt)) continue;
       
       $name = null; 
       
       # extracting double asterisk **
       if($elt == "**"):
         $parsed[] = $double_asterisk_subpattern;
         $name = $parameters_count;
       
       # extracting single asterisk *
       elseif($elt == "*"):
         $parsed[] = $single_asterisk_subpattern;
         $name = $parameters_count;
               
       # extracting named parameters :my_param 
       elseif($elt[0] == ":"):
         if(preg_match('/^:([^\:]+)$/', $elt, $matches))
         {
           $parsed[] = $single_asterisk_subpattern;
           $name = $matches[1];
         };
       
       else:
         $parsed[] = "/".preg_quote($elt, "#");
       
       endif;
       
       /* set parameters names */ 
       if(is_null($name)) continue;
       if(!array_key_exists($parameters_count, $names) || is_null($names[$parameters_count]))
         $names[$parameters_count] = $name;
       $parameters_count++;
     }
     
     $pattern = "#^".implode('', $parsed).$optionnal_slash_subpattern."?$#i";
   }
   
   return array( "method"       => $method,
                 "pattern"      => $pattern,
                 "names"        => $names,
                 "function"     => $func,
                 "agent_regexp" => $agent_regexp );
}

/**
 * Find a route and returns it
 * If not found, returns false
 * Routes are checked from first added to last added.
 *
 * @param string $method 
 * @param string $path 
 * @return void
 */
function route_find($method, $path)
{
   $routes = route();
   $method = strtoupper($method);
   foreach($routes as $route)
   {
     if($method == $route["method"] && preg_match($route["pattern"], $path, $matches))
     {
       $params = array();
       if(count($matches) > 1)
       {
         array_shift($matches);
         $params = array_combine(array_values($route["names"]), $matches);
       }
       $route["params"] = $params;
       return $route;
     }
   }
   return false;
}


/**
 * Returns current request method for a given environment or current one
 *
 * @param string $env 
 * @return void
 */
function request_method($env = null)
{
  if(is_null($env)) $env = env();
  $m = array_key_exists('REQUEST_METHOD', $env['SERVER']) ? $env['SERVER']['REQUEST_METHOD'] : null;
  if($m == "POST" && array_key_exists('_method', $env['POST'])) 
    $m = strtoupper($env['POST']['_method']);
  if(!in_array(strtoupper($m), request_methods()))
  {
    trigger_error("'$m' request method is unkown or unavailable.", E_USER_WARNING);
    $m = false;
  }
  return $m;
}

/**
 * Checks if a request method or current one is allowed
 *
 * @param string $m 
 * @return void
 */
function request_method_is_allowed($m = null)
{
  if(is_null($m)) $m = request_method();
  return in_array(strtoupper($m), request_methods());
}

function request_is_get($env = null)
{
  return request_method($env) == "GET";
}

function request_is_post($env = null)
{
  return request_method($env) == "POST";
}

function request_is_put($env = null)
{
  return request_method($env) == "PUT";
}

function request_is_delete($env = null)
{
  return request_method($env) == "DELETE";
}

/**
 * Returns allowed request methods
 *
 * @return array
 */
function request_methods()
{
   return array("GET","POST","PUT","DELETE");
}

/**
 * Returns current request uri (the path that will be compared with routes)
 * 
 * (Inspired from codeigniter URI::_fetch_uri_string method)
 *
 * @return string
 */
function request_uri($env = null)
{
  #TODO test request_uri
  if(is_null($env)) $env = env();

  if(array_key_exists('url', $env['GET']))
  {
    $uri = $env['GET']['url'];
  }
  else if(array_key_exists('u', $env['GET']))
  {
    $uri = $env['GET']['u'];
  }
  else if (count($env['GET']) == 1 && trim(key($env['GET']), '/') != '')
	{
		$uri = key($env['GET']);
	}
	else
	{
    $app_file = app_file();
    $path_info = isset($env['SERVER']['PATH_INFO']) ? $env['SERVER']['PATH_INFO'] : @getenv('PATH_INFO');
    $query_string =  isset($env['SERVER']['QUERY_STRING']) ? $env['SERVER']['QUERY_STRING'] : @getenv('QUERY_STRING');
    
	  // Is there a PATH_INFO variable?
  	// Note: some servers seem to have trouble with getenv() so we'll test it two ways
  	if (trim($path_info, '/') != '' && $path_info != "/".$app_file)
  	{
  		$uri = $path_info;
  	}
  	// No PATH_INFO?... What about QUERY_STRING?
  	elseif (trim($query_string, '/') != '')
  	{
  		$uri = $query_string;
  	}
  	elseif(array_key_exists('REQUEST_URI', $env['SERVER']) && !empty($env['SERVER']['REQUEST_URI']))
  	{
  	  $request_uri = $env['SERVER']['REQUEST_URI'];
  	  $base_path = $env['SERVER']['SCRIPT_NAME'];

  	  if(strpos($request_uri, '?') !== FALSE) $base_path .= "?";
  	  $uri = str_replace($base_path, '', $request_uri);
  	}
  	elseif($env['SERVER']['argc'] > 1 && trim($env['SERVER']['argv'][1], '/') != '')
    {
      $uri = $env['SERVER']['argv'][1];
    }
	}
  
  $uri = rtrim($uri, "/"); # removes ending /
  if($uri[0] != '/') $uri = '/' . $uri; # add a leading slash
  return $uri;
}

/**
 * Returns all mime types in an associative array, with extensions as keys
 * (extracted from Orbit source http://orbit.luaforge.net/)
 *
 * @return array
 */
function mime_types()
{
  return array(
    'ai'      => 'application/postscript',
    'aif'     => 'audio/x-aiff',
    'aifc'    => 'audio/x-aiff',
    'aiff'    => 'audio/x-aiff',
    'asc'     => 'text/plain',
    'atom'    => 'application/atom+xml',
    'atom'    => 'application/atom+xml',
    'au'      => 'audio/basic',
    'avi'     => 'video/x-msvideo',
    'bcpio'   => 'application/x-bcpio',
    'bin'     => 'application/octet-stream',
    'bmp'     => 'image/bmp',
    'cdf'     => 'application/x-netcdf',
    'cgm'     => 'image/cgm',
    'class'   => 'application/octet-stream',
    'cpio'    => 'application/x-cpio',
    'cpt'     => 'application/mac-compactpro',
    'csh'     => 'application/x-csh',
    'css'     => 'text/css',
    'dcr'     => 'application/x-director',
    'dir'     => 'application/x-director',
    'djv'     => 'image/vnd.djvu',
    'djvu'    => 'image/vnd.djvu',
    'dll'     => 'application/octet-stream',
    'dmg'     => 'application/octet-stream',
    'dms'     => 'application/octet-stream',
    'doc'     => 'application/msword',
    'dtd'     => 'application/xml-dtd',
    'dvi'     => 'application/x-dvi',
    'dxr'     => 'application/x-director',
    'eps'     => 'application/postscript',
    'etx'     => 'text/x-setext',
    'exe'     => 'application/octet-stream',
    'ez'      => 'application/andrew-inset',
    'gif'     => 'image/gif',
    'gram'    => 'application/srgs',
    'grxml'   => 'application/srgs+xml',
    'gtar'    => 'application/x-gtar',
    'hdf'     => 'application/x-hdf',
    'hqx'     => 'application/mac-binhex40',
    'htm'     => 'text/html',
    'html'    => 'text/html',
    'ice'     => 'x-conference/x-cooltalk',
    'ico'     => 'image/x-icon',
    'ics'     => 'text/calendar',
    'ief'     => 'image/ief',
    'ifb'     => 'text/calendar',
    'iges'    => 'model/iges',
    'igs'     => 'model/iges',
    'jpe'     => 'image/jpeg',
    'jpeg'    => 'image/jpeg',
    'jpg'     => 'image/jpeg',
    'js'      => 'application/x-javascript',
    'kar'     => 'audio/midi',
    'latex'   => 'application/x-latex',
    'lha'     => 'application/octet-stream',
    'lzh'     => 'application/octet-stream',
    'm3u'     => 'audio/x-mpegurl',
    'man'     => 'application/x-troff-man',
    'mathml'  => 'application/mathml+xml',
    'me'      => 'application/x-troff-me',
    'mesh'    => 'model/mesh',
    'mid'     => 'audio/midi',
    'midi'    => 'audio/midi',
    'mif'     => 'application/vnd.mif',
    'mov'     => 'video/quicktime',
    'movie'   => 'video/x-sgi-movie',
    'mp2'     => 'audio/mpeg',
    'mp3'     => 'audio/mpeg',
    'mpe'     => 'video/mpeg',
    'mpeg'    => 'video/mpeg',
    'mpg'     => 'video/mpeg',
    'mpga'    => 'audio/mpeg',
    'ms'      => 'application/x-troff-ms',
    'msh'     => 'model/mesh',
    'mxu'     => 'video/vnd.mpegurl',
    'nc'      => 'application/x-netcdf',
    'oda'     => 'application/oda',
    'ogg'     => 'application/ogg',
    'pbm'     => 'image/x-portable-bitmap',
    'pdb'     => 'chemical/x-pdb',
    'pdf'     => 'application/pdf',
    'pgm'     => 'image/x-portable-graymap',
    'pgn'     => 'application/x-chess-pgn',
    'png'     => 'image/png',
    'pnm'     => 'image/x-portable-anymap',
    'ppm'     => 'image/x-portable-pixmap',
    'ppt'     => 'application/vnd.ms-powerpoint',
    'ps'      => 'application/postscript',
    'qt'      => 'video/quicktime',
    'ra'      => 'audio/x-pn-realaudio',
    'ram'     => 'audio/x-pn-realaudio',
    'ras'     => 'image/x-cmu-raster',
    'rdf'     => 'application/rdf+xml',
    'rgb'     => 'image/x-rgb',
    'rm'      => 'application/vnd.rn-realmedia',
    'roff'    => 'application/x-troff',
    'rss'     => 'application/rss+xml',
    'rtf'     => 'text/rtf',
    'rtx'     => 'text/richtext',
    'sgm'     => 'text/sgml',
    'sgml'    => 'text/sgml',
    'sh'      => 'application/x-sh',
    'shar'    => 'application/x-shar',
    'silo'    => 'model/mesh',
    'sit'     => 'application/x-stuffit',
    'skd'     => 'application/x-koan',
    'skm'     => 'application/x-koan',
    'skp'     => 'application/x-koan',
    'skt'     => 'application/x-koan',
    'smi'     => 'application/smil',
    'smil'    => 'application/smil',
    'snd'     => 'audio/basic',
    'so'      => 'application/octet-stream',
    'spl'     => 'application/x-futuresplash',
    'src'     => 'application/x-wais-source',
    'sv4cpio' => 'application/x-sv4cpio',
    'sv4crc'  => 'application/x-sv4crc',
    'svg'     => 'image/svg+xml',
    'svgz'    => 'image/svg+xml',
    'swf'     => 'application/x-shockwave-flash',
    't'       => 'application/x-troff',
    'tar'     => 'application/x-tar',
    'tcl'     => 'application/x-tcl',
    'tex'     => 'application/x-tex',
    'texi'    => 'application/x-texinfo',
    'texinfo' => 'application/x-texinfo',
    'tif'     => 'image/tiff',
    'tiff'    => 'image/tiff',
    'tr'      => 'application/x-troff',
    'tsv'     => 'text/tab-separated-values',
    'txt'     => 'text/plain',
    'ustar'   => 'application/x-ustar',
    'vcd'     => 'application/x-cdlink',
    'vrml'    => 'model/vrml',
    'vxml'    => 'application/voicexml+xml',
    'wav'     => 'audio/x-wav',
    'wbmp'    => 'image/vnd.wap.wbmp',
    'wbxml'   => 'application/vnd.wap.wbxml',
    'wml'     => 'text/vnd.wap.wml',
    'wmlc'    => 'application/vnd.wap.wmlc',
    'wmls'    => 'text/vnd.wap.wmlscript',
    'wmlsc'   => 'application/vnd.wap.wmlscriptc',
    'wrl'     => 'model/vrml',
    'xbm'     => 'image/x-xbitmap',
    'xht'     => 'application/xhtml+xml',
    'xhtml'   => 'application/xhtml+xml',
    'xls'     => 'application/vnd.ms-excel',
    'xml'     => 'application/xml',
    'xpm'     => 'image/x-xpixmap',
    'xsl'     => 'application/xml',
    'xslt'    => 'application/xslt+xml',
    'xul'     => 'application/vnd.mozilla.xul+xml',
    'xwd'     => 'image/x-xwindowdump',
    'xyz'     => 'chemical/x-xyz',
    'zip'     => 'application/zip'
  );
}


?>