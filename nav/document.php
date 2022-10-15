<?php

session_start();

if (empty($_SESSION["ultimate"])) {
  header("location:../index.php");
}

include("conn.php");

if (!$_GET['v']) {
  $verse = 'empty';
  $errmsg = 'Please specify a verse.';
  include 'error.php';
} else if (!preg_match("/^[a-zA-Z']*$/",$_GET['v'])) {
  $verse = 'empty';
  $errmsg = 'The verse you specified is not valid.';
  include 'error.php';
} else {
  $v = $_GET['v'];
  $resultv = mysqli_query($db,"SELECT * FROM verse WHERE tag = '$v'");
  $rowv = mysqli_fetch_array($resultv);
  if ($rowv == NULL) {
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
  $verse = $rowv['name'];

if (!$_GET['id']) {
  $id = 'empty';
  $errmsg = 'Please specify an id.';
} else if (!preg_match("/^[0-9']*$/",$_GET['id'])) {
  $id = 'empty';
  $errmsg = 'The id you specified is not valid.';
  include 'error.php';
} else {
  $id = $_GET['id'];
  $vtag = $rowv['tag'];
  $doclist = $vtag.'_docs';
  $result = mysqli_query($db,"SELECT * FROM $doclist WHERE id = '$id'");
  if ($result == FALSE) {
    $errmsg = 'The id you specified is not valid.';
    include 'error.php';
  } else {
  $row = mysqli_fetch_array($result);
  if ($row == NULL) {
    $errmsg = 'The id you specified is not valid.';
    include 'error.php';
  } else {

?>

<html>
<head>
  
<title><?php echo $row['title']; ?> | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" />
  
</head>
<body>
  
<div class="verticontainer">
  
  <?php
  if (isset($row['characters'])) {
    if (str_contains($row['characters'], ',')) {
      $chararray = explode(', ', $row['characters']);
      if (count($chararray) > 2) {
        echo '<div class="doubleinfobox">';
      } else {
        echo '<div class="infobox">';
      }
    } else {
        echo '<div class="infobox">';
      }
  } else {
        echo '<div class="infobox">';
      }
  
  
    echo '<div class="fulltext flatcolor">
      <div class="dochead">
          <div class="doctitle">', $row['title'], '</div>
          <div class="docstat">', $row['date created'], '</div>
          <div class="docstat">', $row['wordcount'], ' words</div>
          <div class="docstat">', $row['status'], '</div>
        </div>
        <br>
        <div class="tagcase">';
          if (isset($row['charagroup'])) { echo $row['charagroup'], ', '; }
          if (isset($row['ships'])) { echo $row['ships'], ', '; }
          if (isset($row['tags'])) { echo $row['tags']; }
        echo '
        <a href="library.php?v=', $vtag, '" class="return">⮭</a>
      </div>
    </div>';
    if (isset($row['characters'])) {
      $chtable = $vtag.'_charas';
      if (str_contains($row['characters'], ',')) {
        $chararray = explode(', ', $row['characters']);
        foreach($chararray as $key => $value) {
          $resultc = mysqli_query($db,"SELECT nick FROM $chtable WHERE nick = '$value'");
          $rowc = mysqli_fetch_array($resultc);
          
          echo '<a class="charalink" href="character.php?v=', $vtag, '&ch=', $rowc['nick'], '">
            <img class="chicon" src="../assets/', $vtag, '/pics/', $value, '-icon.png">
          </a>';
        }
      } else {
        $chara = $row['characters'];
        $resultc = mysqli_query($db,"SELECT nick FROM $chtable WHERE nick = '$chara'");
        $rowc = mysqli_fetch_array($resultc);
        
        echo '<a class="charalink" href="character.php?v=', $vtag, '&ch=', $rowc['nick'], '">
          <img class="chicon" src="../assets/', $vtag, '/pics/', $chara, '-icon.png">
        </a>';
      }
    }
  echo '</div>';
  
  echo '<div class="padmaster">
  <div class="fulltext flatcolor">';
  
  $prose = fopen("../assets/".$vtag."/docs/".$row['filename'].".txt", "r") or die("The specified text could not be found.");
  while (!feof($prose)) {
    echo fgets($prose) . "<br>";
    }
  fclose($prose);
  
  echo '</div></div>';
  }}}}}
  ?>
  
</div>
</body>
</html>