<?php
require_once dirname(dirname(__FILE__)).'/lib/limonade.php';
require_once dirname(dirname(__FILE__)).'/lib/limonade/tests.php';

$basedir = dirname(__FILE__).DS;

if(!defined('TESTS_DOC_ROOT'))
{
  # 1. CONFIG file is required
  $config_file = dirname(__FILE__).'/config/config.php';
  if(file_exists($config_file))
  {
    include $config_file;
    $doc_root = $config['limonade_base_url']."tests/apps/";
  }
  else
  {
    echo <<<OUTPUT

ERROR: MISSING CONFIG FILE FOR TESTS
====================================

In order to run test, you must have a valid tests/config/config.php file.
Please copy tests/config/config.php.dist into tests/config/config.php and
set required values.

The \$config['limonade_base_url'] is required to run functional tests.

NOTE: the Limonade source code must be somewhere in your HTTP server public
folder in order to call testing limonade apps.

---

OUTPUT;
  exit;
  }
  
  # 2. HTTP+CURL requirements
  if(function_exists('curl_version'))
  {
    $url = $doc_root.'index.php';
    $response = test_request($url, 'GET');
    if($response)
    {
      $v = phpversion();
      $curl_v = curl_version();
      $cv = $curl_v['version'];
      if($response == $v)
      {

        echo <<<OUTPUT

==== RUNNING LIMONADE TESTS [PHP $v — cURL $cv] =====

OUTPUT;
      } else {
        echo  <<<OUTPUT

ERROR: Wrong response to HTTP request test
==========================================

Requesting $url
must return '$v' but returns this response:

$response

---

Your \$config['limonade_base_url'] might be wrong or maybe it's your HTTP
server configuration and/or php installation.
Please fix it in order to run tests.

---

OUTPUT;
        exit;
      }
    }
    else
    {
      exit;
    }
    
  }
  else
  {
    echo <<<OUTPUT

ERROR: cURL Library is required
===============================

Please install PHP cURL library in order to run Limonade tests.


---

OUTPUT;
  }
   
  
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
  require $basedir."http.php";
end_test_suite();
