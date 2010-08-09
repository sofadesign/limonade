<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

dispatch('/', 'content_negociation');

function content_negociation()
{
  //return var_dump($_SERVER['HTTP_ACCEPT']);
  if(http_ua_accepts('json'))
  {
    return "json" ;
  }
  else if (http_ua_accepts('html'))
  {
    return "<h1>HTML</h1>" ;
  }
  else
  {
    return 'Oops' ;
  }
}

run();