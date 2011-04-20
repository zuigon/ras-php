<?php

$c = "db.php";
$cc = "../db.php";
if(!file_exists($c))
  if(file_exists($cc))
    $c = $cc;
  else die("Error: No db.conf found!");

// $f = file_get_contents(realpath($c));
// $cc = explode("\n", $f);
include $c;
list($host, $uname, $pw, $db) = explode(":", $auth);
// unset($cc);

if($host==null || $uname==null || $db==null)
  die("Error: Podatci iz db.conf su prazni (?)");

mysql_connect($host, $uname, $pw);
mysql_select_db($db);

unset($c); unset($z); unset($auth);

?>
