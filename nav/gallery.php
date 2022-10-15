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
  
  if (!isset($_GET['ch'])) {
    $fchara = 'empty';
  } else if (!preg_match("/^[a-zA-Z']*$/",$_GET['ch'])) {
    $fchara = 'empty';
  } else if ($_GET['ch'] == '') {
    $fchara = 'empty';
  } else {
    $fchara = $_GET['ch'];
  }
  
  $resultv = mysqli_query($db,"SELECT * FROM verse WHERE tag = '$v'");
  if ($resultv == FALSE) {
    $verse = 'empty';
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
  $rowv = mysqli_fetch_array($resultv);
  if ($rowv == NULL) {
    $verse = 'empty';
    $errmsg = 'The verse you specified is not valid.';
    include 'error.php';
  } else {
    
  $verse = $rowv['name'];
  $vtag = $rowv['tag'];
  $charalist = $vtag.'_charas';

$result = mysqli_query($db,"SELECT nick FROM $charalist WHERE importance = '1' OR '2'");
if ($result == FALSE) {
    $verse = 'empty';
    $errmsg = 'The verse you specified does not have character information.';
    include 'error.php';
  } else {
$rows[] = mysqli_fetch_all($result, $mode = MYSQLI_ASSOC );
if ($rows == NULL) {
    $verse = 'empty';
    $errmsg = 'The verse you specified does not have characters of high importance.';
    include 'error.php';
  } else {

$charaopt = array_column($rows['0'], 'nick');
$characters = array_unique($charaopt);

$resultfa = mysqli_query($db,"SELECT * FROM fanart");
$rowsfa[] = mysqli_fetch_all($resultfa, $mode = MYSQLI_ASSOC );

$artlist1 = array_column($rowsfa['0'], 'artist1');

$urllist1 = array_column($rowsfa['0'], 'url1');

$artlist2 = array_column($rowsfa['0'], 'artist2');

$urllist2 = array_column($rowsfa['0'], 'url2');
  
?>

<html>
<head>
  
<title><?php echo $verse; ?> | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  
$(document).ready(function() {

$('.optcat').click(function(e){
    e.preventDefault();
    var category = $(this).attr('id');
    $('#'+category+'.collapse').toggleClass('collapsed');

    $(this).text(newtext);
});

$(".optcat").keypress(function(event) { 
          if (event.keyCode === 13 || 36) {
            var category = $(this).attr('id');
            $(".optcat#"+category).click(); 
          } 
      });

$(".thumb").click(function() {
  var thumb = $(this).attr('src');
  var filearray = thumb.split('-');
  filearray.pop();
  var filepath = filearray.join('-');

  $('.fullimg').attr('src', filepath+'v1.png');
  $('.popbg').show();
  
  var id = $(this).attr('id');
  if (id.includes('fanart')) {
    var artlist1 = <?php echo json_encode($artlist1); ?>;
    var artlist2 = <?php echo json_encode($artlist2); ?>;
    var urllist1 = <?php echo json_encode($urllist1); ?>;
    var urllist2 = <?php echo json_encode($urllist2); ?>;
    var idnum = parseInt(id.slice(6));
    var fanid = idnum - 2;
    
    $('.credbox').removeClass('hidden');
    $('#cred1').attr('href', urllist1[fanid]);
    $('#cred1').text(artlist1[fanid]);

    $('#cred2').attr('href', urllist2[fanid]);
    $('#cred2').text(artlist2[fanid]);
    
  }
  
  var currimg = $('.fullimg').attr('src');
  var currarray = currimg.split('v');
  var currno = currarray[currarray.length - 1];
  currarray.pop();
  var currpath = currarray.join('v');
  
  var currno2 = currno.split('.');
  var currnum = parseInt(currno2['0']);
  var nextnum = currnum + 1;
  
  var nextimg = currpath+'v'+nextnum+'.png';
  
  $.get(nextimg)
    .fail(function() { 
    $('#next').addClass('noclick');
  }).done(function() { 
    $('#next').removeClass('noclick');
  });
    
  $('#prev').addClass('noclick');
  
});

$(".close").click(function() {
  $('.popbg').hide();
  $('.credbox').addClass('hidden');
});

$('#next').click(function() {
  var currimg = $('.fullimg').attr('src');
  var currarray = currimg.split('v');
  var currno = currarray[currarray.length - 1];
  currarray.pop();
  var currpath = currarray.join('v');
  
  var currno2 = currno.split('.');
  var currnum = parseInt(currno2['0']);
  var nextnum = currnum + 1;
  
  var nextimg = currpath+'v'+nextnum+'.png';
  
  $.get(nextimg)
    .done(function() { 
    $('.fullimg').attr('src', nextimg);
  });
    
  var afternum = currnum + 2;
  var afterimg = currpath+'v'+afternum+'.png';
  
  $.get(afterimg)
    .fail(function() { 
    $('#next').addClass('noclick');
  }).done(function() { 
    $('#next').removeClass('noclick');
  });
    
  $('#prev').removeClass('noclick');
  
});

$('#prev').click(function() {
  var currimg = $('.fullimg').attr('src');
  var currarray = currimg.split('v');
  var currno = currarray[currarray.length - 1];
  currarray.pop();
  var currpath = currarray.join('v');
  
  var currno2 = currno.split('.');
  var currnum = parseInt(currno2['0']);
  var prevnum = currnum - 1;
  
  var previmg = currpath+'v'+prevnum+'.png';
  
  $.get(previmg)
    .done(function() { 
    $('.fullimg').attr('src', previmg);
  });
    
  var beforenum = currnum - 2;
  var beforeimg = currpath+'v'+beforenum+'.png';
  
  $.get(beforeimg)
    .fail(function() { 
    $('#prev').addClass('noclick');
  }).done(function() { 
    $('#prev').removeClass('noclick');
  });
    
  $('#next').removeClass('noclick');
  
});

$('#shipbtn').click(function() {
  $('#shipart').toggleClass('hidden');
  $('#shipbtn').toggleClass('actbtn');
  $('#shipbtn').toggleClass('dormbtn');
});

$('#fanbtn').click(function() {
  $('#fanart').toggleClass('hidden');
  $('#fanbtn').toggleClass('actbtn');
  $('#fanbtn').toggleClass('dormbtn');
});

});

</script>
  
</head>
<body>
  
  <div class="popbg">
    <div class="poprow">
      <div class="arrow" id="prev">🡸</div>
      <img class="fullimg" src="../images/placeholder.png">
      <div class="arrow" id="next">🡺</div>
    </div>
    <div class="credbox flatcolor hidden">Art by <a href="google.com" id="cred1">a link</a> <a href="google.com" id="cred2"></a></div>
    <div class="close">×</div>
  </div>

<div class="verticontainer">
  
  <div class="bantitle">
    <div class="smalltitle">Gallery<?php if ($verse !== 'empty') { echo ": ", $verse; } ?></div>
    <a href="verse.php?v=<?php echo $vtag; ?>" class="return">⮭</a>
  </div>
  
  <div class="barenabler">
    
    <div class="sidebar">
      
      <?php
      echo '
      <div class="optcat" tabindex="0" id="character">➢ character</div>
      <div class="optbox collapse collapsed" id="character">';
      
      foreach ($characters as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($fchara, $shortvalue) !== false) {
          $button = 'actbtn';
          
          echo "<a href='gallery.php?v=", $vtag;
          if ($fchara !== 'empty') {
            
            echo '&ch=', str_replace($shortvalue, '', $fchara); }
          echo "' id=\"", $shortvalue, "\" class=\"year sidebtn ", $button, "\"'>", $value, "</a>";
          
        } else {
          $button = 'dormbtn';
          echo "<a href='gallery.php?v=", $vtag;
          if ($fchara !== 'empty') { echo '&ch=', $fchara, $shortvalue; } else { echo '&ch=', $shortvalue; }
          echo "' id=\"", $shortvalue, "\" class=\"year sidebtn ", $button, "\"'>", $value, "</a>";
        }
        
        
      }
      
      if ($fchara == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='gallery.php?v=", $vtag, "' id='anychara' class='sidebtn ", $button, "'>any character</a>";
      
      echo '</div>';
      
      ?>
    <br>
    <div class="sidebtn actbtn" id="shipbtn">Show Ship Art</div>
    
    <div class="sidebtn dormbtn" id="fanbtn">Show Fanart</div>
    
    </div>
    
    <div class="bookcase">
    
      <div class="sectitle">Main</div>
      
      <div class="thumbcase">
        
        <?php
          
          foreach ($characters as $value) {
            
            if ((($fchara !== 'empty') && (str_contains($fchara, $value))) || ($fchara == 'empty')) {
            
            $id = 1;
            
            do {
              $filename = '../assets/'.$vtag.'/pics/'.$value.'-'.$id.'-thumb.png';
              if (file_exists($filename)) {
                echo '<div class="imgbox"><img class="thumb" id="', $value, $id, '" src="', $filename, '"></div>';
              }
              $id++;
            } while (file_exists($filename));
            
          }
          
          }
          
        ?>
        
      </div>
      
      <div id="shipart" class="galarea">
        
        <div class="sectitle">Ship Art</div>
        
        <div class="thumbcase">
          
          <?php
            
            $results = mysqli_query($db,"SELECT * FROM ship_art WHERE verse = '$v'");
            $rowcount = mysqli_num_rows($results);
            
            if ($rowcount !== 0) {
              
              while ($rows = mysqli_fetch_array($results)) {
                
                if ((($fchara !== 'empty') && (
                    (($rows['chara1'] !== '') && (str_contains($fchara, $rows['chara1']))) ||
                    (($rows['chara2'] !== '') && (str_contains($fchara, $rows['chara2']))) ||
                    (($rows['chara3'] !== '') && (str_contains($fchara, $rows['chara3'])))
                  )) || ($fchara == 'empty')) {
                
                echo '<div class="imgbox"><img class="thumb" id="', $rows['filename'], '" src="../assets/', $v, '/pics/', $rows['filename'], '-thumb.png"></div>';
              }
              
              }
              
            }
          ?>
          
        </div>
        
      </div>
      
      <div id="fanart" class="galarea hidden">
        
        <div class="sectitle">Fanart</div>
        
        <div class="thumbcase">
          
          <?php
            
            $results = mysqli_query($db,"SELECT * FROM fanart WHERE verse = '$v'");
            $rowcount = mysqli_num_rows($results);
            
            if ($rowcount !== 0) {
              
              while ($rows = mysqli_fetch_array($results)) {
                
                if (((($fchara !== 'empty') && (
                    (($rows['chara1'] !== '') && (str_contains($fchara, $rows['chara1']))) ||
                    (($rows['chara2'] !== '') && (str_contains($fchara, $rows['chara2']))) ||
                    (($rows['chara3'] !== '') && (str_contains($fchara, $rows['chara3'])))
                  )) || ($fchara == 'empty')) && ($rows['include'] == 1)) {
                
                echo '<div class="imgbox"><img class="thumb" id="fanart', $rows['id'], '" src="../assets/', $v, '/pics/', $rows['filename'], '-thumb.png"></div>';
              }
              
              }
              
            }
            
            
}}}}}
          ?>
          
        </div>
        
      </div>
    
    </div>
    
  </div>
  
  
</div>

</body>
</html>