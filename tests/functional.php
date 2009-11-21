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
   
   function test_functional_routing()
   {
     $path = TESTS_DOC_ROOT.'03-routing.php/';
     $response =  test_request($path.'route0', 'GET');
     assert_equal($response, 'route0');
     $response =  test_request($path.'route1', 'GET');
     assert_equal($response, 'route1');
     $response =  test_request($path.'route2', 'GET');
     assert_equal($response, 10);
     $response =  test_request($path.'route3', 'GET');
     assert_equal($response, 20);
     $response =  test_request($path.'route4', 'GET');
     assert_equal($response, 20);
     
     $response =  test_request($path.'route5', 'GET');
     assert_equal($response, 'human');
     $response =  test_request($path.'route5b', 'GET');
      assert_equal($response, 'human');
     $response =  test_request($path.'route6', 'GET');
     assert_equal($response, 'human');
     $response =  test_request($path.'route6b', 'GET');
     assert_equal($response, 'human10');
     $response =  test_request($path.'route6c', 'GET');
     assert_equal($response, 'human10');
     
     $response =  test_request($path.'route7/123', 'GET');
     assert_equal($response, 123);
     $response =  test_request($path.'route7b/123', 'GET');
     assert_equal($response, 123);
     $response =  test_request($path.'route7c/123', 'GET');
     assert_equal($response, 1230);
     $response =  test_request($path.'route7d/123', 'GET');
     assert_equal($response, 1230);
     $response =  test_request($path.'route7e/123', 'GET');
     assert_equal($response, 2460);
     $response =  test_request($path.'route7f/123', 'GET');
     assert_equal($response, 2460);
     $response =  test_request($path.'route7g', 'GET');
     assert_equal($response, 200);
     $response =  test_request($path.'route7h', 'GET');
     assert_equal($response, 200);
     $response =  test_request($path.'route8/123', 'GET');
     assert_equal($response, 123);
     $response =  test_request($path.'route8b/123', 'GET');
     assert_equal($response, 123);
     $response =  test_request($path.'route9/123', 'GET');
     assert_equal($response, 2460);
     $response =  test_request($path.'route9b/123', 'GET');
     assert_equal($response, 2460);
     $response =  test_request($path.'route10/123', 'GET');
     assert_equal($response, 2460);
     $response =  test_request($path.'route10b/123', 'GET');
     assert_equal($response, 2460);
     
     /* http methods dispatching */
     $response =  test_request($path.'route11', 'GET');
     assert_equal($response, 'GET');
     $response =  test_request($path.'route11', 'POST');
     var_dump($response);
     assert_equal($response, 'POST');
     $response =  test_request($path.'route11', 'PUT');
     assert_equal($response, 'PUT');
     $response =  test_request($path.'route11', 'DELETE');
     assert_equal($response, 'DELETE');
     $response =  test_request($path.'route11', 'HEAD', true);
     assert_header($response, 'X-LIM-CTL', 'route11');
     
     /* undefined route */
     $response =  test_request($path.'unknown_route', 'GET');
     assert_match('/Page not found/', $response);     
     
   }
end_test_case();