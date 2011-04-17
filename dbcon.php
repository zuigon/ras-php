<?php

$c = "db.conf";
$cc = "../db.conf";
if(!file_exists($c))
  if(file_exists($cc))
    $c = $cc;
  else die("Error: No db.conf found!");

$f = file_get_contents(realpath($c));
$cc = explode("\n", $f);
list($host, $uname, $pw, $db) = explode(":", $cc[0]);
unset($cc);

if($host==null || $uname==null || $db==null)
  die("Error: Podatci iz db.conf su prazni (?)");

mysql_connect($host, $uname, $pw);
mysql_select_db($db);

unset($c); unset($z);

$fself = basename($_SERVER['SCRIPT_NAME']);

?>
