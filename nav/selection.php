<?php

session_start();

if (empty($_SESSION["ultimate"])) {
  header("location:../index.php");
}

include("conn.php");

$id = 1;

$resultco = mysqli_query($db,"SELECT max(d_index) FROM verse");
  $rowco = mysqli_fetch_array($resultco);
  $rowcount = $rowco['max(d_index)'];

?>

<html>
<head>
  
<title>Selection | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" />
  
</head>
<body>
  
<div class="verticontainer">
  
  <div class="title">Multiverse Selection</div>
  
  <div class="iconlistbox">

<?php    
do {
  ($result1 = mysqli_query($db,"SELECT * FROM verse WHERE d_index = '$id'"));
  if (!is_null($result1)) {
  
  $row1 = mysqli_fetch_array($result1);
    echo '<div class="roundnav">
    <a href="verse.php?v=',
    $row1['tag'],
    '" class="topcase" id="',
    $row1['id'],
    '">
    <div class="ontop" id="tit',
    $row1['id'],
    '"><div class="clicktext">',
    $row1['name'],
    '</div></div>
    <img class="round" src="../assets/',
    $row1['logo'],
    '">
    </a>
    </div>';
    $id++;
  } else {
    $id++;
  }
} while ($id <= $rowcount);

?>
    
  </div>
  
</div>
  
</body>
</html>