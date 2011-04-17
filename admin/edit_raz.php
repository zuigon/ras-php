<h2>Raz edit (<?= ($noviraz)?"novi":$gen.'/'.$raz?>)</h2>

  <form action="?save" method="post">
    <input type="hidden" name="raz" value="<?=$gen."_".$raz?>" />
    <input type="hidden" name="edit" value="ras" />
    <table border="2" id="tbl_ras">
      <tr>
        <th width='20'>&nbsp;</th>
<? for ($i=0; $i<5; $i++) echo "<th class='gray' width='80'>$dani[$i]</th>"; ?>
      </tr>
<? for($i=0; $i<=8; $i++){ ?>
      <tr>
        <td class='gray'><?=$i?></td>
<? for ($j=1; $j<=5; $j++){ ?>
        <td>
<? echo sprintf('<input type="text" name="%s" value="%s" tabindex=%d />',
                  $i.'_'.$j, $ras[$i][$j], ($j-1)*9+$i+1); ?>
        </td>
<? } ?>
      </tr>
<? } ?>
  </table></div>

  <input type="submit" value="Spremi raspored" />
  </form>

  <form action="?save" method="post">
    <input type="hidden" name="raz" value="<?=$gen."_".$raz?>" />
    <input type="hidden" name="edit" value="calurl" />
    <p>Calendar URL: <input type="text" size="50" name="calurl" value="<? $x=qtoa("select calurl from razredi where gen='%s' and raz='%s'", $gen, $raz);
echo $x[0][0]; unset($x); ?>" /> <input type="submit" value="Spremi URL" /></p>
  </form>

  <p><a href="admin.php?delraz=<?=raz_id($gen, $raz)?>" onclick="return (confirm('Are you sure?'));">Obrisi raz/ras</a></p>

  <p><a href="admin.php">Svi razredi</a></p>
