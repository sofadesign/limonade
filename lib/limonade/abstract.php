<?php
/**
 * Abstract methods that might be redefined by user
 * Do not include this file in your app: it only aims to provide documentation
 * about those functions.
 * 
 * @package limonade
 * @subpackage abstract
 */
 
/**
 * It will be called when app is launched (at the begining og the run function).
 * You can define options inside it, a connection to a database ...
 *
 * @abstract this function might be redefined by user
 * @return void 
 */
function configure()
{
  return;
}

/**
 * Called in run() just after the route matching, in order to load controllers. 
 * If not specfied, the default function is called:
 * 
 * <code>
 * function autoload_controller($callback)
 * {
 *   require_once_dir(option('controllers_dir'));
 * }
 * </code>
 * 
 *
 * @param string $callback the callback deined in matching route
 * @return void
 */
function autoload_controller($callback)
{
  return;
}
 
/**
 * Called before each request.
 * This is very useful to define a default layout or passing common variables
 * to the templates.
 *
 * @abstract this function might be redefined by user
 * @param array() $route array (like returned by {@link route_build()},
 *   with keys "method", "pattern", "names", "function", "options")
 * @return void 
 */
function before($route)
{
  
}
 
/**
 * An `after` output filter
 * 
 * Called after each request and can apply a transformation to the output
 * (except for `render_file` outputs  which are sent directly to the output buffer).
 *
 * @abstract this function might be redefined by user
 * @param string $output 
 * @param array() $route array (like returned by {@link route_find()},
 *   with keys "method", "pattern", "names", "function", "params", "options")
 * @return string 
 */
function after($output, $route)
{
  # Call functions...
  # .. modifies $output...
  return $output;
}
 
/**
 * Not found error output
 *
 * @abstract this function might be redefined by user
 * @param string $errno 
 * @param string $errstr 
 * @param string $errfile 
 * @param string $errline 
 * @return string "not found" output string
 */
function not_found($errno, $errstr, $errfile=null, $errline=null)
{
 
}
 
/**
 * Server error output
 *
 * @abstract this function might be redefined by user
 * @param string $errno 
 * @param string $errstr 
 * @param string $errfile 
 * @param string $errline 
 * @return string "server error" output string
 */
function server_error($errno, $errstr, $errfile=null, $errline=null)
{
  
}
 
/**
 * Called when a route is not found.
 * 
 * 
 * @abstract this function might be redefined by user
 * @param string $request_method 
 * @param string $request_uri 
 * @return void 
 */
function route_missing($request_method, $request_uri)
{
  halt(NOT_FOUND, "($request_method) $request_uri"); # by default
}

/**
 * Called before stoppping and exiting application.
 *
 * @abstract this function might be redefined by user
 * @return void 
 */
function before_exit()
{
  
}

/**
 * Rendering prefilter
 * Useful if you want to transform your views before rendering.
 *
 * @abstract this function might be redefined by user
 * @param string $content_or_func a function, a file in current views dir or a string
 * @param string $layout 
 * @param array $locals 
 * @param array $view_path (by default <code>file_path(option('views_dir'),$content_or_func);</code>)
 * @return array with, in order, $content_or_func, $layout, $locals vars
 *  and the calculated $view_path
 */
function before_render($content_or_func, $layout, $locals, $view_path)
{
  # transform $content_or_func, $layout, $locals or $view_path…
  return array($content_or_func, $layout, $locals, $view_path);
}


/**
 * Called only if option('autorender') is enabled and rendering $output is_null,
 * like in a controller with no return statement.
 *
 * @abstract this function might be defined by user
 * @param array() $route array (like returned by {@link route_build()},
 *   with keys "method", "pattern", "names", "function", "options")
 * @return string
 */
function autorender($route)
{
  # process output depending on $route
  return $output;
}
