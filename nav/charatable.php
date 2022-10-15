<?php

session_start();

if (empty($_SESSION["ultimate"])) {
  header("location:../index.php");
}

include("conn.php");

if ((!$_GET) || (!isset($_GET['v']))) {
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
  if ($resultv == FALSE) {
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
  $rowv = mysqli_fetch_array($resultv);
  if ($rowv == NULL) {
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
    $verse = $rowv['name'];
    $charatbl = $v."_charas";
    $resultt = mysqli_query($db,"SELECT id FROM $charatbl");
  if ($resultt == FALSE) {
    $errmsg = 'The verse you specified has no character table yet.';
    include 'error.php';
  } else {
  $rowt = mysqli_fetch_array($resultt);



$resultco = mysqli_query($db,"SELECT max(d_index) FROM $charatbl");
  $rowco = mysqli_fetch_array($resultco);
  $rowcount = $rowco['max(d_index)'];

?>

<html>
<head>
  
<title>Character Selection | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" />
  
</head>
<body>
  
<div class="verticontainer">
  
  <div class="title">Character Selection</div>
  
  <div class="iconlistbox">

<?php

$id = 1;

do {
  $result1 = mysqli_query($db,"SELECT * FROM $charatbl WHERE d_index = '$id'");
  if (!is_null($result1)) {
  
  $row1 = mysqli_fetch_array($result1);
    echo '<div class="roundnav">
    <a href="character.php?v=',
    $v,
    '&ch=',
    $row1['nick'],
    '" class="topcase" id="',
    $row1['id'],
    '">
    <div class="ontop" id="tit',
    $row1['id'],
    '"><div class="clicktext">',
    $row1['name'],
    '</div></div>
    <img class="round" src="../assets/',
    $v,
    '/pics/',
    $row1['nick'],
    '-icon.png">
    </a>
    </div>';
    $id++;
  } else {
    $id++;
  }
} while ($id <= $rowcount);

?>
    
  </div>
  
  <div class="iconlistbox">
    <?php
    
    $result2 = mysqli_query($db,"SELECT * FROM $charatbl WHERE importance > 2");
    if ($result2 !== FALSE) {
    $row2 = mysqli_fetch_all($result2);
    
    foreach ($row2 as $key => $value) {
      echo '<A class="minor darkbtn" href="character.php?v=', $v, '&ch=', $value[6], '">', $value[1], '</a>';
    }}
    
    }}}}
    
    ?>
  </div>
  
</div>
  
</body>
</html>