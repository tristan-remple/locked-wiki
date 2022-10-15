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
  $rowv = mysqli_fetch_array($resultv);
  if ($rowv == NULL) {
    $verse = 'empty';
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
  $verse = $rowv['name'];

if (!isset($_GET['ch'])) {
  $id = 'empty';
  $errmsg = 'Please specify a character.';
  include 'error.php';
} else if (!preg_match("/^[a-zA-Z']*$/",$_GET['ch'])) {
  $id = 'empty';
  $errmsg = 'The character you specified is not valid.';
  include 'error.php';
} else {
  $chara = $_GET['ch'];
  $vtag = $rowv['tag'];
  $charatbl = $vtag.'_charas';
  $result = mysqli_query($db,"SELECT * FROM $charatbl WHERE nick = '$chara'");
  if ($result == FALSE) {
    $errmsg = 'The verse you selected lacks character data.';
    include 'error.php';
  } else {
  $row = mysqli_fetch_array($result);
  if ($row == NULL) {
    $verse = 'empty';
    $errmsg = 'The character you specified is not valid.';
    include 'error.php';
  } else {

?>

<html>
<head>
  
<title><?php echo $row['name']; ?> | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" />
  
</head>
<body>
  
<div class="verticontainer">
  
  <div class="title"><?php echo $row['name']; ?></div>
  
  <div class="rowbox">
    
    <div class="columnthirdtop">
      <img class="biglogo" src="../assets/<?php echo $vtag, '/pics/', $row['nick'], '-icon.png'; ?>">
      <div class="flatcolor factbox">
        <?php
          
          if ((isset($row['age'])) && ($row['age'] !== '')) {
            echo "AGE: ", $row['age'], "<br>";
          }
          
          if ((isset($row['pronouns'])) && ($row['pronouns'] !== '')) {
            echo "PRONOUNS: ", $row['pronouns'], "<br>";
          }
          
          if ((isset($row['height'])) && ($row['height'] !== '')) {
            echo "HEIGHT: ", $row['height'], "<br>";
          }
          
          if ((isset($row['gender'])) && ($row['gender'] !== '')) {
            echo "GENDER: ", $row['gender'], "<br>";
          }
          
          if ((isset($row['sexuality'])) && ($row['sexuality'] !== '')) {
            echo "SEXUALITY: ", $row['sexuality'], "<br>";
          }
          
          if ((isset($row['race'])) && ($row['race'] !== '')) {
            echo "RACE: ", $row['race'], "<br>";
          }
          
          if ((isset($row['specialty'])) && ($row['specialty'] !== '')) {
            echo "FEATURE: ", $row['specialty'], "<br>";
          }
          
          if ((isset($row['job'])) && ($row['job'] !== '')) {
            echo "OCCUPATION: ", $row['job'], "<br>";
          }
          
          if ((isset($row['familiar'])) && ($row['familiar'] !== '')) {
            echo "FAMILIAR: ", $row['familiar'], "<br>";
          }
          
          if ((isset($row['faction'])) && ($row['faction'] !== '')) {
            echo "FACTION: ", $row['faction'], "<br>";
          }
          
        ?>
      </div>
      <a class="sidelink btncolor" href="character.php?v=<?php echo $row['verse'], '&ch=', $row['nick']; ?>"><div class="clicktext">Character Page</div></a>
    </div>
    
    <div class="everyship">
        
        <div class="padship">
        <div class="longtext flatcolor">
            <h1>Timeline</h1><br>
            <?php
              if ((isset($row['history'])) && ($row['history'] !== '')) { echo $row['history']; } else { echo "No data set."; }
              
  }}}}}
            ?>
        </div>
        </div>
        
    </div>
    
  </div>
  
</div>

</body>
</html>