<?php
/**
 * @package tests
 */
 
# ============================================================================ #
#    TESTS                                                                     #
# ============================================================================ #

/**
 * load assertions
 */
require_once dirname(__FILE__)."/assertions.php";


 
/**
 * Constants and globals
 */
if(!defined('DS')) define("DS", DIRECTORY_SEPARATOR);

if(!array_key_exists("limonade", $GLOBALS))
   $GLOBALS["limonade"] = array();
if(!array_key_exists("tests", $GLOBALS["limonade"]))
   $GLOBALS["limonade"]["tests"] = array();
if(!array_key_exists("tests_errors", $GLOBALS["limonade"]))
   $GLOBALS["limonade"]["tests_errors"] = array();
if(!array_key_exists("tests_current", $GLOBALS["limonade"]))
   $GLOBALS["limonade"]["tests_current"] = NULL;
if(!array_key_exists("tests_all", $GLOBALS["limonade"]))
   $GLOBALS["limonade"]["tests_all"] = NULL;

ini_set("display_errors", true);
error_reporting(E_ALL ^ (E_USER_WARNING | E_NOTICE | E_USER_NOTICE));
// error_reporting(E_ALL | E_STRICT);
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_BAIL, 0);
assert_options(ASSERT_QUIET_EVAL, 0);
assert_options(ASSERT_CALLBACK, 'test_assert_failure');

# TODO: separate display from logic
# TODO: clean results output
# TODO: add all tests results

/**
 * Starts a group of tests suites
 *
 * @param string $name 
 * @return void
 */
function tests_all($name)
{
  $GLOBALS["limonade"]["tests_all"] = $name;
  echo "===========================================================\n";
  echo ">>>> START $name tests suites\n";
  echo "-----------------------------------------------------------\n";
}

/**
 * Ends the last group of tests_suites
 *
 * @return void
 */
function endtests_all()
{
  $name = $GLOBALS["limonade"]["tests_all"];
  $failures = 0;
  $tests = 0;
  $passed_tests = 0;
  $assertions = 0;
  foreach($GLOBALS["limonade"]["tests"] as $test)
  {
    $failures += $test['failures'];
    $assertions += $test['assertions'];
    if(empty($test['failures'])) $passed_tests++;
    $tests++;
  }
  echo ">> ENDING $name tests suites\n  ";
  echo $failures > 0 ? "|FAILED!| " : "|PASSED| ";
  echo "Passes ".$passed_tests."/".$tests.", ";
  echo " {$failures} failures for {$assertions} assertions.\n";
  echo "===========================================================\n";
}

/**
 * Starting a new tests suite
 *
 * @param string $name 
 * @return void
 */
function tests($name)
{
   $name = strtolower($name); // TODO: normalize name
   
   if(!array_key_exists($name, $GLOBALS["limonade"]["tests"]))
   {
      $GLOBALS["limonade"]["tests"][$name] = array( 
                                                      "name" => $name,
                                                      "assertions" => 0, 
                                                      "failures" => 0,
                                                      "description" => NULL
                                                   );      
      $GLOBALS["limonade"]["tests_current"] = $name;
   }
   else
   {
      
   }
}

/**
 * Describes the current tests suite
 *
 * @param string $msg 
 * @return string tests description
 */
function tests_describe($msg = NULL)
{
   $test =& tests_current();
   if(!is_null($msg))
   {
      $test["description"] = $msg;
   }
   //var_dump($test["description"]);
   return $test["description"];
}

/**
 * Displays and ending the current tests suite
 * 
 * @return void
 */
function endtests()
{
   $name = $GLOBALS["limonade"]["tests_current"];
   echo "## ".strtoupper($name)."\n";
      
   $desc = tests_describe();
   if(!is_null($desc)) echo $desc."\n";

   echo "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
   
   tests_execute_current();
   
   
   if(!is_null($name))
   {
      $test = $GLOBALS["limonade"]["tests"][$name];
      // closing previous test
      echo "\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
      echo $test['failures'] > 0 ? "|FAILED!|" : "|PASSED|";
      echo " Tests suite '$name' finished: ";
      echo count(tests_all_func())." tests, ";
      echo " {$test['failures']} failures for {$test['assertions']} assertions.\n";
      
      echo "-----------------------------------------------------------\n";
   }
   $GLOBALS["limonade"]["tests_current"] = null;
}

/**
 * Returns all user test functions
 *
 * @access private
 * @return void
 */
function tests_all_func()
{
   $functions = get_defined_functions();
   $functions = $functions['user'];
   $tests = array();
   $name = $GLOBALS["limonade"]["tests_current"];
   while ($func = array_shift($functions)) {
      $regexp = "/^test_{$name}_(.*)$/";
      if(!preg_match($regexp, $func)) continue;
      if($func == tests_before_func()) continue;
      // TODO: adding break for all test api methods
      
      $tests[] = $func;
   }
   return $tests;
}

function tests_execute_current()
{
   $tests = tests_all_func();
   while($func = array_shift($tests))
   {
      tests_execute_before_test();
      call_user_func($func);
   }
}

function &tests_current()
{
   $name = $GLOBALS["limonade"]["tests_current"];
   return $GLOBALS["limonade"]["tests"][$name];
}



function tests_before_func()
{
   $test = tests_current();
   $name = $test["name"];
   $func = "tests_before_each_test_in_$name";
   if(function_exists($func)) return $func;
   return null;
}

function tests_execute_before_test()
{
   $func = tests_before_func();
   if(!is_null($func)) call_user_func($func);
}

function tests_before_assert_func()
{
   $test = tests_current();
   $name = $test["name"];
   $func = "tests_before_each_assert_in_$name";
   if(function_exists($func)) return $func;
   return null;
}

function tests_execute_before_assert()
{
   test_inc_assertions();
   $func = tests_before_assert_func();
   if(!is_null($func)) call_user_func($func);
}

function test_inc_assertions()
{
   $name = $GLOBALS["limonade"]["tests_current"];
   $GLOBALS["limonade"]["tests"][$name]['assertions']++;
}

/**
 * Error handler 
 * 
 * @access private
 * @return boolean true
 */
function test_error_handler($errno, $errstr, $errfile, $errline)
{
	if($errno < E_USER_ERROR || $errno > E_USER_NOTICE) 
	   echo "!!! ERROR [$errno], $errstr in $errfile at line $errline\n";
	$GLOBALS["limonade"]["tests_errors"][] = array($errno, $errstr, $errfile, $errline);
   return true;
}

/**
 * Assert callback
 * 
 * @access private
 * @param string $script 
 * @param string $line 
 * @param string $message 
 * @return void
 */
function test_assert_failure($script, $line, $message)
{
   // Using the stack trace, find the outermost assert*() call
   $stacktrace = array_slice(debug_backtrace(), 1); // skip self
   $assertion = reset($stacktrace);
   while ($stackframe = array_shift($stacktrace)) {
    if (!preg_match('/^assert/', $stackframe['function']))
      break;
    $assertion = $stackframe;
   }

   extract($assertion, EXTR_PREFIX_ALL, 'assert');
   $code = explode("\n", file_get_contents($assert_file));
   $code = trim($code[$assert_line - 1]);
   
   list($assert_code, $message) = explode("//", $message);
   echo "# Assertion failed in script *{$assert_file}* (line {$assert_line}):\n";
   echo "   * assertion: $code\n";
   echo "   * message:   $message\n";
   $name = $GLOBALS["limonade"]["tests_current"];
   $GLOBALS["limonade"]["tests"][$name]['failures']++;
}

?>