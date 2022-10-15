<?php

session_start();

if (empty($_SESSION["ultimate"])) {
  header("location:../index.php");
}

include("conn.php");

if (!$_GET) {
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

  $vtag = $rowv['tag'];
  $doclist = $vtag.'_docs';

$result = mysqli_query($db,"SELECT * FROM $doclist");
if ($result == FALSE) {
  $errmsg = "The specified verse lacks a document library.";
  include 'error.php';
} else {
$rows[] = mysqli_fetch_all($result, $mode = MYSQLI_ASSOC );

$yearopt = array_column($rows['0'], 'date created');
$typeopt = array_column($rows['0'], 'doc type');
$statopt = array_column($rows['0'], 'status');
$groupopt = array_column($rows['0'], 'charagroup');
$charaopt = array_column($rows['0'], 'characters');
$shipopt = array_column($rows['0'], 'ships');
$tagopt = array_column($rows['0'], 'tags');

$maxyears = [];
foreach ($yearopt as $key => $value) {
  $newyear = substr($value, 0, -3);
  array_push($maxyears, $newyear);
}
$years = array_unique($maxyears);

$types = array_unique($typeopt);

$statuses = array_unique($statopt);

$groupsn = array_unique($groupopt);
$groups = array_filter($groupsn);

$maxcharas = [];
foreach ($charaopt as $key => $value) {
  if (str_contains($value, ', ')) {
    $newcharas = explode(', ', $value);
    foreach ($newcharas as $key => $value) {
      array_push($maxcharas, $value);
    }
  } elseif ($value !== NULL) {
    array_push($maxcharas, $value);
  }
}
$charas = array_unique($maxcharas);

$maxships = [];
foreach ($shipopt as $key => $value) {
  if (str_contains($value, ', ')) {
    $newships = explode(', ', $value);
    foreach ($newships as $key => $value) {
      array_push($maxships, $value);
    }
  } elseif ($value !== NULL) {
    array_push($maxships, $value);
  }
}
$ships = array_unique($maxships);

$maxtags = [];
foreach ($tagopt as $key => $value) {
  if (str_contains($value, ', ')) {
    $newtags = explode(', ', $value);
    foreach ($newtags as $key => $value) {
      array_push($maxtags, $value);
    }
  } elseif ($value !== NULL) {
    array_push($maxtags, $value);
  }
}
$tags = array_unique($maxtags);


function clean($string) {
  return preg_replace('/[^A-Za-z0-9-]/', '', $string);
}

if (!$_GET) {
  $fyear = 'empty';
  $ftype = 'empty';
  $fstatus = 'empty';
  $fgroup = 'empty';
  $fchara = 'empty';
  $fship = 'empty';
  $ftag = 'empty';
  $showall = 'yes';
} else {
  
  $showall = 'no';

if (!isset($_GET['yr'])) {
  $fyear = 'empty';
} elseif (preg_match('/[^-a-z0-9]/i', ($_GET['yr']))) {
  $fyear = 'empty';
} else {
  $fyear = clean($_GET['yr']);
}

if (!isset($_GET['type'])) {
  $ftype = 'empty';
} elseif (preg_match('/[^-a-z0-9]/i', ($_GET['type']))) {
  $ftype = 'empty';
} else {
  $ftype = clean($_GET['type']);
}

if (!isset($_GET['st'])) {
  $fstatus = 'empty';
} elseif (preg_match('/[^-a-z0-9]/i', ($_GET['st']))) {
  $fstatus = 'empty';
} else {
  $fstatus = clean($_GET['st']);
}

if (!isset($_GET['cg'])) {
  $fgroup = 'empty';
} elseif (preg_match('/[^-a-z0-9]/i', ($_GET['cg']))) {
  $fgroup = 'empty';
} else {
  $fgroup = clean($_GET['cg']);
}

if (!isset($_GET['ch'])) {
  $fchara = 'empty';
} elseif (preg_match('/[^-a-z0-9]/i', ($_GET['ch']))) {
  $fchara = 'empty';
} else {
  $fchara = clean($_GET['ch']);
}

if (!isset($_GET['ship'])) {
  $fship = 'empty';
} elseif (preg_match('/[^-a-z0-9]/i', ($_GET['ship']))) {
  $fship = 'empty';
} else {
  $fship = clean($_GET['ship']);
}

if (!isset($_GET['tag'])) {
  $ftag = 'empty';
} elseif (preg_match('/[^-a-z0-9]/i', ($_GET['tag']))) {
  $ftag = 'empty';
} else {
  $ftag = clean($_GET['tag']);
}

}

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

});

</script>
  
</head>
<body>

<div class="verticontainer">
  
  <div class="bantitle">
    <div class="smalltitle">Library<?php if ($verse !== 'empty') { echo ": ", $verse; } ?></div>
    <a href="verse.php?v=<?php echo $vtag; ?>" class="return">⮭</a>
  </div>
  
  <div class="barenabler">
    <div class="sidebar">
      <?php
      echo '
      <div class="optcat" tabindex="0" id="year">➢ year</div>
      <div class="optbox collapse collapsed" id="year">';
      
      foreach ($years as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($fyear, $shortvalue) !== false) {
          $button = 'actbtn';
        } else {
          $button = 'dormbtn';
        }
        
        echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&yr=', $fyear, $shortvalue; } else { echo '&yr=', $shortvalue; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
        echo "' id=\"", $shortvalue, "\" class=\"year sidebtn ", $button, "\"'>", $value, "</a>";
      }
      
      if ($fyear == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='library.php?v=", $vtag;
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
      echo "' id='anyyear' class='sidebtn ", $button, "'>any year</a>";
      
      echo '</div>';
      
      echo '
      <div class="optcat" tabindex="0" id="type">➢ document type</div>
      <div class="optbox collapse collapsed" id="type">';
      
      foreach ($types as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($ftype, $shortvalue) !== false) {
          $button = 'actbtn';
        } else {
          $button = 'dormbtn';
        }
        
        echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&yr=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype, $shortvalue; } else { echo '&type=', $shortvalue; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
        echo "' id=\"", $shortvalue, "\" class=\"type sidebtn ", $button, "\"'>", $value, "</a>";
      }
      
      if ($ftype == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&type=', $fyear; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
      echo "' id='anytype' class='sidebtn ", $button, "'>any type</a>";
      
      echo '</div>';
      
      echo '
      <div class="optcat" tabindex="0" id="status">➢ status</div>
      <div class="optbox collapse collapsed" id="status">';
      
      foreach ($statuses as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($fstatus, $shortvalue) !== false) {
          $button = 'actbtn';
        } else {
          $button = 'dormbtn';
        }
        
        echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&yr=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus, $shortvalue; } else { echo '&st=', $shortvalue; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
        echo "' id=\"", $shortvalue, "\" class=\"status sidebtn ", $button, "\"'>", $value, "</a>";
      }
      
      if ($fstatus == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&st=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
      echo "' id='anystatus' class='sidebtn ", $button, "'>any status</a>";
      
      echo '</div>';
      
      echo '
      <div class="optcat" tabindex="0" id="group">➢ character group</div>
      <div class="optbox collapse collapsed" id="group">';
      
      foreach ($groups as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($fgroup, $shortvalue) !== false) {
          $button = 'actbtn';
        } else {
          $button = 'dormbtn';
        }
        
        echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&yr=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup, $shortvalue; } else { echo '&cg=', $shortvalue; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
        echo "' id=\"", $shortvalue, "\" class=\"group sidebtn ", $button, "\"'>", $value, "</a>";
      }
      
      if ($fgroup == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&cg=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
      echo "' id='anygroup' class='sidebtn ", $button, "'>any group</a>";
      
      echo '</div>';
      
      echo '
      <div class="optcat" tabindex="0" id="chara">➢ character</div>
      <div class="optbox collapse collapsed" id="chara">';
      
      foreach ($charas as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($fchara, $shortvalue) !== false) {
          $button = 'actbtn';
        } else {
          $button = 'dormbtn';
        }
        
        echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&yr=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara, $shortvalue; } else { echo '&ch=', $shortvalue; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
        echo "' id=\"", $shortvalue, "\" class=\"chara sidebtn ", $button, "\"'>", $value, "</a>";
      }
      
      if ($fchara == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&type=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
      echo "' id='anychara' class='sidebtn ", $button, "'>any character</a>";
      
      echo '</div>';
      
      echo '
      <div class="optcat" tabindex="0" id="ship">➢ ship</div>
      <div class="optbox collapse collapsed" id="ship">';
      
      foreach ($ships as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($fship, $shortvalue) !== false) {
          $button = 'actbtn';
        } else {
          $button = 'dormbtn';
        }
        
        echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&yr=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship, $shortvalue; } else { echo '&ship=', $shortvalue; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
        echo "' id=\"", $shortvalue, "\" class=\"ship sidebtn ", $button, "\"'>", $value, "</a>";
      }
      
      if ($fship == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&type=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag; }
      echo "' id='anyship' class='sidebtn ", $button, "'>any ship</a>";
      
      echo '</div>';
      
      echo '
      <div class="optcat" tabindex="0" id="tag">➢ tag</div>
      <div class="optbox collapse collapsed" id="tag">';
      
      foreach ($tags as $value) {
        
        $shortvalue = (str_replace(' ', '', strtolower($value)));
        
        if (str_contains($ftag, $shortvalue) !== false) {
          $button = 'actbtn';
        } else {
          $button = 'dormbtn';
        }
        
        echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&yr=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
        if ($ftag !== 'empty') { echo '&tag=', $ftag, $shortvalue; } else { echo '&tag=', $shortvalue; }
        echo "' id=\"", $shortvalue, "\" class=\"tag sidebtn ", $button, "\"'>", $value, "</a>";
      }
      
      if ($ftag == 'empty') {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<a href='library.php?v=", $vtag;
        if ($fyear !== 'empty') { echo '&tag=', $fyear; }
        if ($ftype !== 'empty') { echo '&type=', $ftype; }
        if ($fstatus !== 'empty') { echo '&st=', $fstatus; }
        if ($fgroup !== 'empty') { echo '&cg=', $fgroup; }
        if ($fchara !== 'empty') { echo '&ch=', $fchara; }
        if ($fship !== 'empty') { echo '&ship=', $fship; }
      echo "' id='anytag' class='sidebtn ", $button, "'>any tag</a>";
      
      echo '</div>';
      
      if (($fyear == 'empty') && ($ftype == 'empty') && ($fstatus == 'empty') && ($fgroup == 'empty') && ($fchara == 'empty') && ($fship == 'empty') && ($ftag == 'empty')) {
        $button = 'actbtn';
      } else {
        $button = 'dormbtn';
      }
      
      echo "<br><a href='library.php?v=", $vtag, "' id='alldocs' class='sidebtn ", $button, "'>all documents</a>"
      ?>
    </div>
    <div class="bookcase">
      
      <?php
        
      $resultco = mysqli_query($db,"SELECT max(id) FROM $doclist");
      $rowco = mysqli_fetch_array($resultco);
      
      $id = $rowco['max(id)'];
      
      do {
      
      $result = mysqli_query($db,"SELECT * FROM $doclist WHERE id = '$id'");
      $row = mysqli_fetch_array($result);
      
      if ($row == NULL) {
        $id--;
      } else {
        
        $yearmon = $row['date created'];
        $trueyear = substr($yearmon, 0, -3);
        if ($fyear !== "empty") {
          if ((str_contains($fyear, $trueyear) !== false) && ($trueyear !== NULL) && ($trueyear !== '')) {
            $showyear = TRUE;
          } else {
            $showyear = FALSE;
          }
        } else {
          $showyear = TRUE;
        }
        
        $truetype = $row['doc type'];
        if ($ftype !== "empty") {
          if ((str_contains($ftype, $truetype) !== false) && ($truetype !== NULL) && ($truetype !== '')) {
            $showtype = TRUE;
          } else {
            $showtype = FALSE;
          }
        } else {
          $showtype = TRUE;
        }
        
        $truestatus = $row['status'];
        if ($fstatus !== "empty") {
          if ((str_contains($fstatus, $truestatus) !== false) && ($truestatus !== NULL) && ($truestatus !== '')) {
            $showstatus = TRUE;
          } else {
            $showstatus = FALSE;
          }
        } else {
          $showstatus = TRUE;
        }
        
        if (($row['charagroup'] !== NULL) && ($row['charagroup'] !== '')) {
        $grouparray = $row['charagroup'];
        if (str_contains($grouparray, ',')) {
        $newgroups = explode(', ', $grouparray);
        foreach ($newgroups as $key => $value) {
        if ($fgroup !== "empty") {
          if (str_contains($fgroup, $value) == false) {
            $showgroup = FALSE;
          } else {
            $showgroup = TRUE;
            break;
          }
        } else {
          $showgroup = TRUE;
        }
        }
        } else {
          if ($fgroup !== "empty") {
          if (str_contains($fgroup, $grouparray) == false) {
            $showgroup = FALSE;
          } else {
            $showgroup = TRUE;
          }
        } else {
          $showgroup = TRUE;
        }
        }
        } else {
          if ($fgroup !== "empty") {
            $showgroup = FALSE;
          } else {
            $showgroup = TRUE;
          }
        }
        
        if (($row['characters'] !== NULL) && ($row['characters'] !== '')) {
        $chararray = $row['characters'];
        if (str_contains($chararray, ',')) {
        $newcharas = explode(', ', $chararray);
        foreach ($newcharas as $key => $value) {
        if ($fchara !== "empty") {
          if (str_contains($fchara, $value) == false) {
            $showchara = FALSE;
          } else {
            $showchara = TRUE;
            break;
          }
        } else {
          $showchara = TRUE;
        }
        }
        } else {
          if ($fchara !== "empty") {
          if (str_contains($fchara, $chararray) == false) {
            $showchara = FALSE;
          } else {
            $showchara = TRUE;
          }
        } else {
          $showchara = TRUE;
        }
        }
        } else {
          if ($fchara !== "empty") {
            $showchara = FALSE;
          } else {
            $showchara = TRUE;
          }
        }
        
        if (($row['ships'] !== NULL) && ($row['ships'] !== '')) {
        $shiparray = $row['ships'];
        if (str_contains($shiparray, ',')) {
        $newships = explode(', ', $shiparray);
        foreach ($newships as $key => $value) {
        if ($fship !== "empty") {
          if (str_contains($fship, $value) == false) {
            $showship = FALSE;
          } else {
            $showship = TRUE;
            break;
          }
        } else {
          $showship = TRUE;
        }
        }
        } else {
          if ($fship !== "empty") {
          if (str_contains($fship, $shiparray) == false) {
            $showship = FALSE;
          } else {
            $showship = TRUE;
          }
        } else {
          $showship = TRUE;
        }
        }
        } else {
          if ($fship !== "empty") {
            $showship = FALSE;
          } else {
            $showship = TRUE;
          }
        }
        
        if (($row['tags'] !== NULL) && ($row['tags'] !== '')) {
        $tagarray = $row['tags'];
        if (str_contains($tagarray, ',')) {
        $newtags = explode(', ', $tagarray);
        foreach ($newtags as $key => $value) {
        if ($ftag !== "empty") {
          if (str_contains($ftag, $value) == false) {
            $showtag = FALSE;
          } else {
            $showtag = TRUE;
            break;
          }
        } else {
          $showtag = TRUE;
        }
        }
        } else {
          if ($ftag !== "empty") {
          if (str_contains($ftag, $tagarray) == false) {
            $showtag = FALSE;
          } else {
            $showtag = TRUE;
          }
        } else {
          $showtag = TRUE;
        }
        }
        } else {
          if ($ftag !== "empty") {
            $showtag = FALSE;
          } else {
            $showtag = TRUE;
          }
        }
        
        if (($showyear == TRUE) && ($showtype == TRUE) && ($showstatus == TRUE) && ($showgroup == TRUE) && ($showchara == TRUE) && ($showship == TRUE) && ($showtag == TRUE)) {
        
        echo '<a href="document.php?v=', $vtag, '&id=', $row['id'], '" class="document darkbtn">
        <div class="dochead">
          <div class="doctitle">', $row['title'], '</div>
          <div class="docstat">', $row['date created'], '</div>
          <div class="docstat">', $row['wordcount'], ' words</div>
          <div class="docstat">', $row['status'], '</div>
        </div>
        <br>
        <div class="tagcase">';
          if (isset($row['charagroup'])) { echo $row['charagroup'], ', '; }
          if (isset($row['characters'])) { echo $row['characters'], ', '; }
          if (isset($row['ships'])) { echo $row['ships'], ', '; }
          if (isset($row['tags'])) { echo $row['tags']; }
        echo '</div>
      </a>';
        
        }
        
        $id--;
        }
      
      }while ($id > 0);
      
}}}
      ?>
      
    </div>
  </div>
  
  
</div>

</body>
</html>