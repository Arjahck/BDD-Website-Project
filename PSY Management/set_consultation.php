<?php
// Initialize the session
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
unset($_SESSION['compteur_suiv']);
unset($_SESSION['compteur_prec']);
?>
<!DOCTYPE html>
<html>
<head>
  <link href="include/style.css" rel="stylesheet" type="text/css"/>
  <title>PSY Management - ajouter client</title>
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
        <input type="time" class="input" name="heure" placeholder="heure" style="width: 40%;" required>
        <input type="text" class="input" name="prix" placeholder="prix" style="width: 40%;" required>
        <input type="text" class="input" name="reglement" placeholder="reglement" style="width: 40%;" required>
        <input type="text" class="input" name="anxiete" placeholder="anxiete" style="width: 40%;" required>
        <input type="text" class="input" name="remarques" placeholder="remarques..." style="width: 100%;" required>
        <input id="button" type="submit" value="valider"/>
    </form>
  </main>
  <?php
    if(isset($_POST['prix'])){
      $prix = $_POST['prix'];
      $reglement = $_POST['reglement'];
      $anxiete = $_POST['anxiete'];
      $remarques = $_POST['remarques'];
      $date = $_POST['date'];
      $heure = $_POST['heure'];

      $verif = $conn->prepare('SELECT * FROM consultation WHERE DateConsultation= :dat AND Heure= :heure');
      $verif->execute(array('dat'=>$date,'heure'=>$heure)); 
      $data = $verif->fetchAll(); 

      if(count($data)>0){   //verification : est-ce que la date entrée par la psy correspond à une consultation

        $sql = $conn->prepare('UPDATE consultation SET Prix= :prix, Reglement= :reglement, Anxiete= :anxiete, Remarques = :remarques WHERE DateConsultation= :dat AND Heure= :heure');

        $sql->execute(array('prix' => $prix,'reglement'=>$reglement,'anxiete'=>$anxiete,'dat'=>$date,'heure'=>$heure, 'remarques' => $remarques));

      }else{

        echo "il n'y a pas de rendez vous correspondant à cette date";

      }

    }
    
  ?>
</body>
</html>
