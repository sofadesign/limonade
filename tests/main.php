<?php
if(!defined('LIMONADE')){$h="HTTP/1.0 401 Unauthorized";header($h);die($h);}// Security check

test_case("Main");
   test_case_describe("Testing limonade main functions.");
   
   function before_each_test_in_main()
   {
     env(null);
   }
   
   function test_main_option()
   {
     assert_true(is_array(option()));
     assert_true(is_array(option(null)));
     assert_empty(option());
     $my_first_option = option('my_first_option');
     assert_true(empty($my_first_option));
     assert_not_equal(option('my_first_option', 'my first value'), 123);
     assert_true(is_string(option('my_first_option')));
     assert_equal(option('my_first_option'), 'my first value');
     assert_equal(option('my_first_option'), 'my first value');
     assert_true(is_array(option('my_first_option', 123, 456)));
     $my_first_option = option('my_first_option');
     assert_equal($my_first_option[0], 123);
     assert_equal($my_first_option[1], 456);
   }
   
   function test_main_params()
   {
     assert_empty(params());
     assert_empty(params(null));
     assert_true(is_array(params()));
     
     assert_equal(params('first', 6), 6);
     assert_equal(params('first'), 6);
     assert_true(is_array(params()));
     assert_equal(params('first', 12), 12);
     assert_length_of(params(), 1);
     
     params('my_array', 1, 2, 3, 4);
     assert_true(is_array(params('my_array')));
     assert_length_of(params('my_array'), 4);
     
     assert_true(is_array(params()));
     assert_length_of(params(), 2);
     
     params(array('zero','one'));
     assert_length_of(params(), 4);
     assert_equal(params(0), 'zero');
     assert_equal(params(1), 'one');
     
     params(array(2 => 'two', 'first' => 'my one'));
     assert_length_of(params(), 5);
     assert_equal(params(2), 'two');
     assert_equal(params('first'), 'my one');
     
     assert_empty(params(null));
   }
   
   function test_main_env()
   {
     $env = env();
     assert_true(is_array($env));
     $vars = request_methods();
     $vars[] = "SERVER";
     foreach($vars as $var)
     {
       assert_true(array_key_exists($var, $env));
       assert_true(is_array($env[$var]));
     }
     
     $_POST['_method'] = "PUT";
     $_POST['my_var1'] = "value1";
     $_POST['my_var2'] = "value2";
     
     $env = env(null);
     assert_equal($env['PUT']['my_var1'], "value1");
     assert_equal($env['PUT']['my_var2'], "value2");
   }
   
   function test_main_app_file()
   {
     $app_file = app_file();
     $env = env();
     assert_equal($app_file, $env['SERVER']['PWD'].'/'.$env['SERVER']['PHP_SELF']);
   }
   
   function test_main_call_if_exists()
   {
     assert_empty(call_if_exists("unknown_function"));
     assert_equal(call_if_exists("count", array(1,2,3)), 3);
     assert_length_of(call_if_exists("array_merge", array(1,2,3), array(4,5,6)), 6);
   }
   
   function test_main_define_unless_exists()
   {
     assert_false(defined('MY_SPECIAL_CONST'));
     define_unless_exists('MY_SPECIAL_CONST', "special value");
     assert_equal(MY_SPECIAL_CONST, "special value");
     define_unless_exists('MY_SPECIAL_CONST', "an other value");
     assert_not_equal(MY_SPECIAL_CONST, "an other value");
     assert_equal(MY_SPECIAL_CONST, "special value");
   }
   
   function test_main_require_once_dir()
   {
     $root = dirname(dirname(__FILE__));
     
     ob_start();
     assert_empty(require_once_dir($root));
     $files = require_once_dir($root, "AUTHORS");
     ob_clean();
     
     assert_length_of($files, 1);
     assert_match('/AUTHORS$/', $files[0]);
     
     $lib = $root.'/lib';
     $limonade = $lib.'/limonade';
     
     $files = require_once_dir($limonade);
     assert_not_empty($files);
     
     $tests_lib = $root.'/tests/data/lib0';
     $libs = array('a', 'b', 'c');
     foreach($libs as $lib) assert_false(defined('TEST_LIB_'.strtoupper($lib)));

     $files = require_once_dir($tests_lib);
     assert_not_empty($files);
     assert_length_of($files, 3);
     
     foreach($libs as $lib) assert_true(defined('TEST_LIB_'.strtoupper($lib)));
     
     assert_empty(require_once_dir($root.'/tests/data/'));
     assert_true(is_array(require_once_dir($root.'/tests/data/')));
     
     assert_empty(require_once_dir($root.'/tests/data/unknown_dir'));
     assert_true(is_array(require_once_dir($root.'/tests/data/unknown_dir')));
   }
   
   function test_main_value_or_default()
   {
     assert_equal(value_or_default(10, 20), 10);
     assert_equal(value_or_default(0, 20), 20);
     assert_equal(value_or_default(null, 20), 20);
     assert_equal(value_or_default('hello', 'world'), 'hello');
     assert_equal(value_or_default('', 'world'), 'world');
     assert_equal(value_or_default(10, 20), v(10, 20));
     assert_equal(value_or_default(0, 20), v(0, 20));
   }
   
   function test_main_url_for()
   {
     assert_equal(url_for(''), '/');
     assert_equal(url_for('/'), '/');
     assert_equal(url_for('test'), '/test');
     assert_equal(url_for('test', 1, 2), '/test/1/2');
     assert_equal(url_for('one', 'two', 'three'), '/one/two/three');
     assert_equal(url_for('my/hash#test'), '/my/hash#test');
     $site_url = 'http://limonade.sofa-design.net';
     assert_equal(url_for($site_url), $site_url);
     assert_equal(url_for($site_url, 'examples'), $site_url.'/examples');
     $ssite_url = 'https://limonade.sofa-design.net';
     assert_equal(url_for($ssite_url), $ssite_url);
   }
   
end_test_case();