<?php
require_once 'utils.php';

class API {
    
    private $endpoints = Array();
    private $BASE = "";
            
    function __construct($base = "") {
        $this->BASE = $base;
    }
    
    function register ($endpoints) {
        function removeTrailingSlash(&$value, $omit) {
            $value = rtrim($value, "/");
        }
        $keys = array_keys($endpoints);
        array_walk($keys, 'removeTrailingSlash');
        $this->endpoints = array_combine($keys, $endpoints);
    }
    
    function registerInDir ($dir) {
        
    }
    
    function runEndpoint () {
        $PATH = parse_url($_SERVER['REQUEST_URI'])["path"];
        $endpointKeys = array_map(function ($k) {return $this->BASE . $k;}, array_keys($this->endpoints));
        $endpoint = getBestMatch($endpointKeys, $PATH);

        if (is_array($endpoint) && key_exists("error", $endpoint)) return $endpoint;
        $endpoint = "/" .implode("/",  $endpoint);

                
        $varaibles = matchAlongTemplate($endpoint, $PATH);
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