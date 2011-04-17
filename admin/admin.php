<?php

$title2 = "Admin";
require '../lib.php';
require '../dbcon.php';
require '../auth.php';

$realm = "RasporedApp Admin";

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
  cred:
  header('HTTP/1.1 401 Unauthorized');
  header('WWW-Authenticate: Digest realm="'.$realm.
    '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
  die('...');
}

if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
  !isset($admins[$data['username']]))
  die('Wrong Credentials!');

$A1 = md5($data['username'] . ':' . $realm . ':' . $admins[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if($data['response'] != $valid_response) goto cred;

// ok, valid username & password
$user = $data['username'];


if( isset($_GET['raz']) && isset($_GET['edit']) ){
  $title2 = "raz edit";
  if($_GET['edit'] == 'r' || 1==1){
    if(!preg_match("/^\d\d\d\d_[a-z]$/", $_GET['raz'])) die("404");
    $str = 'editraz';
    list($gen, $raz) = explode("_", $_GET['raz']);
    $razid = (int)raz_id($gen, $raz);
    $razred = qtoa("select calurl from razredi where id=%d", $razid);
    if(count($razred)==0) die("<p>Razred ne postoji!</p><a href='admin.php'>&lt; Nazad</a>");
    $ras = array(); $r = qtoa(
      "select sat, pon, uto, sri, cet, pet from rasporedi where raz_id=%d order by sat",
      $razid); foreach($r as $row) $ras[$row[0]] = $row; unset($r);
  }
} elseif( isset($_GET['noviraz']) && isset($_GET['save']) ){
  $raz = $_POST['raz']; $gen = $_POST['gen']; $cal = $_POST['calurl'];
  if(!preg_match("/^20\d\d$/", $gen)) die("Krivi format generacije (/20\d\d/)");
  if(!preg_match("/^[a-z]$/", $raz)) die("Krivi format odjela (a-z ili A-Z)");
  $raz = strtolower($raz);

  $r = qtoa("select id from razredi where raz='%s' and gen='%s'", $raz, $gen);
  if(count($r)>0)
    echo "Razred vec postoji! (<a href='?raz=".$gen."_".$raz."&edit'>Link</a>)";
  else {
    qtoa("insert into razredi (raz, gen, calurl) values ('%s', '%s', '%s');",
      $raz, $gen, $cal);
  header("Location: ".$_SERVER['PHP_SELF']."?raz=".$gen."_".$raz."&edit");
    die();
  }
} elseif( isset($_GET['noviraz']) ){
  $title2 = "novi razred";
  $str = 'newraz';
  $noviraz=1;
} elseif( isset($_GET['delraz']) ){
  $razid = $_GET['delraz'];
  if(!preg_match("/^\d+$/", $razid)) die("Krivi format generacije (/20\d\d/)");
  qtoa("delete from razredi where id=%d", $razid);
  qtoa("delete from rasporedi where raz_id=%d", $razid);
  header("Location: ".$_SERVER['PHP_SELF']);
  die();
} elseif(isset($_GET['save'])){
    if($_POST['edit']=='ras'){
      for($i=0; $i<=8; $i++){
        $r = qtoa("select id from rasporedi where raz_id=%d and sat=%d", raz_id($_POST['raz']), $i);
        qtoa(
          ( (count($r)==0) ?
          "insert into rasporedi (pon, uto, sri, cet, pet, raz_id, sat) values ('%s', '%s', '%s', '%s', '%s', %d, %d);\n" :
          "update rasporedi set pon='%s', uto='%s', sri='%s', cet='%s', pet='%s' where raz_id=%d and sat=%d;\n" ),
          strtolower($_POST[$i.'_1']), strtolower($_POST[$i.'_2']),
          strtolower($_POST[$i.'_3']), strtolower($_POST[$i.'_4']),
          strtolower($_POST[$i.'_5']), raz_id($_POST['raz']), $i);
      }
    } elseif($_POST['edit']=='calurl'){
      list($gen, $raz) = explode('_', $_POST['raz']);
      qtoa("update razredi set calurl='%s' where gen='%s' and raz='%s'",
           $_POST['calurl'], $gen, $raz);
    }
    header("Location: ".$_SERVER['PHP_SELF']."?raz=".$_POST['raz']."&edit");
    die();
} else {
  // MAIN - lista razreda
  $str = 'main';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta content='text/html; charset=iso-8859-1' http-equiv='Content-Type' />
  <title>RasporedApp - <?=$title2?></title>
  <link href='../style.css' rel='stylesheet' type='text/css' />
  <script type='text/javascript'>
  </script>
</head>
<body>

<? if(empty($_GET)) echo "<a href='../ras.php'>&lt; RasporedApp</a>" ?>

<div id='container'>

<? if($str=='main'){
  include 'show_all.php';
}// elseif($str=='showraz') {
elseif($str=='newraz') { ?>
<form action="?noviraz&save" method="post">
  <table border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td>Generacija:</td><td><select name="gen">
        <? for($i=2008; $i<=2015; $i++) echo "<option value='$i'>$i</option>"; ?>
      </select></td>
    </tr>
    <tr><td>Odjel (a-z):</td><td><input type="text" name="raz" /></td></tr>
    <tr><td>Cal URL:</td><td><input type="text" name="calurl" size="40" /></td></tr>
    <tr><td><input type="submit" value="Dodaj" /><br/><p><a href="admin.php">&lt; Nazad</a></p></td></tr>
  </table>
  <p>

</form>
<? } elseif($str=='editraz') {
  include 'edit_raz.php';
} else {
} ?>

</div>
</body>
</html>
