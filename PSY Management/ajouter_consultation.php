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
		<form id="formAddConsultation" method='post'>
    		<input type="text" class="input" name="nom1" placeholder="Nom client 1" value="" style="width: 40%;" required>
       		<input type="text" class="input" name="prénom1" placeholder="Prénom client 1" style="width: 40%;" required>
    		<input type="text" class="input" name="nom2" placeholder="Nom client 2" value="" style="width: 40%;">
       		<input type="text" class="input" name="prénom2" placeholder="Prénom client 2" style="width: 40%;">
    		<input type="text" class="input" name="nom3" placeholder="Nom client 3" value="" style="width: 40%;">
       		<input type="text" class="input" name="prénom3" placeholder="Prénom client 3" style="width: 40%;">

    		<input type="date" class="input" name="date"  style="width: 40%;border-radius: 5px;" required>
    		<input type="time" min="08:00:00" max="19:30:00" class="input" name="heure" placeholder="heure" style="width: 40%;" required>

			<input id="button" type="submit" value="valider"/>
    	</form>
	</main>
	<?php 
		function timeout($date){
			$sql= $conn->prepare('SELECT COUNT(*) FROM consultation WHERE DateConsultation = ?');
			$sql->execute($date);
			if($sql < 20){
				return false;
			}else{
				return true;
			}
		}

		function isfree($date,$heure){
			$sql = $conn->prepare('SELECT COUNT(*) FROM consultation WHERE DateConsultation = ? AND Heure = ?');
			$sql->execute($date,$heure);
			if($sql == 0){
				return true;
			}else{
				return false;
			}
		}
	?>
	<?php
		if(isset($_POST['nom1'])){
			
			$nom1 = $_POST['nom1'];
			$prenom1 = $_POST['prénom1'];

			if((isset($_POST['nom2'])) AND (isset($_POST['prénom2']))){
				$nom2 = $_POST['nom2'];
				$prenom2 = $_POST['prénom2'];
			}

			if((isset($_POST['nom3'])) AND (isset($_POST['prénom3']))){
				$nom3 = $_POST['nom3'];
				$prenom3 = $_POST['prénom3'];
			}

			$date = $_POST['date'];
			$heure= $_POST['heure'];

			if(isfree($date,$heure) == false){
				echo 'le créneau est déja pris';
			}
			else{
				if(timeout($date)==true){
					echo 'Vous avez atteint le nombre maximal de consultation pour ce jour';
				}else{
					$sql1 = $conn->prepare('INSERT INTO consultation(DateConsultation,Heure,Prix,Reglement,Anxiete) VALUES (:dat,:heure,NULL,NULL,NULL)');
					$sql1->execute(array('dat' => $date,'heure'=> $heure));	

					$sql2 = $conn->prepare('INSERT INTO consultationclient(IdConsultation,IdClient) VALUES ((SELECT IdConsultation FROM consultation WHERE DateConsultation = :dat AND Heure = :heure),(SELECT IdClient FROM client WHERE Nom = :nom AND Prenom = :prenom))');
					$sql2->execute(array('dat' => $date,'heure'=> $heure,'nom' => $nom1,'prenom' => $prenom1));
					
					if((isset($_POST['nom2'])) AND (isset($_POST['prenom2']))){
						$sql3 = $conn->prepare('INSERT INTO consultationclient(IdConsultation,IdClient) VALUES ((SELECT IdConsultation FROM consultation WHERE DateConsultation = :dat AND Heure = :heure),(SELECT IdClient FROM client WHERE Nom = :nom AND Prenom = :prenom))');
						$sql3->execute(array('dat' => $date,'heure'=> $heure,'nom' => $nom2,'prenom' => $prenom2));
					}

					if((isset($_POST['nom3'])) AND (isset($_POST['prenom3']))){
						$sql4 = $conn->prepare('INSERT INTO consultationclient(IdConsultation,IdClient) VALUES ((SELECT IdConsultation FROM consultation WHERE DateConsultation = :dat AND Heure = :heure),(SELECT IdClient FROM client WHERE Nom = :nom AND Prenom = :prenom))');
						$sql4->execute(array('dat' => $date,'heure'=> $heure,'nom' => $nom3,'prenom' => $prenom3));
					}	
				}
			}
		}
		
	?>


</body>
</html>
