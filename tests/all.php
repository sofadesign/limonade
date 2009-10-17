<?php
require_once dirname(dirname(__FILE__)).'/lib/limonade.php';
require_once dirname(dirname(__FILE__)).'/lib/limonade/tests.php';

$basedir = dirname(__FILE__).DS;

if(!defined('TESTS_DOC_ROOT'))
{
  $doc_root = "http://localhost/limonade-php.net/code/tests/apps/";
  define('TESTS_DOC_ROOT', $doc_root);
}


test_suite('Limonade');
  require $basedir."tests.php";
  require $basedir."router.php";
  require $basedir."request.php";
  require $basedir."main.php";
  require $basedir."file.php";
  require $basedir."functional.php";
  require $basedir."output.php";
end_test_suite();
