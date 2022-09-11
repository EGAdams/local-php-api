<?php
// define("PROJECT_ROOT_PATH", __DIR__ . "/../");
define("PROJECT_ROOT_PATH", "joomla/prototype/local-php-api" );
echo "project root path is: " . PROJECT_ROOT_PATH . "<br>";
// include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";
 
// include the base controller file
require_once PROJECT_ROOT_PATH . "/Controller/Api/BaseController.php";
 
// include the use model file
require_once PROJECT_ROOT_PATH . "/Model/ObjectModel.php";
?>
