<?php
//Include the DynamicEndpoint sources
require_once(__DIR__ . "/../src/DynamicEndpoint.php");
//Define a base for the API
$base = "/dynamic-endpoints/test";
//Create the API object
$API = new API($base);
//Register the endpoints
//Variables are defined with %varName
//A .. allows anyhting
$API->register(Array(
    "/api/movie/../%name/%prop/.." => __DIR__ . "/movie.php",
    "/api/movie/" => __DIR__ . "/movie2.php",
    "/api/book/%id" => __DIR__ . "/book.php",
    "/api/book/name/%name" => __DIR__ . "/book2.php"
));
//Look for an endpoint, will run the file if one is found
$result = $API->runEndpoint();
if (array_key_exists("error", $result)) var_dump ($result);
