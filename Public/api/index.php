<?php
require_once(dirname(dirname(__DIR__)) . "/vendor/autoload.php");
require_once(dirname(dirname(__DIR__)) . "/Public/header.php");

$controller = null;
$action = null;
$param = [];
parse_str(file_get_contents('php://input'), $param);
$uri = explode("/", $_SERVER["REQUEST_URI"]);
for($i = 0; $i < 3; $i++){
  unset($uri[$i]);
}

$uri = array_filter(array_values($uri));
if(isset($uri[0])){
  $controller = "App\Controller\\" . trim(ucfirst("{$uri[0]}Controller"));
}

if(isset($uri[1])){
    $action = $uri[1];
}

if(isset($uri[2])){
  $param['get_id'] = $uri[2];
}

if (class_exists($controller) && method_exists($controller, $action)) {
    $controller = new $controller();
    echo call_user_func([$controller,$action], $param);
} else {
    echo json_encode(["result" => "Requisição inválida"]);
}