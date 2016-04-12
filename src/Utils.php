<?php
function error($message) {
    return Array("error" => $message);
}

function success($message) {
    return Array("success" => $message);
}

function getShortestArray($array = array()) {
    if(!empty($array)){
        $lengths = array_map('count', $array);
        global $maxLength;
        $maxLength = min($lengths);
        return array_filter($array, function ($elem) {global $maxLength; return count($elem) == $maxLength;});
    }
}

function remove_prefix($text, $prefix) {
    if(0 === strpos($text, $prefix))
        $text = substr($text, strlen($prefix)).'';
    return $text;
}

function removeTrailingSlash(&$value, $omit) {
    $value = rtrim($value, "/");
}