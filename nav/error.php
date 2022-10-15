<?php echo '
  <head>
  
<title>Error | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" /></head><body>
  <div class="verticontainer">
  
  <div class="bantitle">
    <div class="smalltitle">Error</div>';
    if (isset($vtag)) {
      echo '<a href="verse.php?v=', $vtag, '" class="return">⮭</a>';
    } else {
      echo '<a href="selection.php" class="return">⮭</a>';
    }
  echo '</div>
  <div class="padmaster">
  <div class="fulltext flatcolor">',
  $errmsg,
  '</div>
  </div>
  ';
  
  ?>