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

dispatch('/layout', 'layout_example');
function layout_example()
{
  layout('html_default_layout');
  return render('hello!');
}

dispatch('/layout2', 'layout_example2');
function layout_example2()
{
  layout('html_default_layout');
  return render('html_default_view');
}

dispatch('/content_for', 'content_for_example');
function content_for_example()
{
  return render('html_default_view', 'html_default_layout');
}

dispatch('/partial', 'partial_example');
function partial_example()
{
  layout('html_default_layout');
  return partial('no layout there %s', array('buddy'));
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


# _INLINE templates___________________________________________________________
  
function html_default_layout($vars){ extract($vars);?>
<html><body>
<?php echo $content; ?>
<?php if(isset($side)) echo $side; ?>
</body></html><?php };

function html_default_view($vars){ extract($vars);?>
<p>my content</p>
<?php content_for('side');?>
<p><?php echo 'my'; ?> sidebar</p>
<?php end_content_for();?>
<?php };
