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
    
  function test_output_render()
  {
    $lorem = "Lorem ipsum dolor sit amet.";
    $q_lorem = preg_quote($lorem);
    
    # Testing standard rendering with sprint string
    assert_equal(render($lorem), $lorem);
    assert_equal(render($lorem, null, array('unused')), $lorem);
    assert_equal(render("Lorem %s dolor sit amet.", null, array('ipsum')), $lorem);
    assert_equal(render("Lorem %s dolor sit amet.", null, array('var1' => 'ipsum')), $lorem);
    
    $response =  test_request(URL_FOR_OUTPUT_TEST.'/render0', 'GET');
    assert_equal($response, $lorem);
    $response =  test_request(URL_FOR_OUTPUT_TEST.'/render1', 'GET');
    assert_equal($response, $lorem);
    
    # Testing rendering with a view (inline function case)
    $view = '_test_output_html_hello_world';
    $html = render($view);
    assert_match("/Hello World/", $html);
    assert_no_match("/$q_lorem/", $html);
    $html = render($view, null, array($lorem));
    assert_no_match("/$q_lorem/", $html);
    $html = render($view, null, array('lorem' => $lorem));
    assert_match("/$q_lorem/", $html);
    
    # Testing layout option
    $layout  = '_test_output_html_my_layout';
    $html    = render($lorem, $layout);
    assert_match("/$q_lorem/", $html);
    assert_match("/<title>Page title<\/title>/", $html);
    
    # Testing layout + view (inline function case)
    $html = render($view, $layout);
    assert_match("/<title>Page title<\/title>/", $html);
    assert_match("/Hello World/", $html);
    assert_no_match("/$q_lorem/", $html);
    $html = render($view, $layout, array('lorem' => $lorem));
    assert_match("/<title>Page title<\/title>/", $html);
    assert_match("/Hello World/", $html);
    assert_match("/$q_lorem/", $html);
    
    # Testing layout + view (template files case)
    $views_dir = dirname(__FILE__) . '/apps/views/';
    option('views_dir', $views_dir);

    $view      = 'hello_world.html.php';
    $layout    = 'layouts/default.html.php';
    $html = render($view, $layout);
    assert_match("/<title>Page title<\/title>/", $html);
    assert_match("/Hello World/", $html);
    assert_no_match("/$q_lorem/", $html);
    $html = render($view, $layout, array('lorem' => $lorem));
    assert_match("/<title>Page title<\/title>/", $html);
    assert_match("/Hello World/", $html);
    assert_match("/$q_lorem/", $html);
  }
  
  function test_output_layout()
  {
    $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/layout', 'GET');
    $o = <<<HTML
<html><body>
hello!</body></html>
HTML;
    assert_equal($response, $o);
    
    $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/layout2', 'GET');
    $o = <<<HTML
<html><body>
<p>my content</p>
<p>my sidebar</p>
</body></html>
HTML;
    assert_equal($response, $o);
  }
  
  function test_output_content_for()
  {
    $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/content_for', 'GET');
    $o = <<<HTML
<html><body>
<p>my content</p>
<p>my sidebar</p>
</body></html>
HTML;
    assert_equal($response, $o);
  }
  
  function test_output_partial()
  {
    $response =  test_request(TESTS_DOC_ROOT.'02-outputs.php/partial', 'GET');
    assert_equal($response, 'no layout there buddy');
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
  
  function test_output_before_filter()
  {
    function before_render($content_or_func, $layout, $locals, $view_path)
    {
      if(is_callable($content_or_func))
      {

      }
      elseif(file_exists($view_path) && !array_key_exists('content', $locals))
      {
        // a view file but not a layout
        $view_path = file_path(option('views_dir'), basename($content_or_func, ".html.php") . "_filtered.html.php");
      }
      else
      {
        # it's a string
        $content_or_func .= "∞FILTERED∞";
      }

      return array($content_or_func, $layout, $locals, $view_path);
    }
    $lorem = "Lorem ipsum dolor sit amet.";
    $html  = render("Lorem %s dolor sit amet.", null, array('ipsum'));
    assert_match("/$lorem∞FILTERED∞/", $html);
    
    $views_dir = dirname(__FILE__) . '/apps/views/';
    option('views_dir', $views_dir);
    $view   = 'hello_world.html.php';
    $layout = 'layouts/default.html.php';
    $html = render($view, $layout, array('lorem' => $lorem));
    assert_match("/FILTERED/i", $html);
    assert_match("/$lorem/", $html);
    
  }
  
  function test_output_autorender()
  {
    $response = test_request(TESTS_DOC_ROOT.'02-outputs.php/autorender', 'GET');
    assert_equal($response, 'AUTORENDERED OUTPUT for empty_controller');
  }
  
end_test_case();


# Views and Layouts

function _test_output_html_my_layout($vars){ extract($vars);?> 
<html>
<head>
	<title>Page title</title>
</head>
<body>
	<?php echo $content ?>
</body>
</html>
<?php }

function _test_output_html_hello_world($vars){ extract($vars);?> 
<p>Hello World</p>
<?php if(isset($lorem)): ?><p><?php echo $lorem?></p><?php endif;?>
<?php }
