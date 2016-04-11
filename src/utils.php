<?php
function parseWithCallback($url, $format, $callback) {



}

function splitAtSlash($url) {

  $working = Array();
  $formatSplit = Array();
  foreach (str_split($url) as $char) {
    if ($char == "/") {
      if (implode($working) != "")
        $formatSplit[] = urldecode(implode($working));
      $working = Array();
    }
    else {
      $working[] = $char;
    }
  }
  $formatSplit[] = urldecode(implode($working));
  return array_filter($formatSplit);

}

function matchAlongTemplate($_template, $_match) {
  $match = splitAtSlash($_match);
  $template = splitAtSlash($_template);
  if (count($template) != count($match)) return false;
  $search = true;
  $i = 0;
  $variables = Array();

  while ($search) {
    
    if (!array_key_exists($i, $template) || !array_key_exists($i, $match)) {$search = false; break;}
    if ($template[$i] == "..") {}
    else if ($template[$i][0] == "%") {
        $variables[$template[$i]] = $match[$i];
    }
    else if ($template[$i] != $match[$i]) {
        return false;
    }
    $i+=1;
  }
  
  if (!$variables) $variables = Array("nothing" => TRUE);
  return $variables;

}

function elementMatches ($template, $match) {    
    //var_dump($template);
    //var_dump($match);
    if ($match == "") {
        return FALSE;
    }
    else if ($template == $match){
        return 3;
    }
    else if ($template[0] == "%"){
        return 2;
    }
    else if ($template == ".."){
        return 1;
    }
    return FALSE;
}

function getBestMatch($templateStrings, $matchString) {
    
    $templates = array_map("splitAtSlash", $templateStrings);
    
    global $match;
    $match = splitAtSlash($matchString);
    
    global $i;
    $i = 0;
    
    $search = TRUE;
    
    while ($search) {
        
        $finalEndpoint = NULL;
        
        if (!array_key_exists($i, $match)) {
            $search = FALSE;
            if (count($templates) == 1) {
                $finalEndpoint = array_values($templates)[0];
                break;
            }
            else if (count($templates) == 0){
                return error("No endpoints");
            }
            else {
                var_dump($templates);
                $shortest = getShortestArray($templates);
                var_dump($shortest);
                if (count($shortest) > 1) return error("Muliple endpoints");
                return array_values($shortest)[0];
            }
        }
        
        $templates = array_filter($templates, function ($template) {
            global $i;global $match;
            if (!array_key_exists($i, $template)) return FALSE;
            return elementMatches($template[$i], $match[$i]) != FALSE;
        });
        if (count($templates) < 1) {
            //echo("00");
            $search = FALSE;
            break;
        }
        $theseElems = array_map(function ($template) {
            global $i;
            return $template[$i];
        }, $templates);
        $currentElem = $match[$i];
        $i++;

    }
    if ($finalEndpoint == NULL) return error("No endpoint was matched");
    return $finalEndpoint;
    
}

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