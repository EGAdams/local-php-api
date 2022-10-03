<?php
header( "Access-Control-Allow-Origin: *" );
require_once __DIR__ . "/inc/bootstrap.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

function is_localhost() { return false; }

if ( is_localhost()) {
    if ((isset($uri[ 2 ]) && $uri[ 1 ] != 'object') || !isset($uri[ 2 ])) {
        header("HTTP/1.1 404 Not Found");
        exit(); }
} else {
    if ((isset($uri[ 4 ]) && $uri[ 4 ] != 'object' && $uri[ 4 ] != 'insert' ) || !isset($uri[ 5 ])) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}


 
require_once PROJECT_ROOT_PATH . "/Controller/Api/ObjectController.php";
// echo "<meta http-equiv=\"refresh\" content=\"1\" />";
// echo "creating new controller... <br>";
$controller = new ObjectController( "monitored_objects" );
// echo "controller created.<br>";
if ( is_localhost()) {
    $methodName = $uri[ 2 ] . 'Action';  // ie $controller->select_action();
} else {
    $methodName = $uri[ 5 ] . 'Action';  // ie $controller->select_action(); //either [ 2 ] or [ 5 ]
}
// echo "calling $methodName ... <br>";
$controller->{ $methodName }();      // send data output from methodName();
?>
