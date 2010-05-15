<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

dispatch('/no-error', 'test_no_error');
function test_no_error()
{
  return "No error";
}

dispatch('/not_found', 'test_not_found');
function test_not_found()
{
  return halt(NOT_FOUND);
}

dispatch('/server_error', 'test_server_error');
function test_server_error()
{
  return halt(SERVER_ERROR);
}

dispatch('/halt', 'test_halt');
function test_halt()
{
  halt(SERVER_ERROR);
  return "This shouldn't be outputed.";
}

dispatch('/trigger_error/:level', 'test_trigger_error');
function test_trigger_error($level = 'E_USER_ERROR')
{
  $level = strtoupper($level);
  switch($level)
  {
    case 'E_USER_WARNING':
    $err = E_USER_WARNING;
    $body = "This should be outputed.";
    break;
    
    case 'E_USER_NOTICE':
    $err = E_USER_NOTICE;
    $body = "This should be outputed.";
    break;    
    
    default:
    $err = E_USER_ERROR;
    $body = "This shouldn't be outputed.";
    break;
  }
  trigger_error("Error !", $err);
  return $body;
}

dispatch('/halt1234', 'test_halt1234');
function test_halt1234()
{
  halt(1234);
  return "This shouldn't be outputed.";
}

error(1234, 'my_1234_error_handler');
function my_1234_error_handler($errno, $errstr, $errfile, $errline)
{
  status(501);
  echo "A personnal error #$errno";
}


run();