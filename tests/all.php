<?php
require_once dirname(__DIR__).'/lib/limonade.php';
require_once dirname(__DIR__).'/lib/limonade/tests.php';

$basedir = __DIR__ . DS;

test_suite('Limonade');
  require $basedir."tests.php";
  require $basedir."router.php";
  require $basedir."request.php";
  require $basedir."main.php";
  require $basedir."file.php";
  require $basedir."functional.php";
  require $basedir."output.php";
  require $basedir."http.php";
end_test_suite();
