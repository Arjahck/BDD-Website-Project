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
	<title>PSY Management - Search</title>
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
	<main>
    <form method="POST">
      <input id="Recherche" type="text" name="search" placeholder="Recherche..">
      <input id="button" type="submit" value="valider"/>
    </form>
    
      <?php
      
      if (isset($_POST['search'])){
        $search = $_POST['search'];

        

        $sql = $conn->prepare("SELECT Nom,Prenom,Email,Age,Genre,Moyen FROM client WHERE  Email = ? OR Nom = ? OR Prenom = ?");
        $sql->execute(array($search,$search,$search));
?>
<form id="form">
<?php
        while ($data1 = $sql->fetch()){ 

          echo '<div class="infoclient"> Nom : '. $data1[0]. '</div><br>';
          echo '<div class="infoclient"> Prénom : '. $data1[1]. '</div><br>';
          echo '<div class="infoclient"> Email : '. $data1[2]. '</div><br>';
          echo '<div class="infoclient"> Age : '. $data1[3]. '</div><br>';
          echo '<div class="infoclient"> Genre : '. $data1[4]. '</div><br>';
          echo '<div class="infoclient"> Découverte du psy par : '.  $data1[5]. '</div><br>';
          
        }
        $sql->closeCursor();

        $profession = $conn->prepare("SELECT NomProfession FROM profession pr 
                JOIN professionclient pc on pr.IdProfession = pc.IdProfession
                JOIN client cl on pc.IdClient = cl.IdClient
                WHERE Email = ? OR Nom = ? OR Prenom = ? ORDER BY pc.IdPC DESC");
        $profession->execute(array($search,$search,$search));

        while ($data2 = $profession->fetch()){
          echo '<div class="infoprofession"> Profession : '.$data2[0]. '</div><br>';
        }
      }

      ?>      
    </form>

        <?php
          if (isset($_POST['search'])){
?>


<?php
          $sql = $conn->prepare("SELECT DateConsultation, Heure, Prix, Reglement,Anxiete,Remarques FROM consultation co 
          JOIN consultationclient cc on co.IdConsultation = cc.IdConsultation
          JOIN client cl on cc.IdClient = cl.IdClient
          WHERE Email = ? OR Nom = ? OR Prenom = ?");

          $sql->execute(array($_POST['search'],$_POST['search'],$_POST['search']));
          $data = $sql->fetchAll();
          if(count($data)>0){ ?>
            <table id="mainBox">
              <tr>
                <th class='planningCaseHead'>Date</th>
                <th class='planningCaseHead'>Heure</th>
                <th class='planningCaseHead'>Prix</th>
                <th class='planningCaseHead'>Reglement</th>
                <th class='planningCaseHead'>Anxiete</th>
                <th class='planningCaseHead'>Remarques</th>
              </tr>
          <?php
          }
          for($i=0;$i<count($data);$i++){ ?>
          <tr>
            <td class='planningCase'><?php echo $data[$i][0]; ?></td>
            <td class='planningCase'><?php echo $data[$i][1]; ?></td>
            <td class='planningCase'><?php echo $data[$i][2]; ?></td>
            <td class='planningCase'><?php echo $data[$i][3]; ?></td>
            <td class='planningCase'><?php echo $data[$i][4]; ?></th>
            <td class='planningCase'><?php echo $data[$i][5]; ?></th>
          </tr>
      <?php 
        }
      $sql->closeCursor();
      }
      ?>

    </table> 
  </div>
	</main>
</body>
</html>