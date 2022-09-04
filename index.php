<?php
require __DIR__ . "/inc/bootstrap.php";
 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
 
if ((isset($uri[2]) && $uri[ 1 ] != 'object') || !isset($uri[ 2 ])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
 
require PROJECT_ROOT_PATH . "/Controller/Api/ObjectController.php";
 
$controller = new ObjectController( "monitored_objects" );
$methodName = $uri[ 2 ] . 'Action';  // ie $controller->select_action();
$controller->{ $methodName }();      // send data output from methodName();
?>
