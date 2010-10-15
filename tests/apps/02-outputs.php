<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

dispatch('/render0', 'test_render0');
function test_render0()
{
  return render("Lorem ipsum dolor sit amet.");
}

dispatch('/render1', 'test_render1');
function test_render1()
{
  return render("Lorem %s dolor sit amet.", null, array('ipsum'));
}

dispatch('/text', 'text_file');
function text_file()
{
  return render_file(dirname(dirname(__FILE__)).'/data/empty_text_file.txt');
}

dispatch('/jpeg', 'jpeg_file');
function jpeg_file()
{
  return render_file(dirname(dirname(__FILE__)).'/data/deer.jpg');
}

dispatch('/autorender', 'empty_controller');
function empty_controller()
{

}

function autorender($route){
  return "AUTORENDERED OUTPUT for ".$route['callback'];
}

run(); 