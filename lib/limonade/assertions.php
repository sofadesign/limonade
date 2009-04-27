<?php
/**
 * @package tests
 * @subpackage assertions
 */
# ============================================================================ #
#    ASSERTIONS                                                                #
# ============================================================================ #

/**
 * assert_true
 *
 * @param string $value 
 * @param string $message 
 * @return boolean
 */
function assert_true($value, $message = '<1> should be TRUE')
{
   tests_execute_before_assert();
   return assert('$value === TRUE; //'.$message);
}

function assert_false($value, $message = '<1> should be FALSE')
{
   tests_execute_before_assert();
   return assert('$value === FALSE; //'.$message);
}

function assert_null($value, $message = '<1> should be NULL')
{
   tests_execute_before_assert();
   return assert('$value === NULL; //'.$message);
}

function assert_not_null($value, $message = '<1> should not be NULL')
{
   tests_execute_before_assert();
   return assert('$value !== NULL; //'.$message);
}

function assert_empty($value, $message = '<1> should be empty')
{
   tests_execute_before_assert();
   return assert('empty($value); //'.$message);
}

function assert_not_empty($value, $message = '<1> should not be empty')
{
   tests_execute_before_assert();
   return assert('!empty($value); //'.$message);
}

function assert_equal($expected, $value, $message = '<1> should be equal to <2>')
{
   tests_execute_before_assert();
   return assert('$expected == $value; //'.$message);
}

function assert_not_equal($expected, $value, $message = '<1> should not equal to <2>')
{
   tests_execute_before_assert();
   return assert('$expected != $value; //'.$message);
}

function assert_identical($expected, $value, $message = '<1> should be identical to <2>')
{
   tests_execute_before_assert();
   return assert('$expected === $value; //'.$message);
}

function assert_not_identical($expected, $value, $message = '<1> should not be identical to <2>')
{
   tests_execute_before_assert();
   return assert('$expected !== $value; //'.$message);
}

function assert_match($pattern, $string, $message = '<2> expected to match regular expression <1>') {
   tests_execute_before_assert();
   return assert('preg_match($pattern, $string); //'.$message);
}
 
function assert_no_match($pattern, $string, $message = '<2> expected to not match regular expression <1>') {
   tests_execute_before_assert();
   return assert('!preg_match($pattern, $string); //'.$message);
}

function assert_type($type, $value, $message = '<1> is not of type <2>') {
  tests_execute_before_assert();
  $predicate = 'is_' . strtolower(is_string($type) ? $type : gettype($type));
  return assert('$predicate($value); //'.$message);
}
 
function assert_instance_of($class, $object, $message = '<2> is not an instance of class <1>') {
   tests_execute_before_assert();
   return assert('$object instanceof $class; //'.$message);
}
 
function assert_length_of($value, $length, $message = '<1> expected to be of length <2>') {
   tests_execute_before_assert();
   $count = is_string($value) ? 'strlen' : 'count';
   return assert('$count($value) == $length; //'.$message);
}

function assert_trigger_error($callable, $args = array(), $message = '<1> should trigger an error') {
  tests_execute_before_assert();
  $trigger_errors = count($GLOBALS["limonade"]["tests_errors"]);
  set_error_handler("test_error_handler");
  $result = call_user_func_array($callable, $args);
  restore_error_handler();
  return assert('$trigger_errors < count($GLOBALS["limonade"]["tests_errors"]); //'.$message);
}

# TODO add web browser assertions assert_http_get, assert_http_response... as in SimpleTest (http://www.simpletest.org/en/web_tester_documentation.html)

?>