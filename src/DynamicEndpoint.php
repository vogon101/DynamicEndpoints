<?php
namespace BluePost;

require_once 'utils.php';
require_once 'ParsingUtils.php';

use DynamicEndpointUtils\ParsingUtils;
use DynamicEndpointUtils\Utils;

/**
 * The main class for a DynamicEndpoint API
 */
class API {
    
    private $endpoints = Array();
    private $BASE = "";
            
    /**
     * Create an API object
     * @param string $base - The base path for the API
     */
    function __construct($base = "") {
        $this->BASE = $base;
    }
    
    /**
     * Set the base path for the API
     * @param string $base - The base path for the API
     */
    function setBase ($base) {
        $this->BASE = $base;
    }
    
    /**
     * Add endpoints to the API
     * @param array $endpoints - The endpoints to add in the format "endpoint" => "file"
     */
    function register ($endpoints) {
        $keys = array_keys($endpoints);
        array_walk($keys, ['DynamicEndpointUtils\\Utils','removeTrailingSlash']);
        $this->endpoints = array_merge($this->endpoints, array_combine($keys, $endpoints));
    }
    
    /**
     * Run the endpoint
     * @return array - Success or error message
     */
    function runEndpoint () {
        $PATH = parse_url($_SERVER['REQUEST_URI'])["path"];
        $endpointKeys = array_map(function ($k) {return $this->BASE . $k;}, array_keys($this->endpoints));
        $endpoint = ParsingUtils::getBestMatch($endpointKeys, $PATH);

        if (is_array($endpoint) && key_exists("error", $endpoint)) return $endpoint;
        $endpoint = "/" .implode("/",  $endpoint);

                
        $varaibles = ParsingUtils::matchAlongTemplate($endpoint, $PATH);
        if (!$varaibles) return Utils::error("Endpoint did not match");
        
        foreach ($varaibles as $_name__=>$value) {
            $_name__ = ltrim($_name__, "%");
            $$_name__ = $value;
        }
        $API = $this;
        
        require ($this->endpoints[Utils::remove_prefix($endpoint, $this->BASE)]);
        return Utils::success("Endpoint run correctly");
    }
    
}

