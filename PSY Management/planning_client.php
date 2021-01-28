<?php
//Initialize the session
session_start(); 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

try
{
  $conn = new PDO('mysql:host=localhost;dbname=psy-base;charset=utf8','root', '');
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
	<title>PSY Management - Planning</title>
</head>
<style type="text/css">

</style>

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
   		<table id="planning">
 				<tr>
 					<th class='planningCaseHead'>Date</th>
          <th class='planningCaseHead'>Heure</th>
          <th class='planningCaseHead'>Nom</th>
          <th class='planningCaseHead'>Prenom</th>
 				</tr>

         <?php
         $sql = $conn->prepare("SELECT * FROM planning WHERE Nom = (SELECT Nom FROM client WHERE Email = ?)");
         $sql->execute(array($_SESSION["username"]));
       while($data = $sql->fetch()){ 
        
        echo '<tr>';
          echo "<td class='planningCase'>".  $data[0] .'</td>';
          echo "<td class='planningCase'>". $data[1] .'</td>';
          echo "<td class='planningCase'>". $data[2] .'</td>';
          echo "<td class='planningCase'>".  $data[3] .'</td>';
        echo'</tr>';
        }

      $sql->closeCursor();
      ?>
          
		</table>
	</main>
</body>
</html>