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
     
     $response =  test_request(TESTS_DOC_ROOT.'03-routing.php/route0', 'GET', true);
     assert_header($response, 'X-Limonade', LIM_NAME);
   }
   
   function test_functional_session()
   {
     $response =  test_request(TESTS_DOC_ROOT.'06-session.php/', 'GET', false);
     // In http://www.php.net/manual/en/function.session-name.php:
     //
     // > The session name references the session id in cookies and URLs. It
     //   should contain only alphanumeric characters; it should be short and
     //   descriptive (i.e. for users with enabled cookie warnings). If name is
     //   specified, the name of the current session is changed to its value.
     assert_true(ctype_alnum($response));
     assert_equal($response, "LIMONADE".str_replace('.', 'x', LIMONADE));
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
     
     if(version_compare(PHP_VERSION, '5.3.0') >= 0)
     {
       $response =  test_request($path.'route-lambda', 'GET');
       assert_equal($response, 'LAMBDA CALL');
     }
     
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
     $response =  test_request($path.'route8c/10', 'GET');
     assert_equal($response, 5);
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
   
   function test_functional_params()
   {
     $path = TESTS_DOC_ROOT.'08-params.php';
     $response =  test_request($path, 'GET');
     assert_equal($response, 'HELLO');
     
     $response =  test_request($path.'?/&sort=asc', 'GET');
     assert_match('/sort=asc/', $response);
     
     $response =  test_request($path.'/books/fr', 'GET');
     assert_equal($response, 'lang=fr');
     
     $response =  test_request($path.'?uri=books/fr&sort=asc&page=2', 'GET');
     assert_match('/sort=asc/', $response);
     assert_match('/page=2/', $response);
   }
   
   
   function test_functional_errors()
   {
     $path = TESTS_DOC_ROOT.'04-errors.php/';
     $response =  test_request($path.'no-error', 'GET', true);
     assert_status($response, 200);
     $response =  test_request($path.'unknow____url', 'GET', true);
     assert_status($response, 404);
     $response =  test_request($path.'not_found', 'GET', true);
     assert_status($response, 404);
     $response =  test_request($path.'server_error', 'GET', true);
     assert_status($response, 500);
     
     $response =  test_request($path.'halt', 'GET', true);
     assert_status($response, 500);
     assert_no_match("/This shouldn't be outputed/", $response);
     
     $response =  test_request($path.'trigger_error', 'GET', true);
     assert_status($response, 500);
     assert_no_match("/This shouldn't be outputed/", $response);
     
     $response =  test_request($path.'trigger_error/E_USER_WARNING', 'GET', true);
     assert_status($response, 200);
     assert_no_match("/This should be seen/", $response);
     
     $response =  test_request($path.'trigger_error/E_USER_NOTICE', 'GET', true);
     assert_status($response, 200);
     assert_no_match("/This should be seen/", $response);
     
     $response =  test_request($path.'halt1234', 'GET', true);
     assert_status($response, 501);
     assert_match("/A personnal error #1234/", $response);
   }
   
   function test_functional_flash()
   {
     $path = TESTS_DOC_ROOT.'07-flash.php/';
     
     $ch = curl_init(); 
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
     curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE); 
     curl_setopt($ch, CURLOPT_HEADER, 0); 
     curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile"); 
     curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile"); 
     curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id()); 
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 

     curl_setopt($ch, CURLOPT_URL, $path); 
     $response  = curl_exec($ch); 
     assert_no_match("/ON DISPLAY/", $response);
     
     curl_setopt($ch, CURLOPT_URL, $path.'two'); 
     $response  = curl_exec($ch); 
     assert_match("/ON DISPLAY 2/", $response);

     # Run a HEAD request on a page where there is no new flash
     # message set. Previous flash message should still be
     # there after this request.
     curl_setopt($ch, CURLOPT_URL, $path.'four'); 
     curl_setopt($ch, CURLOPT_NOBODY, TRUE); 
     curl_setopt($ch, CURLOPT_HEADER, 1); 
     $response  = curl_exec($ch); 

     curl_setopt($ch, CURLOPT_NOBODY, FALSE); 
     curl_setopt($ch, CURLOPT_URL, $path.'three'); 
     $response  = curl_exec($ch); 
     assert_match("/ON DISPLAY 3/", $response);

     curl_setopt($ch, CURLOPT_URL, $path.'four'); 
     $response  = curl_exec($ch); 
     assert_match("/ON DISPLAY 4/", $response);
     assert_match("/NO FLASH MESSAGE ON NEXT PAGE/", $response);

     curl_setopt($ch, CURLOPT_URL, $path.'five'); 
     $response  = curl_exec($ch); 
     assert_match("/REDIRECTED FROM INDEX FIVE/", $response);
     assert_match("/ON DISPLAY 6/", $response);

     curl_setopt($ch, CURLOPT_URL, $path.'six'); 
     $response  = curl_exec($ch);
     assert_no_match("/ON DISPLAY/", $response);

     curl_setopt($ch, CURLOPT_URL, $path.'two'); 
     $response  = curl_exec($ch); 
     assert_no_match("/ON DISPLAY/", $response);

     curl_close($ch);
   }
end_test_case();
