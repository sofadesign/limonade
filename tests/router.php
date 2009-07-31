<?php

test_case("Router");
   test_case_describe("Testing limonade router functions.");
   
   function before_each_test_in_router()
   {
     route_reset();
   }
   
   function test_router_build_route()
   {
      assert_trigger_error('route_build', array('UNKOWN','/','aaa'));
      
      /* testing route returned array */
      $r = route_build("GET","/index", 'get_index');
      assert_equal($r["method"], "GET");
      assert_equal($r["pattern"], "#^/index(?:/*?)?$#i");
      assert_empty($r["names"]);
      assert_equal($r["function"], "get_index");
      
      /* testing very simple route with no parameters */
      assert_match($r["pattern"], "/index");
      assert_no_match($r["pattern"], "/other");
      assert_no_match($r["pattern"], "/");
      assert_no_match($r["pattern"], "/index/1");
      
      /* testing empty route */
      $r = route_build("GET","/", 'get_index');
      assert_match   ($r["pattern"], "/");
      assert_match   ($r["pattern"], "");
      assert_no_match($r["pattern"], "/test2");
      
      $r = route_build("GET","", 'get_index');
      assert_match   ($r["pattern"], "/");
      assert_match   ($r["pattern"], "");
      assert_no_match($r["pattern"], "/test2");
      
      /* testing single asterisk routes */
      $r = route_build("GET","/test/*", 'get_index');
      assert_match   ($r["pattern"], "/test");
      assert_match   ($r["pattern"], "/Test");
      assert_match   ($r["pattern"], "/test/");
      assert_match   ($r["pattern"], "/test/truc");
      assert_match   ($r["pattern"], "/test/truc/");
      assert_match   ($r["pattern"], "/test/truc////");
      assert_no_match($r["pattern"], "/test/truc/2");
      assert_no_match($r["pattern"], "/test2");
      assert_equal   ($r["names"][0], 0);
      
      preg_match($r["pattern"], "/test/foo////", $matches);
      assert_length_of($matches, 2);
      assert_equal($matches[1], "foo");
      
      $r = route_build("GET","/test/*/two", 'get_index');
      assert_match   ($r["pattern"], "/test/truc/two");
      assert_match   ($r["pattern"], "/test/truc/two/");
      assert_no_match($r["pattern"], "/test");
      assert_no_match($r["pattern"], "/test/");
      assert_no_match($r["pattern"], "/test/truc");
      assert_no_match($r["pattern"], "/test/truc/2");
      assert_no_match($r["pattern"], "/test2");
      assert_no_match($r["pattern"], "/test/truc/2/two");
      assert_no_match($r["pattern"], "/test/truc/two/three");
      assert_equal   ($r["names"][0], 0);
      
      preg_match($r["pattern"], "/test/foo/two/", $matches);
      assert_length_of($matches, 2);
      assert_equal($matches[1], "foo");
      
      /* testing single asterisk routes with params names */
      $r = route_build("GET",array("/test/*/two", array("first")), 'get_index');
      assert_match   ($r["pattern"], "/test/truc/two");
      assert_match   ($r["pattern"], "/test/truc/two/");
      assert_equal   ($r["names"][0], "first");
      
      /* testing double asterisk routes */
      $r = route_build("GET","/test/**", 'get_index');
      assert_match   ($r["pattern"], "/test");
      assert_match   ($r["pattern"], "/TEST");
      assert_match   ($r["pattern"], "/test/");
      assert_match   ($r["pattern"], "/test/truc");
      assert_match   ($r["pattern"], "/test/truc/2");
      assert_no_match($r["pattern"], "/test2");
      assert_equal   ($r["names"][0], 0);
      
      preg_match($r["pattern"], "/test/foo", $matches);
      assert_length_of($matches, 2);
      assert_equal($matches[1], "foo");
      
      $r = route_build("GET","/test/**/two/", 'get_index');
      assert_match   ($r["pattern"], "/test/truc/two");
      assert_match   ($r["pattern"], "/test/truc/one/two");
      assert_match   ($r["pattern"], "/Test/truc/one/two/");
      assert_no_match($r["pattern"], "/test");
      assert_no_match($r["pattern"], "/test/");
      assert_no_match($r["pattern"], "/test/truc");
      assert_no_match($r["pattern"], "/test/truc/2");
      assert_no_match($r["pattern"], "/test2");
      assert_no_match($r["pattern"], "/test/truc/one/two/three");
      
      preg_match($r["pattern"], "/test/foo/bar/two", $matches);
      assert_length_of($matches, 2);
      assert_equal($matches[1], "foo/bar");
      
      /* testing named parameters routes */
      $r = route_build("GET","/test/:bob", 'get_index');
      assert_match   ($r["pattern"], "/test");
      assert_match   ($r["pattern"], "/test/");
      assert_match   ($r["pattern"], "/test/truc");
      assert_no_match($r["pattern"], "/test/truc/2");
      assert_no_match($r["pattern"], "/test2");
      assert_equal   ($r["names"][0], "bob");
      
      /* testing regexp route */
      $r = route_build("GET","^/my/(\d+)/own/regexp", 'get_index');
      assert_match   ($r["pattern"], "/my/12/own/regexp");
      
      /* testing a complex route and parameters names*/
      $r = route_build("GET","/test/:my/*/complex/**/:route", 'get_index');
      assert_match   ($r["pattern"], "/test/my/first/complex/very-big/route/69");
      assert_no_match($r["pattern"], "/test/truc/2");
      assert_equal   ($r["names"][0], "my");
      assert_equal   ($r["names"][1], 1);
      assert_equal   ($r["names"][2], "2");
      assert_equal   ($r["names"][3], "route");
      
      /* testing typical route used for static files */
      $r = route_build("GET","/*.jpg/:size", 'get_index');
      assert_match   ($r["pattern"], "/limonade.jpg");
      assert_match   ($r["pattern"], "/limonade.jpg/");
      assert_match   ($r["pattern"], "/limonade.jpg/thumb");
      
      /* testing a complex route and parameters names*/
      $path = "/test/:my/*/complex/**/:route";
      $params = array("mmy","second", "lazy", null);
      
      $r = route_build("GET",array($path, $params), 'get_index');
      assert_match   ($r["pattern"], "/test/my/first/complex/very-big/route/69");
      assert_no_match($r["pattern"], "/test/truc/2");
      assert_equal   ($r["names"][0], "mmy");
      assert_equal   ($r["names"][1], "second");
      assert_equal   ($r["names"][2], "lazy");
      assert_equal   ($r["names"][3], "route");
   }
   
   function test_router_route()
   {
     assert_empty(route());
     
     $r = route("get", "/index", "my_func");
     assert_length_of($r, 1);
     assert_length_of($r[0], 4);
     assert_equal($r[0]["method"], "GET");
     assert_equal($r[0]["pattern"], "#^/index(?:/*?)?$#i");
     assert_empty($r[0]["names"]);
     assert_equal($r[0]["function"], "my_func");
     
     $r = route("put", "/blog/:id", "my_update_func");
     assert_length_of($r, 2);
     assert_length_of($r[1], 4);
     assert_equal($r[1]["method"], "PUT");
     assert_match($r[1]["pattern"], "/blog/102");
     assert_length_of($r[1]["names"], 1);
     assert_equal($r[1]["names"][0], "id");
     assert_equal($r[1]["function"], "my_update_func");
   }
   
   function test_router_find_route()
   {
               route( "get",    "/index",      "my_index_func"  );
               route( "get",    "/new",        "my_new_func"    );
               route( "post",   "/create",     "my_create_func" );
               route( "get",    "/edit/:id",   "my_edit_func"   );
               route( "put",    "/update/:id", "my_update_func" );
               route( "delete", "/delete/:id", "my_delete_func" );
               route( "get",    "^/list/(\d+)","my_list_func"   );
     $routes = route( "get",    "/*.jpg/:size","my_jpeg"        );
     
     assert_length_of($routes, 8);
     
     $r = route_find("GET", "/unkown");
     assert_false($r);
     
     $r = route_find("GET", "/delete");
     assert_false($r);
     
     $r = route_find("POST", "/create");
     assert_equal($r["function"], "my_create_func");
     
     $r = route_find("GET", "/edit");
     assert_equal($r["function"], "my_edit_func");
     
     $r = route_find("GET", "/edit/120");
     assert_equal($r["function"], "my_edit_func");
     assert_equal($r["params"]["id"], 120);
     
     $r = route_find("GET","/limonade.jpg/thumb", 'my_jpeg');
     
     assert_equal($r["function"], "my_jpeg");
     assert_equal($r["params"][0], "limonade");
     assert_equal($r["params"]["size"], "thumb");
     
     route( "get", "/index/*", "my_index_func2"  );
     $routes = route( "delete", "/delete/:id/:confirm", "my_delete_func2" );
     
     assert_length_of($routes, 10);
     $r = route_find("GET", "/index");
     assert_equal($r["function"], "my_index_func");
     
     $r = route_find("GET", "/index/ok");
     assert_equal($r["function"], "my_index_func2");
     
     $r = route_find("DELETE", "/delete");
     assert_equal($r["function"], "my_delete_func");
     
     $r = route_find("DELETE", "/delete/120");
     assert_equal($r["function"], "my_delete_func");
     
     $r = route_find("DELETE", "/delete/120/ok");
     assert_equal($r["function"], "my_delete_func2");
     
     $r = route_find("GET", "/list/120");
     assert_equal($r["function"], "my_list_func");
     
   }
   
end_test_case();