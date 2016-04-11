<?php
require_once(__DIR__ . "/../src/DynamicEndpoint.php");
//echo($_SERVER['REQUEST_URI']);
//echo("\n");
//var_dump(splitAtSlash($_SERVER['REQUEST_URI']));
//echo("\n");
//
//var_dump(splitAtSlash($base . "/api/book/%id"));
//echo("\n");


$base = "/dynamic-endpoints/test";
//print($_SERVER['REQUEST_URI']);
//var_dump(matchAlongTemplate($base . "/api/book/name/%id", $_SERVER['REQUEST_URI']));
$API = new API($base);
$API->register(Array(
    "/api/movie/../%name/%prop/.." => __DIR__ . "/movie.php",
    "/api/book/%id" => __DIR__ . "/book.php",
    "/api/book/name/%name" => __DIR__ . "/book2.php"
));
$result = $API->runEndpoint();
if (array_key_exists("error", $result)) var_dump ($result);
