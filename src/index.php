<?php
session_start();
ini_set('display_errors', true);
error_reporting(E_ALL);
set_time_limit(1000);
// basic .env file parsing
if (file_exists("../.env")) {
  $variables = parse_ini_file("../.env", true);
  foreach ($variables as $key => $value) {
     putenv("$key=$value");
  }
}

$routes = array(
  'home' => array(
    'controller' => 'Home',
    'action' => 'home'
  ),
  'extraQuestions' => array(
    'controller' => 'Home',
    'action' => 'extraQuestions'
  ),
  'detail' => array(
    'controller' => 'Home',
    'action' => 'detail'
  ),

  'signIn' => array(
    'controller' => 'Users',
    'action' => 'signIn'
  ),
  'signUp' => array(
    'controller' => 'Users',
    'action' => 'signUp'
  ),
  'signOut' => array(
    'controller' => 'Users',
    'action' => 'signOut'
  ),

  'devTools' => array(
    'controller' => 'Developer',
    'action' => 'devTools'
  )
);

if(empty($_GET['page'])) {
  $_GET['page'] = 'home';
}
if(empty($routes[$_GET['page']])) {
  $_SESSION['error'] = '\'' . $_GET['page'] . '\'invalid page';
  header('Location: index.php');
  exit();
}

$route = $routes[$_GET['page']];
$controllerName = $route['controller'] . 'Controller';

require_once __DIR__ . '/controller/' . $controllerName . ".php";

$controllerObj = new $controllerName();
$controllerObj->route = $route;
$controllerObj->filter();
$controllerObj->render();
