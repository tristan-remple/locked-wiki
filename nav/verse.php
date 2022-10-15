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
  $result = mysqli_query($db,"SELECT * FROM verse WHERE tag = '$v'");
  if ($result == FALSE) {
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
  $row = mysqli_fetch_array($result);
  if ($row == NULL) {
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
    $verse = $row['name'];

?>

<html>
<head>
  
<title><?php echo $verse; ?> | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" />
  
</head>
<body>
  
<div class="verticontainer">
  
  <div class="title"><?php echo $verse; ?></div>
  
  <div class="rowbox">
    
    <div class="columnthird">
      <img class="biglogo" src="../assets/<?php echo $row['logo']; ?>">
      <a class="verselink btncolor" href="http://www.unrealityagents.com/nav/verse.php?v=<?php echo $row['tag']; ?>"><div class="clicktext">Public Page</div></a>
      <a class="verselink btncolor" href="selection.php"><div class="clicktext">Verse Selection</div></a>
      <a class="verselink btncolor" href="../index.php"><div class="clicktext">Home</div></a>
    </div>
    
    <div class="columnthird">
      <div class="flatcolor synopbox">
        <h1>Public Synopsis</h1><br>
        <?php echo $row['synop']; ?>
      </div>
      <div class="flatcolor synopbox">
        <h1>Private Synopsis</h1><br>
        <?php echo $row['overview'], '<br><br>
        Created on ', $row['date']; ?>
      </div>
    </div>
    
    <div class="columnthird">
      <a class="verselink btncolor" href="library.php?v=<?php echo $row['tag']; ?>"><div class="clicktext">Documents</div></a>
      <a class="verselink btncolor" href="charatable.php?v=<?php echo $row['tag']; ?>"><div class="clicktext">Characters</div></a>
      
      <?php
        $number = 1;
        do {
          $link = 'link'.$number;
          if (($row[$link]) !== '') {
            $concept = $row[$link];
            
            echo '<a class="verselink btncolor" href="concept.php?c=', $concept, '"><div class="clicktext">', $concept, '</div></a>';
          }
          $number++;
        } while ($number < 7);        
}}}
      ?>
      
    </div>
    
  </div>
  
</div>

</body>
</html>