<?php
# Autorendering example

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

function configure()
{
  option('env', ENV_DEVELOPMENT);
  option('autorender', true);
}

dispatch('/', 'index');
function index()
{
  return "is rendering something";
}

dispatch('/no', 'no_output');
function no_output()
{
  // rendering nothing;
  // return null;
}


function autorender($route)
{
  // check $route['function'] and call the matching view to render
  return "My view called by autorendering.";
}

run();