<?php
//Initialize the session
session_start(); 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

try{ $conn = new PDO('mysql:host=localhost;port=3308;dbname=psy-base;charset=utf8','root', ''); }
catch (Exception $e){ die('Erreur : ' . $e->getMessage()); }

unset($_SESSION['compteur_suiv']);
unset($_SESSION['compteur_prec']);
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

<body>
	<main id="mainBox">
   	<form id="formulaire" method='post'>
      <input type="date" class="input" name="date" placeholder="date" style="width: 40%;" required>
      <input type="time" min="08:00:00" max="19:30:00" class="input" step="1800" name="heure"  style="width: 40%;" required>

      <input id="button" type="submit" value="supprimer"/>
    </form>

    <?php  
      if(isset($_POST['date']) AND isset($_POST['date'])){

      $date = $_POST['date'];
      $heure = $_POST['heure'].':00';

      $sql = $conn->prepare('DELETE FROM consultation WHERE DateConsultation = :dat AND Heure = :heure');
      $sql->execute(array('dat' => $date,'heure'=>$heure));
    }
   
    ?>
	</main>
</body>
</html>