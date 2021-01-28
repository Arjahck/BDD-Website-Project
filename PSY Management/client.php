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

?>
<!DOCTYPE html>
<html>
<head>
	<link href="include/style.css" rel="stylesheet" type="text/css"/>
	<title>PSY Management - client</title>
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
	<main>
		<form id="form">
			<?php
			$sql = $conn->prepare("SELECT Nom,Prenom,Email,Age,Genre,Moyen FROM client WHERE  Email = ? ");
			$sql->execute(array($_SESSION["username"]));
			
			while ($data1 = $sql->fetch()){ 

				echo '<div class="infoclient"> Nom : '. $data1[0]. '</div><br>';
				echo '<div class="infoclient"> Prénom : '. $data1[1]. '</div><br>';
				echo '<div class="infoclient"> Email : '. $data1[2]. '</div><br>';
				echo '<div class="infoclient"> Age : '. $data1[3]. '</div><br>';
				echo '<div class="infoclient"> Genre : '. $data1[4]. '</div><br>';
				echo '<div class="infoclient"> Découverte du psy par : '.  $data1[5]. '</div><br>';
				
        	}
        	$count = 0;

        	$profession = $conn->prepare("SELECT NomProfession FROM profession pr 
            		JOIN professionclient pc on pr.IdProfession = pc.IdProfession
            		JOIN client cl on pc.IdClient = cl.IdClient
            		WHERE Email = ?  ORDER BY pc.IdPC DESC");

            $profession->execute(array($_SESSION["username"]));
            
        	while ($data2 = $profession->fetch()){
        		if($count == 0){
        			echo '<div class="infoprofession"> Profession actuelle: '. $data2[0]. '</div><br>';
        			echo '<div class="infoprofession">Profession(s) anterieur(s):</div><br>';
        			$count+=1;
        		}
        		else{
        			echo '<div class="infoprofession">'.$data2[0].'</div><br>';
        		}				
        	}
        	
        	$sql->closeCursor();


        	?>
			<a id="button" href="modif_client.php">modifier</a>
		</form>
	</main>
</body>
</html>