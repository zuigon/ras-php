<?php
$rz = qtoa("select concat(gen, '_', raz) from razredi order by gen desc, raz asc;");

echo "<center><table id='t'><tr><td><div id='fl_d'><h1 class='raz_naslov'>R</h1></div><h2>adm</h2></td><td><ul class='svi_razredi'>";
foreach($rz as $r)
  echo "<li><a href='?raz=".$r[0]."&edit'>".raz($r[0])."</a></li>";
  echo "<li style='margin-top: 15px;'><a href='?noviraz'>&nbsp;+&nbsp;</a></li>";
echo "</ul></td></tr></table></center>";
?>
