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

<? if(empty($_GET)) echo "<a href='admin/admin.php'>Admin</a>" ?>

<div id='container'>
<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once 'lib.php'; require_once 'dbcon.php';


function ras_table($gen, $raz, $tj){
  // $time_start = microtime_float();
  global $fself;
  // $q = sprintf("select * from rasporedi where raz_id=%d and (date(now())+%d)>=start_date order by id desc limit 9", raz_id($gen, $raz), $tj*7);
  // echo "<pre>$q</pre>";
  $r = mysql_query($q);
  // if (!$r) die('Invalid query: ' . mysql_error());
  // if (mysql_num_rows($r)==0)

  $ras = getRas(raz_id($gen, $raz), $tj);
  if(!$ras)
    die("<h2 class='err'>Raspored nije podesen za ovaj razred ...</h2><p><a href='$fself'>&lt; Svi razredi</a></p>");

  echo "<div id='tj".(($tj>=2)?"N":$tj)."'>";
  echo '<table border="2" id="tbl_ras">';

  $eventi = array(0=>array(), 1=>array(), 2=>array(), 3=>array(), 4=>array(), 5=>array(), 6=>array());
  $q = sprintf("select weekday(dan), txt, dsc from eventi where raz_id=%d and YEARweek(dan)=YEARweek(date(now())+%d);", raz_id($gen, $raz), $tj*7);
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
  $dan = 24*3600;
  $poc = time()-(int)(date("w", time()))*$dan; // prvi dan tj.
  for ($i=1; $i<=5; $i++)
    echo "<th>".strftime("%d.%m.", $poc+$dan*$i+$tj*$dan*7)."</th>";

  echo "<tr><th>Kal.</th>";
  for ($i=1; $i<=5; $i++) {
    echo "<th class='events_l' valign='top'><ul>";
    foreach($eventi[$i-1] as $arr){
      $cl = "gcal_event_li";
      if ($arr[2]) $cl+=" hasdesc";
      if (preg_match("/ test$/", $arr[1])) $cl+=" ev_test";
      if (preg_match("/ odg$/", $arr[1])) $cl+=" ev_odg";

      echo "<li".
        ($cl     ? " class='$cl'" : "").
        ($arr[2] ? " title='".$arr[2]."'" : "").">".
        ($arr[2] ? "+ " : "- ").$arr[1]."</li>";
    }
    echo "</ul></th>";
  }
  echo "</tr>";

  $smj = smjena(time()+3600*24*7*$tj);

  $dani = array("pon", "uto", "sri", "cet", "pet");

  for ($i=0; $i<=8; $i++) {
    echo "<tr>";
    echo "<td class='gray'>".$i.".</td>";
    for ($j=0; $j<5; $j++)
      echo "<td".(($d==$j && $tj==0)?" class='danas'":"").">".strtoupper($ras[$dani[$j]][($smj==1) ? 8-$i : $i])."</td>";
    echo "</tr>";
  }
  echo "</table></div>";
}

if (!isset($_GET['ras'])) {
  $q = "select concat(gen, '_', raz) from razredi order by gen desc, raz asc;";
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
  echo "<div class='cb'><br /></div>";
  ras_table($gen, $raz, 1);
  echo "<div class='cb'><br /></div>";
  ras_table($gen, $raz, 2);
  echo "<div class='cb'><br /></div>";
  ras_table($gen, $raz, 3);

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

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-22251152-1']);
_gaq.push(['_trackPageview']);
(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript';   ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0];   s.parentNode.insertBefore(ga, s);
})();
</script>

</body>
</html>
