<?php
// Initialize the session
session_start(); 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

try{
	$conn = new PDO('mysql:host=localhost;port=3308;dbname=psy-base;charset=utf8','root', '');
}
catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="include/style.css" rel="stylesheet" type="text/css"/>
	<title>PSY Management - modifier profil</title>
</head>

<header>
	<ul id="navbar">
      <li class="navbar_li"><a class="navbar_a" href="#home">Home</a></li>
      <li class="navbar_li"><a class="navbar_a" href="planning_client.php">Planning</a></li>
      <li class="navbar_li"><a class="navbar_a" href="client.php">Mon Profil</a></li>
      <li class="navbar_li" style="float:right"><a class="navbar_a" style="background-color: #4CAF50"  href="login.php">déconnexion</a></li>
    </ul>
</header>
<body>
	<main id="mainBox">
		<form id="formulaire" method='post'>
    		<input type="text" class="input" name="nom" placeholder="Nom" style="width: 40%;">
       		<input type="text" class="input" name="prenom" placeholder="Prenom" style="width: 40%;">
       		<input type="text" class="input" name="prenom2" placeholder="Deuxieme prenom" style="width: 40%;">
    		<input type="email" class="input" name="email" placeholder="Email" style="width: 60%;">
    		<input type="password" class="input" name="password" placeholder="Mot de passe" style="width: 40%;">
    		<input type="age" class="input" name="age" placeholder="Age" style="width: 10%;">

    		<input type="text" class="input" name="profession" placeholder="Profession" style="width: 40%;border-radius: 5px;">

			<input id="button" type="submit" value="modifier"/>
    	</form>
	</main>
	<?php
	if (isset($_POST['nom'])){

		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$prenom2 = $_POST['prenom2'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$age = $_POST['age'];
		$profession = $_POST['profession'];

		if($nom != null){
			$sql1 = $conn->prepare('UPDATE client SET Nom = :nom WHERE Email = :username');
			$sql1->execute(array('nom'=>$nom,'username'=>$_SESSION["username"]));
			$sql1->closeCursor();
		}

		if($prenom != null){
			$sql2 = $conn->prepare('UPDATE client SET Prenom = :prenom WHERE Email = :username');
			$sql2->execute(array('prenom'=>$prenom,'username'=>$_SESSION["username"]));
			$sql2->closeCursor();
		}

		if($prenom2 != null){
			$sql3 = $conn->prepare('UPDATE client SET Prenom2 = :prenom2 WHERE Email = :username');
			$sql3->execute(array('prenom2'=>$prenom2,'username'=>$_SESSION["username"]));
			$sql3->closeCursor();
		}

		if($email != null){
			$sql4 = $conn->prepare('UPDATE client SET Email = :email WHERE Email = :username');
			$sql4->execute(array('email'=>$email,'username'=>$_SESSION["username"]));
			$sql4->closeCursor();
		}

		if($password != null){
			$sql5 = $conn->prepare('UPDATE client SET MotDePasse = :password WHERE Email = :username');
			$sql5->execute(array('password'=>$password,'username'=>$_SESSION["username"]));
			$sql5->closeCursor();
		}

		if($age != null){
			$sql6 = $conn->prepare('UPDATE client SET Age = :age WHERE Email = :username');
			$sql6->execute(array('age'=>$age,'username'=>$_SESSION["username"]));
			$sql6->closeCursor();
		}

		if($profession != null){
			$check = $conn->prepare('SELECT NomProfession FROM profession WHERE NomProfession = ?');
			$check->execute(array($profession));
			$check->closeCursor();

			if($check == null){
				$sql8 = $conn->prepare('INSERT INTO profession(NomProfession) VALUES (:profession)');
				$sql8->execute(array('profession' => $profession));
				$sql8->closeCursor();
			}

			$sql9 = $conn->prepare('INSERT INTO professionclient(IdProfession,IdClient) VALUES ((SELECT IdProfession FROM profession WHERE NomProfession = :profession),(SELECT IdClient FROM client WHERE Email = :username))');
			$sql9->execute(array('profession' => $profession,'username'=>$_SESSION["username"]));
			$sql9->closeCursor();
		}
	}
		

	?>

</body>
</html>