<?php
require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

dispatch('/', 'index');
  function index()
	{
		$pairs = explode('&', $_SERVER['QUERY_STRING']);
		$params = array();
		foreach($pairs as $pair)
		{
			$keyAndValue = explode('=', $pair);
			$params[$keyAndValue[0]] = count($keyAndValue) > 1 ? $keyAndValue[1] : '';
		}
		array_shift($params);

		return redirect_to('/redirected', $params);
  }
  
dispatch('/redirected', 'redirected');
  function redirected()
	{
		print $_SERVER['QUERY_STRING'];
  }

run();
