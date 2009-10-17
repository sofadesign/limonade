<?php

test_case("Output");
  test_case_describe("Testing limonade output functions.");
  if(!defined('URL_FOR_OUTPUT_TEST')) 
    define('URL_FOR_OUTPUT_TEST', TESTS_DOC_ROOT.'02-outputs.php');
  
  function before_each_test_in_output()
  {
    env(null);
    option('encoding', 'utf-8');
    
  }
  
  function test_output_layout()
  {
    
  }
  
  function test_output_render()
  {
    $response =  test_request(URL_FOR_OUTPUT_TEST.'/render0', 'GET');
    assert_equal($response, "Lorem ipsum dolor sit amet.");
    
    $response =  test_request(URL_FOR_OUTPUT_TEST.'/render1', 'GET');
    assert_equal($response, "Lorem % ipsum dolor sit amet.");
  }
  
  function test_output_html()
  {
    
  }
  
  function test_output_render_file()
  {
    $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/text', 'GET', true);
    assert_header($response, 'Content-type', 'text/plain; charset='.option('encoding'));

    $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/jpeg', 'GET', true);
    assert_header($response, 'Content-type', 'image/jpeg');
    
    $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/unknown_page', 'GET', true);
    assert_header($response, 'Content-type', 'text/html; charset='.option('encoding'));
  }
  
  
  
end_test_case();