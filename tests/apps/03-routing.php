<?php

require_once dirname(dirname(dirname(__FILE__))).'/lib/limonade.php';

/* testing with all callbacks cases */
dispatch('/route0', 'test_route0');
function test_route0()
{
  return "route0";
}

dispatch('/route1', 'test_route1');
function test_route1()
{
  return "route1";
}


class MyController
{
  var $num = 10;
  
  function __construct($num = 10)
  {
    $this->num = $num;
  }
  
  public function method($value=1)
  {
    return $value * $this->num;
  }
 
  static public function staticMethod($value=1)
  {
   return $value * 20;
  }
}


$obj = new MyController(10);

dispatch('/route2', array($obj, 'method'));
dispatch('/route3', array('MyController', 'staticMethod'));
dispatch('/route4', 'MyController::staticMethod');
if(version_compare(PHP_VERSION, '5.3.0') >= 0)
{
  eval("dispatch('/route-lambda', function(){
    return 'LAMBDA CALL';
  });");
}

/* parameterized routes */
dispatch('/route5', 'test_route5', array('params' => array('type' => 'human')));
function test_route5()
{
  return params('type');
}
dispatch('/route5b', 'test_route5b', array('params' => array('human')));
function test_route5b()
{
  return params(0);
}
dispatch('/route6', 'test_route6', array('params' => array('type' => 'human')));
function test_route6($type)
{
  return $type;
}
dispatch('/route6b', 'test_route6b', array('params' => array('type' => 'human', 'num' => 10)));
dispatch('/route6c', 'test_route6b', array('params' => array('human', 10)));
function test_route6b($type, $num)
{
  return $type . $num;
}


/* routes params */
dispatch('/route7/:id', 'test_route7');
function test_route7()
{
  $res = params('id');
  (int) $res;
  return $res;
}
dispatch('/route7b/:id', 'test_route7b', array('params' => array('id' => 10)));
function test_route7b()
{
  $res = params('id');
  (int) $res;
  return $res;
}
dispatch('/route7c/:id', array($obj, 'method'));
dispatch('/route7d/:id', array($obj, 'method'), array('params' => array('id' => 10)));
dispatch('/route7e/:id', 'MyController::staticMethod');
dispatch('/route7f/:id', 'MyController::staticMethod', array('params' => array('id' => 10)));
dispatch('/route7g', 'MyController::staticMethod', array('params' => array('id' => 10)));
dispatch('/route7h', 'MyController::staticMethod', array('params' => array(10)));
dispatch('/route8/:id', 'test_route8');
function test_route8($id)
{
  (int) $id;
  return $id;
}
dispatch('/route8b/:id', 'test_route8b', array('params' => array('id' => 10)));
function test_route8b($id)
{
  (int) $id;
  return $id;
}
dispatch('/route8c/:id', 'test_route8c', array('params' => array('divider' => 2)));
function test_route8c($divider, $id)
{
  (int) $id;
  return ($id / $divider);
}


dispatch('/route9/*', 'MyController::staticMethod');
dispatch('/route9b/*', 'MyController::staticMethod', array('params' => array(10)));
dispatch(array('/route10/*', array('id')), 'MyController::staticMethod');
dispatch(array('/route10b/*', array('id')), 'MyController::staticMethod', array('params' => array('id' => 10)));

/* http methods dispatching */

dispatch_get('/route11', 'test_route11');
function test_route11()
{
  header('X-LIM-CTL: route11');
  return "GET";
}

dispatch_post('/route11', 'test_route11post');
function test_route11post()
{
  //header('Content-length: 4');
  return "POST";
}

dispatch_put('/route11', 'test_route11put');
function test_route11put()
{
  return "PUT";
}

dispatch_delete('/route11', 'test_route11delete');
function test_route11delete()
{
  return "DELETE";
}







run();