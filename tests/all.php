<?php
require_once dirname(dirname(__FILE__)).'/lib/limonade.php';
require_once dirname(dirname(__FILE__)).'/lib/limonade/tests.php';

$basedir = dirname(__FILE__).DS;

tests_all('Limonade');
  require $basedir."tests.php";
  require $basedir."router.php";
  require $basedir."request.php";
  require $basedir."main.php";
endtests_all();

?>