<?php
if(!defined('LIMONADE')){$h="HTTP/1.0 401 Unauthorized";header($h);die($h);}// Security check

test_case("Functional");
   test_case_describe("Functional tests");
   
   function before_each_test_in_functional()
   {
     env(null);
   }
   
   function test_functional_request()
   {
     $response =  test_request(TESTS_DOC_ROOT.'01-hello_world.php', 'GET', true);
     //echo $response;
     assert_header($response, 'Content-type', 'text/html');
     
   }
end_test_case();