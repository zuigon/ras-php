<?php

$c = "db.php";
$cc = "../db.php";
if(!file_exists($c)) if(file_exists($cc)) $c = $cc;
else die("Error: No db.conf found!");
unset($cc);

include $c;
list($host, $uname, $pw, $db) = explode(":", $auth);

if($host==null || $uname==null || $db==null)
  die("Error: Podatci iz db.conf su prazni (?)");

mysql_connect($host, $uname, $pw);
mysql_select_db($db);

unset($c); unset($z); unset($auth);

?>
