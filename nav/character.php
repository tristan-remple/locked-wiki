<?php

session_start();

if (empty($_SESSION["ultimate"])) {
  header("location:../index.php");
}

include("conn.php");

if ((!$_GET) or (!isset($_GET['v']))) {
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
    
    <div class="columnthird">
      <img class="biglogo" src="../assets/<?php echo $vtag; ?>/pics/<?php echo $row['nick']; ?>-icon.png">
      <div class="flatcolor synopbox">
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
    </div>
    
    <div class="columnthird">
      <div class="flatcolor synopbox">
        <h1>Public Bio</h1><br>
        <?php if ($row['bio'] !== '') { echo $row['bio']; } else { echo 'No data'; } ?>
      </div>
      <div class="flatcolor synopbox">
        <h1>Private Bio</h1><br>
        <?php if ((isset($row['secret bio'])) && ($row['secret bio'] !== '')) {
            echo $row['secret bio'];
          } else {
            echo "No data set.";
            }
            ?>
      </div>
    </div>
    
    <?php if (((isset($row['importance'])) && ($row['importance'] < 3)) || (!isset($row['importance']))) { ?>
    <div class="columnthird">
      <a class="verselink btncolor" href="timeline.php?v=<?php echo $row['verse'], '&ch=', $row['nick']; ?>"><div class="clicktext">Timeline</div></a>
      <a class="verselink btncolor" href="shiplist.php?v=<?php echo $row['verse'], '&ch=', $row['nick']; ?>"><div class="clicktext">Relationships</div></a>
      <a class="verselink btncolor" href="gallery.php?v=<?php echo $row['verse'], '&ch=', $row['nick']; ?>"><div class="clicktext">Gallery</div></a>
      <a class="verselink btncolor" href="library.php?v=<?php echo $row['verse'], '&ch=', $row['nick']; ?>"><div class="clicktext">Documents</div></a>
      <a class="verselink btncolor" href="trivia.php?v=<?php echo $row['verse'], '&ch=', $row['nick']; ?>"><div class="clicktext">Trivia</div></a>
      <a class="verselink btncolor" href="https://www.knifequeerart.com/nav/character.php?v=<?php echo $row['verse'], '&id=', $row['id']; ?>"><div class="clicktext">Public Page</div></a>
      <?php } ?>
      
      <a class="verselink btncolor" href="verse.php?v=<?php echo $row['verse']; ?>"><div class="clicktext">Verse</div></a>
    </div>
    
  </div>
  
</div>

<?php }}}}} ?>

</body>
</html>