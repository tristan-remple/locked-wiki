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
      <a class="sidelink btncolor" href="character.php?v=<?php echo $row['verse'], '&ch=', $row['nick']; ?>"><div class="clicktext">Character Page</div></a>
    </div>
    
    <div class="columnthird">
      <div class="flatcolor synopbox">
        <h1>Trivia</h1><br>
        <?php
        
          if ((isset($row['birthday'])) && ($row['birthday'] !== '')) {
            echo "BIRTHDAY: ", $row['birthday'], "<br>";
          }
          
          if ((isset($row['gender details'])) && ($row['gender details'] !== '')) {
            echo "GENDER (DETAILS): ", $row['gender details'], "<br>";
          }
          
          if ((isset($row['sexuality details'])) && ($row['sexuality details'] !== '')) {
            echo "SEXUALITY (DETAILS): ", $row['sexuality details'], "<br>";
          }
          
          if ((isset($row['sun sign'])) && ($row['sun sign'] !== '')) {
            echo "SUN SIGN: ", $row['sun sign'], "<br>";
          }
          
          if ((isset($row['moon sign'])) && ($row['moon sign'] !== '')) {
            echo "MOON SIGN: ", $row['moon sign'], "<br>";
          }
          
          if ((isset($row['rising sign'])) && ($row['rising sign'] !== '')) {
            echo "RISING SIGN: ", $row['rising sign'], "<br>";
          }
          
          if ((isset($row['tumblr url'])) && ($row['tumblr url'] !== '')) {
            echo "TUMBLR URL: <a href='https://", $row['tumblr url'], ".tumblr.com'>", $row['tumblr url'], "</a><br>";
          }
          
          if ((isset($row['lyric'])) && ($row['lyric'] !== '')) {
            echo "DESCRIPTIVE LYRIC: ", $row['lyric'], "<br>";
          }
          
          if ((isset($row['fursona'])) && ($row['fursona'] !== '')) {
            echo "FURSONA: ", $row['fursona'], "<br>";
          }
          
          if ((isset($row['pokesona'])) && ($row['pokesona'] !== '')) {
            echo "POKESONA: ", $row['pokesona'], "<br>";
          }
          
          if ((isset($row['pkmn type'])) && ($row['pkmn type'] !== '')) {
            echo "POKEMON TYPE: ", $row['pkmn type'], "<br>";
          }
          
          if ((isset($row['flight'])) && ($row['flight'] !== '')) {
            echo "ELEMENT (FLIGHTRISING): ", $row['flight'], "<br>";
          }
          
          if ((isset($row['gbf element'])) && ($row['gbf element'] !== '')) {
            echo "ELEMENT (GBF AU): ", $row['gbf element'], "<br>";
          }
          
          if ((isset($row['gbf race'])) && ($row['gbf race'] !== '')) {
            echo "RACE (GBF AU): ", $row['gbf race'], "<br>";
          }
          
          if ((isset($row['gbf weapon'])) && ($row['gbf weapon'] !== '')) {
            echo "WEAPON (GBF AU): ", $row['gbf weapon'], "<br>";
          }
          
          if ((isset($row['gbf style'])) && ($row['gbf style'] !== '')) {
            echo "STYLE (GBF AU): ", $row['gbf style'], "<br>";
          }
          
          if ((isset($row['gbf notes'])) && ($row['gbf notes'] !== '')) {
            echo "NOTES (GBF AU): ", $row['gbf notes'], "<br>";
          }
          
          if ((isset($row['exalted'])) && ($row['exalted'] !== '')) {
            echo "EXALT TYPE: ", $row['axalted'], "<br>";
          }
          
        
        ?>
      </div>
    </div>

    <div class="columnthird">
      <div class="flatcolor synopbox">
        <h1>Likes</h1><br>
        <?php
        
          if ((isset($row['like1'])) && ($row['like1'] !== '')) {
            echo "♥ ", $row['like1'], "<br>";
          }
          
          if ((isset($row['like2'])) && ($row['like2'] !== '')) {
            echo "♥ ", $row['like2'], "<br>";
          }
          
          if ((isset($row['like3'])) && ($row['like3'] !== '')) {
            echo "♥ ", $row['like3'], "<br>";
          }
          
          if ((isset($row['like4'])) && ($row['like4'] !== '')) {
            echo "♥ ", $row['like4'], "<br>";
          }
        
        ?>
      </div>
      <div class="flatcolor synopbox">
        <h1>Dislikes</h1><br>
        <?php
        
          if ((isset($row['dislike1'])) && ($row['dislike1'] !== '')) {
            echo "✘ ", $row['dislike1'], "<br>";
          }
          
          if ((isset($row['dislike2'])) && ($row['dislike2'] !== '')) {
            echo "✘ ", $row['dislike2'], "<br>";
          }
          
          if ((isset($row['dislike3'])) && ($row['dislike3'] !== '')) {
            echo "✘ ", $row['dislike3'], "<br>";
          }
          
          if ((isset($row['dislike4'])) && ($row['dislike4'] !== '')) {
            echo "✘ ", $row['dislike4'], "<br>";
          }
        
  }}}}}
        ?>
      </div>
    </div>
    
    </div>
  
</div>

</body>
</html>