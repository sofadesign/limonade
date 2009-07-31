<?php
ini_set("display_errors", false);
require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';
echo request_uri()."\n";