<?php
require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';


dispatch('/', 'index');
  function index()
  {
    $o = "HELLO";
    if(array_key_exists('sort', $_GET)) $o .= " | sort=" . $_GET['sort'];
    return $o;
  }
  
dispatch('/books/:lang', 'books');
  function books()
  {
    $o = "lang=" . params('lang');
    if(array_key_exists('sort', $_GET)) $o .= " | sort=" . $_GET['sort'];
    if(array_key_exists('page', $_GET)) $o .= " | page=" . $_GET['page'];
    return $o;
  }

dispatch_post('/books', 'create');
  function create()
  {
    $o = '';
    if(array_key_exists('title', $_POST)) $o = "title=" . $_POST['title'];
    return $o;
  }
  


run();
