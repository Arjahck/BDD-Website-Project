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
	<title>PSY Management - Modif consultation</title>
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
      <input type="date" class="input" name="date" style="width: 40%;">
      <input type="time" class="input" name="heure" style="width: 40%;">
      <input id="button" type="submit" name="valider" value="valider">
      <?php
        if(isset($_POST['valider']) AND $_POST['date']!= null AND $_POST['heure']!= null){
          $_SESSION['date'] = date("Y-m-d",strtotime($_POST['date']));
          $_SESSION['heure'] = $_POST['heure'].":00";

          $date = $_SESSION['date'];
          $heure = $_SESSION['heure'];

          $time = $conn->prepare("SELECT * FROM planning WHERE DateConsultation = ? AND Heure = ?");
          $time->execute(array($date,$heure));

          $check = $time->fetchAll();
          $time->closeCursor();

          if(count($check) == 0){
            echo "il n'existe pas de rendez vous pour ce créneau";
          }
          else{     

            echo "<br>";
            echo '<table id="planning">';
              echo "<tr>";
                echo "<th id='planning1erecase'></th>";
                echo "<th class='planningCaseHead'>Prenom</th>";
                echo "<th class='planningCaseHead'>Nom</th>";
              echo "</tr>";

              /*$client= $conn->prepare("SELECT Prenom, Nom FROM client cl
                                      JOIN consultationclient cc ON cl.IdClient = c.IdClient
                                      JOIN consultation ct on cc.IdClient = ct.IdClient
                                      WHERE DateConsultation = ? AND Heure = ?");*/
              $client= $conn->prepare("SELECT Prenom, Nom FROM Planning WHERE DateConsultation = ? AND Heure = ?");
              $client->execute(array($date,$heure));
              $data = $client->fetchAll();
              for($i=1 ; $i<count($data)+1 ; $i++){
                echo "<tr>";
                  echo "<td class='planningCaseHead'>client". $i.'</td>';
                  echo "<td class='planningCase'>". $data[$i-1][0] .'</td>';
                  echo "<td class='planningCase'>". $data[$i-1][1] .'</td>';
                echo "</tr>";
              }
              $client->closeCursor();
              
            echo "</table>";
          ?>
          
            <br>
          
              <input type="text" class="input" name="nom1" placeholder="nom1" style="width: 40%;">
              <input type="text" class="input" name="prenom1" placeholder="prenom1" style="width: 40%;">
              <input type="text" class="input" name="nom2" placeholder="nom2" style="width: 40%;">
              <input type="text" class="input" name="prenom2" placeholder="prenom2" style="width: 40%;">
              <input type="text" class="input" name="nom3" placeholder="nom3" style="width: 40%;">
              <input type="text" class="input" name="prenom3" placeholder="prenom3" style="width: 40%;">
              <input id="button" type="submit" name="modifier" value="modifier">
            </form>

            <?php 
             }    
            } 
              if(isset($_POST['modifier'])){

                $date = $_SESSION['date'];
                $heure = $_SESSION['heure'];

              
              if($_POST['nom1'] != null AND $_POST['prenom1'] != null){
                $nom1 = $_POST['nom1'];
                $prenom1 = $_POST['prenom1'];

                $sql1 = $conn->prepare("SELECT Prenom, Nom FROM client WHERE Nom = ? AND Prenom = ?");
                $sql1->execute(array($nom1,$prenom1));
                $data1 = $sql1->fetchAll();
                if(count($data1) == 0){
                  echo "le client1 que vous avez entré n'existe pas";
                }else{
                  $change1 = $conn->prepare("UPDATE consultationclient SET IdClient = (SELECT IdClient FROM client WHERE Prenom = :prenom AND Nom = :nom) WHERE IdConsultation = (SELECT IdConsultation FROM consultation WHERE DateConsultation = :dat AND Heure = :heure)");
                  $change1->execute(array('prenom'=>$prenom1,'nom'=>$nom1,'dat'=>$date,'heure'=>$heure));
                }
              }

              if($_POST['nom2'] != null AND $_POST['prenom2'] != null){
                $nom2 = $_POST['nom2'];
                $prenom2 = $_POST['prenom2'];

                $sql2 = $conn->prepare("SELECT Prenom, Nom FROM client WHERE Nom = ? AND Prenom = ?");
                $sql2->execute(array($nom2,$prenom2));
                $data2 = $sql2->fetchAll();
                if(count($data2) == 0){
                  echo "le client2 que vous avez entré n'existe pas";
                }else{
                  $change2 = $conn->prepare("UPDATE consultationclient SET IdClient = (SELECT IdClient FROM client WHERE Prenom = :prenom AND Nom = :Nom) WHERE IdConsultation = (SELECT IdConsultation FROM consultation WHERE DateConsultation = :dat AND Heure = :heure)");
                  $change2->execute(array('prenom'=>$prenom2,'nom'=>$nom2,'dat'=>$date,'heure'=>$heure));
                  echo "le client1 a été modifier avec succès par ".$prenom1." ".$nom1;
                }
              }

              if($_POST['nom3'] != null AND $_POST['prenom3'] != null){
                $nom3 = $_POST['nom3'];
                $prenom3 = $_POST['prenom3'];

                $sql3 = $conn->prepare("SELECT Prenom, Nom FROM client WHERE Nom = ? AND Prenom = ?");
                $sql3->execute(array($nom3,$prenom3));
                $data3 = $sql3->fetchAll();
                if(count($data3) == 0){
                  echo "le client3 que vous avez entré n'existe pas";
                }else{
                  $change3 = $conn->prepare("UPDATE consultationclient SET IdClient = (SELECT IdClient FROM client WHERE Prenom = :prenom AND Nom = :Nom) WHERE IdConsultation = (SELECT IdConsultation FROM consultation WHERE DateConsultation = :dat AND Heure = :heure)");
                  $change3->execute(array('prenom'=>$prenom3,'nom'=>$nom3,'dat'=>$date,'heure'=>$heure));
                }
              }

              unset($_SESSION['date']);
              unset($_SESSION['heure']);
            }
           
      ?>

  </main>
</body>
</html>