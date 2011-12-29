<?php
if(!defined('LIMONADE')){$h="HTTP/1.0 401 Unauthorized";header($h);die($h);}// Security check

test_case("HTTP");
   test_case_describe("Testing limonade HTTP utils functions.");
   
   function test_http_response_status_code()
   {
     $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/render0', 'GET', true);
     assert_match("/HTTP\/1\./", $response);
     assert_status($response, 200);
   }
   
   function test_http_ua_accepts()
   {
     $env = env();

     $env['SERVER']['HTTP_ACCEPT'] = null;
     assert_true(http_ua_accepts('text/plain'));

     $env['SERVER']['HTTP_ACCEPT'] = 'text/html';
     assert_true(http_ua_accepts('html'));

     $env['SERVER']['HTTP_ACCEPT'] = 'text/*; application/json';     
     assert_true(http_ua_accepts('html'));
     assert_true(http_ua_accepts('text/html'));
     assert_true(http_ua_accepts('text/plain'));
     assert_true(http_ua_accepts('application/json'));
     
     assert_false(http_ua_accepts('image/png'));
     assert_false(http_ua_accepts('png'));
     
     assert_true(defined('TESTS_DOC_ROOT'), "Undefined 'TESTS_DOC_ROOT' constant");
     
     $response =  test_request(TESTS_DOC_ROOT.'05-content_negociation.php', 'GET', false, array(), array("Accept: image/png"));
     assert_equal("Oops", $response);
     
     $response =  test_request(TESTS_DOC_ROOT.'05-content_negociation.php', 'GET', false, array(), array("Accept: text/html"));
     assert_equal("<h1>HTML</h1>", $response);
     
     $response =  test_request(TESTS_DOC_ROOT.'05-content_negociation.php', 'GET', false, array(), array("Accept: application/json"));
     assert_equal("json", $response);
	 }
	 
	 function test_http_redirect()
	 {
		 $url = TESTS_DOC_ROOT.'09-redirect.php?/';
		 
		 $response = test_request($url, 'GET');
		 assert_equal($response, '/redirected');
		 
		 $response = test_request($url.'&key1=value1', 'GET');
		 assert_equal($response, '/redirected&key1=value1');
		 
		 $response = test_request($url.'&key1=value1&key2=value2', 'GET');
		 assert_equal($response, '/redirected&key1=value1&key2=value2');
   }

end_test_case();
