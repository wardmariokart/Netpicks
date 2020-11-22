<?php
session_start();
ini_set('display_errors', true);
error_reporting(E_ALL);

$routes = array(
  'home' => array(
    'controller' => 'Home',
    'action' => 'home'
  ),
  'extraQuestions' => array(
    'controller' => 'Home',
    'action' => 'extraQuestions'
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
  )
);

if(empty($_GET['page'])) {
  $_GET['page'] = 'home';
}
if(empty($routes[$_GET['page']])) {
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
