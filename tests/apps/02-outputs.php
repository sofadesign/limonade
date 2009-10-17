<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

dispatch('/', 'index');
function index()
{
  return render_file(dirname(dirname(__FILE__)).'/data/empty_text_file.txt');
}

dispatch('/jpeg', 'jpeg_file');
function jpeg_file()
{
  return render_file(dirname(dirname(__FILE__)).'/data/deer.jpg');
}

run(); 