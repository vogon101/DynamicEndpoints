<?php
namespace DynamicEndpointUtils;
class Utils {
	    static function error($message) {
		            return Array("error" => $message);
			        }

	        static function success($message) {
			        return Array("success" => $message);
				    }

	        static function getShortestArray($array = array()) {
			        if(!empty($array)){
					            $lengths = array_map('count', $array);
						                global $maxLength;
						                $maxLength = min($lengths);
								            return array_filter($array, function ($elem) {global $maxLength; return count($elem) == $maxLength;});
								        }
				    }

	        static function remove_prefix($text, $prefix) {
			        if(0 === strpos($text, $prefix))
					            $text = substr($text, strlen($prefix)).'';
				        return $text;
				    }

	        static function removeTrailingSlash(&$value, $omit) {
			        $value = rtrim($value, "/");
				    }
}
