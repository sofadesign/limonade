<?php

test_case("Test");

   test_case_describe( "Testing test and assertions functions.\n". 
                       "Must run first, before all other tests."   );

    function before_each_test_in_test()
    {
       // echo "// test_before_test(): executed before each test\n";
       global $val;
       $val = NULL;
    }
   
   function before_each_assert_in_test()
   {
      // echo "// test_before_test(): executed before each test\n";
      global $val;
      $val = 10;
   }
   
   // fake function for test purpose
   function my_triggering_error_func($trigger)
   {
      if($trigger) trigger_error("Mmmm... error", E_USER_ERROR);
   }
   
   // tests
   
   function test_test_simple_assertions()
   {
      assert_true(true);
      assert_false(false);
      assert_equal("aaa", "aaa");
      assert_not_equal("aaas", "aaa");
   }
   
   function test_test_before_each_test_method()
   {
      global $val;
      assert_null($val);
   }
   
   function test_test_before_each_assert_method()
   {
      global $val;
      assert_null($val);
      $val = 400;
      assert_true(true); // run before_each_assert_in_test() first
      // so now 
      assert_equal($val, 10, '$val should be 10, not 400');
   }

   function test_test_trigger_error()
   {
      assert_trigger_error("my_triggering_error_func", array(true));
   }
   
   function test_test_assert_request()
   {
     assert_true(defined('TESTS_DOC_ROOT'), "Undefined 'TESTS_DOC_ROOT' constant");
     $response =  test_request(TESTS_DOC_ROOT.'00-empty.php', 'GET', true);
     assert_header($response, 'Content-type');
     assert_header($response, 'Content-type', 'text/html');
     assert_header($response, 'Content-Type');
     assert_header($response, 'Content-Type', 'text/html');
   }
   
end_test_case();