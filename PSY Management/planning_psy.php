<?php
//Initialize the session
session_start(); 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

try
{
  $conn = new PDO('mysql:host=localhost;port=3308;dbname=psy-base;charset=utf8','root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
ini_set('display_errors', 'off');
?>


<!DOCTYPE html>
<html>
<head>
  <link href="include/style.css" rel="stylesheet" type="text/css"/>
	<title>PSY Management - Planning</title>
</head>

<header>
	<ul id="navbar">
      <li class="navbar_li"><a class="navbar_a" href="planning_psy.php">Planning</a></li>
      <li class="navbar_li"><a class="navbar_a" href="search.php">Recherche client</a></li>
      <li class="navbar_li"><a class="navbar_a" href="ajouter_client.php">Ajouter client</a></li>
      <li class="navbar_li"><a class="navbar_a" href="ajouter_consultation.php">Ajouter consultation</a></li>
      <li class="navbar_li"><a class="navbar_a" href="set_consultation.php">Fin consultation</a></li>
      <li class="navbar_li"><a class="navbar_a" href="modif_consultation.php">Modifier consultation</a></li>
      <li class="navbar_li"><a class="navbar_a" href="del_consultation.php">suppr consultation</a></li>
      <li class="navbar_li" style="float:right"><a class="navbar_a" style="background-color: #4CAF50"  href="login.php">déconnexion</a></li>
  </ul>
</header>

  <form id="headerPlanning" method="POST">
    <input class="buttonPlanning" type="submit" name="precedent" value="precedent">

    <?php
      $today = date('Y-m-d');
      $_SESSION['week_number']=date('W', strtotime($today));

      if(isset($_POST['suivant']) && ($_SESSION['week_number']+$_SESSION['compteur_suiv']< 53 )) //semaine suivante
      {
        $_SESSION['compteur_suiv']++;
        $_SESSION['compteur_prec'] = 0-$_SESSION['compteur_suiv'];
        $_SESSION['week_number']+=$_SESSION['compteur_suiv'];
      }

      if(isset($_POST['precedent']) && ($_SESSION['week_number']-$_SESSION['compteur_prec'] > 1)) //semaine précèdente
      {
        $_SESSION['compteur_prec']++;
        $_SESSION['compteur_suiv'] = 0-$_SESSION['compteur_prec'];
        $_SESSION['week_number']-=$_SESSION['compteur_prec'];
      }  
    ?>

    <h2>semaine <?php echo $_SESSION['week_number'];?></h2>

    <input class="buttonPlanning" type="submit" name="suivant" value="suivant">

  </form>
  

  

  
<body>
  <?php

  $lundi = (get_lundi_vendredi_from_week($_SESSION['week_number'],date('Y'),1));
  $mardi = (get_lundi_vendredi_from_week($_SESSION['week_number'],date('Y'),2));
  $mercredi = (get_lundi_vendredi_from_week($_SESSION['week_number'],date('Y'),3));
  $jeudi = (get_lundi_vendredi_from_week($_SESSION['week_number'],date('Y'),4));
  $vendredi = (get_lundi_vendredi_from_week($_SESSION['week_number'],date('Y'),5));
  $samedi = (get_lundi_vendredi_from_week($_SESSION['week_number'],date('Y'),6));


  $sql = $conn->prepare("SELECT * FROM planning WHERE DateConsultation >= :lundi AND DateConsultation <= :samedi");

  $fmtlundi = change_format(str_replace("/", "-",$lundi));
  $fmtmardi = change_format(str_replace("/", "-",$mardi));
  $fmtmercredi = change_format(str_replace("/", "-",$mercredi));
  $fmtjeudi = change_format(str_replace("/", "-",$jeudi));
  $fmtvendredi = change_format(str_replace("/", "-",$vendredi));
  $fmtsamedi = change_format(str_replace("/", "-",$samedi));

  $sql->execute(array('lundi'=>$fmtlundi,'samedi'=>$fmtsamedi));

  $data = $sql->fetchAll();
  $sql->closeCursor();

  for($k = 0 ; $k<count($data);$k++){ 
    $data[$k][1] = substr($data[$k][1], 0, -3);
  }
  

  echo "<table id='planning'>";
    echo "<tr>";
      echo "<th id='planning1erecase'></th>";
      echo "<th class='planningCaseHead'>Lundi ".$lundi."</th>";
      echo "<th class='planningCaseHead'>Mardi ".$mardi."</th>";
      echo "<th class='planningCaseHead'>Mercredi ".$mercredi."</th>";
      echo "<th class='planningCaseHead'>Jeudi ".$jeudi."</th>";
      echo "<th class='planningCaseHead'>Vendredi ".$vendredi."</th>";
      echo "<th class='planningCaseHead'>Samedi ".$samedi."</th>";
    echo "</tr>";
    $half=false;
    $i=8;
    $j=0;

  if(count($data)>0){
    for($l = 0 ; $l<count($data);$l++){
    while ($i< 20) {
      if ($half == false)
      {
        $hour = $i.":00";
        echo "<tr>";
          echo "<td class='planningCaseHead'>".$i.":00</td>";

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtlundi){
            echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>";
          }else{
            echo "<td class='planningCase'></td>";
          }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtmardi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtmercredi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtjeudi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtvendredi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtsamedi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

        echo "</tr>";
        $half = true;
      }
      else
      {
        $hour = $i.":30";
        echo "<tr>";
          echo "<td class='planningCaseHead'>".$i.":30</td>";

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtlundi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtmardi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtmercredi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtjeudi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtvendredi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

          if($data[$l][1] == $hour AND $data[$l][0] == $fmtsamedi){ echo "<td class='planningCase'>".$data[$l][2]." ".$data[$l][3]."</td>"; }
          else{ echo "<td class='planningCase'></td>"; }

        echo "</tr>";
        $half = false;
        $i++;
      } 
    }
  }
}else{
  while ($i< 20) {
      if ($half == false)
      {
        $hour = $i.":00";
        echo "<tr>";
          echo "<td class='planningCaseHead'>".$hour."</td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
        echo "</tr>";
        $half = true;
      }
      else
      {
        $hour = $i.":30";
        echo "<tr>";
          echo "<td class='planningCaseHead'>".$hour."</td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
          echo "<td class='planningCase'></td>";
        echo "</tr>";
        $half = false;
        $i++;
      } 
    }
  }
  
    

  echo "</table>";

  
  function get_lundi_vendredi_from_week($week,$year,$nb,$format="d/m")      //retourne les dates en fonction du numéro de la semaine
    {
      $firstDayInYear = date("N",mktime(0,0,0,1,1,$year));

      if ($firstDayInYear<5){
        $shift=-($firstDayInYear-1)*86400;        
      }
      else{
        $shift=(8-$firstDayInYear)*86400;
      }
      if ($week>1) {
        $weekInSeconds=($week-1)*604800; 
      }
      else{
        $weekInSeconds=0;
      } 

      
      if($nb==1)      //lundi
      {
        $timestamp=mktime(0,0,0,1,1,$year)+$weekInSeconds+$shift;
        return (date($format,$timestamp));
      }
      if($nb==2)      //mardi
      {
        $timestamp=mktime(0,0,0,1,2,$year)+$weekInSeconds+$shift;
        return (date($format,$timestamp));
      }   
      if($nb==3)      //mercredi
      {
        $timestamp=mktime(0,0,0,1,3,$year)+$weekInSeconds+$shift;
        return (date($format,$timestamp));
      }
      if($nb==4)      //jeudi
      {
        $timestamp=mktime(0,0,0,1,4,$year)+$weekInSeconds+$shift;
        return (date($format,$timestamp));
      }
      if($nb==5)      //vendredi
      {
        $timestamp=mktime(0,0,0,1,5,$year)+$weekInSeconds+$shift;
        return (date($format,$timestamp));
      }
      if($nb==6)      //samedi
      {
        $timestamp=mktime(0,0,0,1,6,$year)+$weekInSeconds+$shift;
        return (date($format,$timestamp));
      }
      if($nb==7)      //dimanche
      {
        $timestamp=mktime(0,0,0,1,7,$year)+$weekInSeconds+$shift;
        return (date($format,$timestamp));
      }
    }

    function change_format($date) {
      $vraidate = $date.'-'.date('Y');
      return date("Y-m-d",strtotime($vraidate));
    }
  ?>
</body>
</html>