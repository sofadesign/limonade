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
 * Called before each request.
 * This is very useful to define a default layout or passing common variables
 * to the templates.
 *
 * @abstract this function might be redefined by user
 * @return void 
 */
function before()
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
 * @return string 
 */
function after($output)
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
