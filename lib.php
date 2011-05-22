<?php

function qtoa(){
  $narg = func_num_args();
  if ($narg == 1)
    $q = func_get_arg(0);
  else {
    $argl = func_get_args();
    foreach ($argl as $id=>$arg)
      if($id!=0)
        $argl[$id] = mysql_real_escape_string($arg);
    $q = vsprintf($argl[0], array_slice($argl, 1));
  }
  // echo "<pre>Q: $q</pre>";
  $a = array();
  $r = mysql_query($q) or die("in qtoa(): ".mysql_error());
  if($r==0 || $r==1) return $r;
  while ($row = mysql_fetch_row($r)) array_push($a, $row);
  return $a;
}

// "2009", "a" => 1
function raz_id($gen, $raz=-1){
  if($raz==-1)
    list($gen, $raz) = explode('_', $gen);
  $r = mysql_query(sprintf("select id from razredi where gen='%s' and raz='%s' limit 1;", $gen, $raz));
  $x = mysql_fetch_row($r);
  return $x[0];
}

// 2009_a => 2.a
// function raz($str){
//   $time_start = microtime_float();
//   list($a, $b) = explode('_', $str);
//   $now = time();
//   $d = mktime(0, 0, 0, 9, 1, (int)$a);
//   if ($d - $now > 0)
//     return false;
//   $x = (((int)date('y', $now)-(int)date('y', $d)));
//   $time_end = microtime_float();
//   $time = $time_end - $time_start;
//   echo "<pre>D: raz(): ";
//   printf("%.4lf", $time);
//   echo " seconds</pre>";
//   return "$x.$b";
// }

// date() => 0/1
function smjena($datum){
  // $time_start = microtime_float();
  $POC_DATUM = array("6.9.2010", 0);
  $d = strptime("%d.%m.%Y", $POC_DATUM[0]);
  $r = (abs((int)date("W", $datum) - (int)date("%W", $d)) + $POC_DATUM[1])%2;
  if(date("w", time())=="0")
    $r = ($r+1)%2;
  // $time_end = microtime_float();
  // $time = $time_end - $time_start;
  // echo "<pre>D: smjena(): ";
  // printf("%.4lf", $time);
  // echo " seconds</pre>";
  return $r;
}


// 2009_a => 2.a
function raz($str){
  list($a, $b) = explode('_', $str);
  $now = time();
  $d = mktime(0, 0, 0, 9, 1, (int)$a);
  if ($d - $now > 0)
    return false;
  $x = (((int)date('y', $now)-(int)date('y', $d)));
  return "$x.$b";
}

function http_digest_parse($txt){
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach($matches as $m){
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }
    return $needed_parts ? false : $data;
}

function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

date_default_timezone_set("Europe/Zagreb");

$dani = array("Pon", "Uto", "Sri", "Cet", "Pet");

$fself = basename($_SERVER['SCRIPT_NAME']);


function getRas($raz_id, $tj=0){
  $q = "select * from rasporedi where raz_id=".$raz_id." and date_add(date(DATE_SUB(now(), INTERVAL DATE_FORMAT(now(), '%w') DAY)), interval ".$tj." week)>=start_date order by start_date desc limit 1";
  echo "<pre>$q</pre>";
  $r = mysql_query($q);
  if(!$r || mysql_num_rows($r)==0) return 0;
  $h = mysql_fetch_assoc($r);
  $r = array(); // ["pon"=>[...], ...]
  $dani = array("pon", "uto", "sri", "cet", "pet");
  foreach($dani as $d)
    if($h[$d]){
      $r[$d] = explode(";", $h[$d]);
    } else { // ako je lista za taj dan prazna => [""]*9
      $r[$d] = array();
      for($i=0; $i<9; $i++)
        array_push($r[$d], "");
    }
  // mysql_free_result($r);
  return $r;
}

?>