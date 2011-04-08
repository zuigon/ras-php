<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta content='text/html; charset=iso-8859-1' http-equiv='Content-Type' />
  <title>RasporedApp</title>
  <link href='style.css' rel='stylesheet' type='text/css' />
  <script type='text/javascript'>
  </script>
</head>
<body>

<div id='container'>

<?php

$file = fopen("db.conf", "r") or exit("Ne mogu otvoriti db.conf file!");
$c = "";
while(!feof($file))
  $c += fgets($file);
fclose($file);
$cc = explode("\n", $c);
list($host, $uname, $pw, $db) = explode(":", $cc[0]);
unset($c); unset($cc);

$fself = basename($_SERVER['SCRIPT_NAME']);

date_default_timezone_set("Europe/Zagreb");

mysql_connect($host, $uname, $pw);
mysql_select_db($db);

// "2009", "a" => 1
function raz_id($gen, $raz){
  $r = mysql_query(sprintf("select id from razredi where gen='%s' and raz='%s' limit 1;", $gen, $raz));
  $x = mysql_fetch_row($r);
  return $x[0];
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

// date() => 0/1
function smjena($datum){
  $POC_DATUM = array("6.9.2010", 0);
  $d = strptime("%d.%m.%Y", $POC_DATUM[0]);
  $r = (abs((int)date("W", $datum) - (int)date("%W", $d)) + $POC_DATUM[1])%2;
  if(date("w", time())=="0")
    $r = ($r+1)%2;
  return $r;
}

function ras_table($gen, $raz, $tj){
    $q =
  sprintf("select sat, pon, uto, sri, cet, pet, sub from rasporedi where raz_id=%d order by sat;", raz_id($gen, $raz));
  $r = mysql_query($q);
  if (!$r) die('Invalid query: ' . mysql_error());

  if (mysql_num_rows($r)==0)
    die("<h2 class='err'>Raspored nije podesen za ovaj razred ...</h2><p><a href='$fself'>< Svi razredi</a></p>");

  echo "<div id='tj".(($tj>=2)?"N":$tj)."'>";

  echo '<table border="2" id="tbl_ras">';

  $ras = array();
  while ($row = mysql_fetch_row($r))
    $ras[$row[0]] = $row;

  $eventi = array(0=>array(), 1=>array(), 2=>array(), 3=>array(), 4=>array(), 5=>array(), 6=>array());
  $q = sprintf("select weekday(dan), txt, dsc from eventi where raz_id=%d and week(dan)=(week(date(now())-1)+%d);", (int)raz_id($gen, $raz), $tj);
  $r = mysql_query($q);
  while ($row = mysql_fetch_row($r))
    array_push($eventi[$row[0]], $row);
  mysql_free_result($r);

  $d = date('w', time());

  $dani = array("Pon", "Uto", "Sri", "Cet", "Pet");
  echo "<tr><th width='20'>&nbsp;</th>";
  for ($i=0; $i<5; $i++)
    echo "<th class='gray".(($d==$i+1)?" uline":"")."' width='80'>$dani[$i]</th>";
  echo "</tr>";

  echo "<th>&nbsp;</th>";
  $poc = time()-(int)(date("w", time()))*3600*24; // prvi dan tj.
  for ($i=1; $i<=5; $i++)
    echo "<th>".strftime("%d.%m.", $poc+3600*24*($n+$i))."</th>";

  echo "<tr>
    <th>Kal.</th>";
  for ($i=1; $i<=5; $i++) {
    echo "<th class='events_l' valign='top'><ul>\n";
    foreach($eventi[$i-1] as $arr){
      $cl = "gcal_event_li";
      if ($arr[2])
        $cl += " hasdesc";

      echo "<li".
        ($cl!="" ? " class='$cl'" : "").
        ($arr[2] ? " title='".$arr[2]."'" : "").">".
        ($arr[2] ? "+ " : "- ").$arr[1]."</li>\n";
    }
    echo "</ul></th>";
  }
  echo "</tr>";

  $smj = smjena(time()+3600*24*7*$tj);

  for ($i=0; $i<=8; $i++) {
    echo "<tr>";
    echo "<td class='gray'>".$i.".</td>";
    for ($ii=1; $ii<=5; $ii++)
      echo "<td".(($d==$ii && $tj==0)?" class='danas'":"").">".strtoupper($ras[($smj==1) ? 8-$i : $i][$ii])."</td>";
    echo "</tr>\n";
  }
  echo "</table>";
  echo "</div>";
}


if (!isset($_GET['ras'])) {
  $q = "select concat(gen, '_', raz) from razredi order by gen, raz asc;";
  $r = mysql_query($q);
  if (!$r) die('Invalid query: ' . mysql_error());

  echo "<center><table id='t'><tr><td><div id='fl_d'><h1 class='raz_naslov'>R</h1></div></td><td><ul class='svi_razredi'>";
  while ($row = mysql_fetch_row($r))
    echo "<li><a href='?ras=".$row[0]."'>".raz($row[0])."</a></li>";
  echo "</ul></td></tr></table></center>";
} else if (preg_match("/^\d\d\d\d_[a-z]$/", $_GET['ras'])) {
  list($gen, $raz) = explode("_", $_GET['ras']);

  echo "<h1>Razred: ".raz("${gen}_${raz}")."</h1><div id='rasporedi'>";

  ras_table($gen, $raz, 0);
  ras_table($gen, $raz, 1);

  echo "</div>";

  echo "<div class='cb'></div>";
  echo "<p><a href='$fself'>&lt; Svi razredi</a></p>";
} else if (preg_match("/^ajax_\d\d\d\d_[a-z]_\d+$/", $_GET['ras'])) {
  list($x, $gen, $raz, $tj) = explode("_", $_GET['ras']);
  $tj = (int)$tj;
  if ($tj>=0 && $tj<=10)
    ras_table($gen, $raz, $tj);
}

mysql_close();

?>

</div>

</body>
</html>
