<?php
header( "Access-Control-Allow-Origin: *" );
require_once __DIR__ . "/inc/bootstrap.php";
//echo "parsing uri... <br>";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

function is_localhost() { return false; }

if ( is_localhost()) {
    if ((isset($uri[ 2 ]) && $uri[ 1 ] != 'object') || !isset($uri[ 2 ])) {
        header("HTTP/1.1 404 Not Found");
        exit(); }
} else {
    if ((isset($uri[ 7 ]) && $uri[ 7 ] != 'object' && $uri[ 8 ] != 'insert' ) &&  ($uri[ 8 ] != 'select' )) {
        echo "error <br>";
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}

// echo $uri[0] .  "<br>" .$uri[1] .  "<br>" . $uri[2] .  "<br>" . $uri[3] .  "<br>" . $uri[4] .  "<br>" . $uri[5] .  "<br>" . $uri[6] .  "<br>"  . $uri[7] .  "<br>";

 
require_once PROJECT_ROOT_PATH . "/Controller/Api/ObjectController.php";
// echo "<meta http-equiv=\"refresh\" content=\"1\" />";
// echo "creating new controller... <br>";
$controller = new ObjectController( "monitored_objects" );
// echo "controller created.<br>";
if ( is_localhost()) {
    $methodName = $uri[ 2 ] . 'Action';  // ie $controller->select_action();
} else {
    $methodName = $uri[ 5 ] . 'Action';  // ie $controller->select_action();
}
//echo "calling $methodName ... <br>";
echo " ";
$controller->{ $methodName }();      // send data output from methodName();
?>
