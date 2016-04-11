<?php
require_once 'utils.php';

class API {
    
    private $endpoints = Array();
    private $BASE = "";
            
    function __construct($base = "") {
        $this->BASE = $base;
    }
    
    function register ($endpoints) {
        $this->endpoints = $endpoints;
    }
    
    function registerInDir ($dir) {
        
    }
    
    function runEndpoint () {
        $endpointKeys = array_map(function ($k) {return $this->BASE . $k;}, array_keys($this->endpoints));
        $endpoint = getBestMatch($endpointKeys, $_SERVER['REQUEST_URI']);

        if (is_array($endpoint) && key_exists("error", $endpoint)) return $endpoint;
        $endpoint = "/" .implode("/",  $endpoint);

                
        $varaibles = matchAlongTemplate($endpoint, $_SERVER['REQUEST_URI']);
        if (!$varaibles) return error("Endpoint did not match");
        
        foreach ($varaibles as $_name__=>$value) {
            $_name__ = ltrim($_name__, "%");
            $$_name__ = $value;
        }
        $API = $this;
        
        require ($this->endpoints[remove_prefix($endpoint, $this->BASE)]);
        return success("Endpoint run correctly");
        
    }
    
}

function remove_prefix($text, $prefix) {
    if(0 === strpos($text, $prefix))
        $text = substr($text, strlen($prefix)).'';
    return $text;
}