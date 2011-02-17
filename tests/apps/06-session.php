<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

dispatch('/', 'test');
function test()
{
  return render(session_name());
}

run();
