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
    		<input type="text" class="input" name="nom" placeholder="Nom" value="" style="width: 40%;" required>
       		<input type="text" class="input" name="prénom" placeholder="Prénom" style="width: 40%;" required>
       		<input type="text" class="input" name="prénom2" placeholder="deuxième prénom" style="width: 40%;">
    		<input type="email" class="input" name="email" placeholder="Email" style="width: 60%;" required>
    		<input type="password" class="input" name="password" placeholder="Mot de passe" style="width: 40%;" required>
    		<input type="age" class="input" name="age" placeholder="Age" style="width: 10%;" required>

    		<select class="input" name="genre" style="width: 20%;border-radius: 5px;" required>
    			<option value="" disabled selected>Genre</option>
  				<option value="Homme">Homme</option>
  				<option value="Femme">Femme</option>
  				<option value="Nonbinaire">non binaire</option>
    		</select>

    		<input type="text" class="input" name="profession" placeholder="Profession" style="width: 40%;border-radius: 5px;">
    		<input type="text" class="input" name="moyen" placeholder="Comment m'avez vous connu ?" style="width: 40%;border-radius: 5px;">

			<input id="button" type="submit" value="valider"/>
    	</form>
	</main>
	<?php
		if(isset($_POST['nom'])){
			
			$nom = $_POST['nom'];
			$prenom = $_POST['prénom'];
			$prenom2 = $_POST['prénom2'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$age = $_POST['age'];
			$genre = $_POST['genre'];
			$profession = $_POST['profession'];
			$moyen = $_POST['moyen'];


			$sql1 = $conn->prepare('INSERT INTO client(Nom,Prenom,Email,MotDePasse,Age,Genre,Moyen) VALUES (:nom,:prenom,:email,:password,:age,:genre,:moyen)');
			$sql1->execute(array('nom'=>$nom,'prenom'=>$prenom,'email' => $email,'password' => $password,'age' => $age,'genre' => $genre,'moyen' => $moyen));
			
			$check =$conn->prepare('SELECT NomProfession FROM profession WHERE NomProfession = ?');
			$check->execute(array($profession));
			if($check != $profession){
				$sql2 = $conn->prepare('INSERT INTO profession(NomProfession) VALUES (:profession)');
				$sql2->execute(array('profession' => $profession));
			}
			
			
			$sql3 = $conn->prepare('INSERT INTO professionclient(IdProfession,IdClient) VALUES (SELECT IdProfession FROM profession WHERE NomProfession = :profession),(SELECT IdClient FROM client WHERE Nom = :nom AND Prenom = :prenom AND Email = :email AND Age = :age)');
			$sql3->execute(array('profession' => $profession,'nom' => $nom,'prenom' => $prenom, 'email'=> $email, 'age' => $age));
			
		}
	?>
</body>
</html>
