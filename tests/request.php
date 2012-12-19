<?php

test_case("Request");
   test_case_describe("Testing limonade request functions.");
   
   function before_each_test_in_request()
   {
     env(null);
   }
   
   function test_request_methods()
   {
     $m = request_methods();
     assert_length_of($m, 6);
   }
   
   function test_request_method_is_allowed()
   {
     assert_true(request_method_is_allowed("GET"));
     assert_true(request_method_is_allowed("get"));
     assert_true(request_method_is_allowed("POST"));
     assert_true(request_method_is_allowed("PUT"));
     assert_true(request_method_is_allowed("DELETE"));
     assert_true(request_method_is_allowed("HEAD"));
     assert_true(request_method_is_allowed("PATCH"));
   }
   
   function test_request_method()
   {
     $env = env();
     $env['SERVER']['REQUEST_METHOD'] = null;
     
     assert_trigger_error("request_method");
     
     $methods = request_methods();
     
     foreach($methods as $method)
     {
       $env['SERVER']['REQUEST_METHOD'] = $method;
       assert_equal(request_method($env), $method);
     }
     
     $env['SERVER']['REQUEST_METHOD'] = "POST";
     
     $env['POST']['_method'] = "PUT";
     assert_equal(request_method($env), "PUT");
     
     $env['POST']['_method'] = "DELETE";
     assert_equal(request_method($env), "DELETE");
     
     $env['POST']['_method'] = "PATCH";
     assert_equal(request_method($env), "PATCH");

     $env['POST']['_method'] = "UNKOWN";
     assert_trigger_error('request_method', array($env));
     assert_false(request_method());
   }
   
   function test_request_uri()
   {
     # TODO test with webbrick + CGIHandler (http://microjet.ath.cx/webrickguide/html/CGIHandler.html)
     # TODO request_uri must be also tested in a browser...
     
     assert_equal(request_uri(), "/");
     $path = dirname(__FILE__)."/helpers/show_request_uri.php";
     $cmd = "php -f $path";
     
     assert_equal(exec($cmd, $res), "/");

     assert_equal(exec($cmd." test", $res), "/test");
     
     assert_equal(exec($cmd." /test", $res), "/test");
     
     assert_equal(exec($cmd." /my-test/", $res), "/my-test");
     
     assert_not_equal(exec($cmd." /my-test/?", $res), "/my-test");
      
     assert_not_equal(exec($cmd." /my-test?var=1", $res), "/my-test");

   }
   
end_test_case();
   