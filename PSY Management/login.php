<?php
// Initialize the session
session_start(); 

$mysqli = new mysqli('localhost', 'root', '', 'psy-base','3308');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";



 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        if($username=="admin" && $password=="admin")
        {
            // Password is correct, so start a new session
            session_start();
                            
            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;                            
            
            // Redirect user to welcome page
            header("location: planning_psy.php");
        }
        else {
        $sql = "SELECT idClient, Email, MotDePasse FROM client WHERE Email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows() == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if($password == $hashed_password){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: planning_client.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "le mot de passe n'est pas valide.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "aucun compte trouver à cet identifiant.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    }
    
    // Close connection
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>PSY Management - login</title>
</head>

<style type="text/css">

#login{ border: 3px solid #f1f1f1;   box-sizing: border-box;  width: 30%;   margin: auto; margin-top: 20%;}

/* Full-width inputs */
input[type=text], input[type=password] { width: 100%;   padding: 12px 20px;   margin: 8px 0;   display: inline-block;   border: 1px solid #ccc;   box-sizing: border-box; }

/* Set a style for all buttons */
.btn {background-color: #4CAF50;   color: white;   padding: 14px 20px;   margin: 8px 0;   border: none;   cursor: pointer;   width: 100%; }


.btn:hover { opacity: 0.8 }

/* Extra style for the cancel button (red) */
#cancelbtn { width: auto;   padding: 10px 18px;   background-color: #f44336; }

/* Center the avatar image inside this container */
.imgcontainer { text-align: center;   margin: 24px 0 12px 0;}

/* Avatar image */
img.avatar { border-radius: 15%; width: 95%; }

/* Add padding to containers */
.container { padding: 16px; }

/* The "Forgot password" text */
span.psw { float: right;   padding-top: 16px; }
</style>

<body>
<div id="login">
  <div class="imgcontainer">
    <img src="https://i.ytimg.com/vi/XOiAmZBCwN0/maxresdefault.jpg" alt="Avatar" class="avatar">
  </div>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>>
        <label style="color: #0099ff">Identifiant</label>
        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
        <span class="help-block"><?php echo $username_err; ?></span>
      </div>    
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label style="color: #0099ff">Mot de passe</label>
        <input type="password" name="password" class="form-control">
        <span class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn" value="Login">
  </form>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" id="cancelbtn" class="btn">Cancel</button>
    <span class="psw"><a href="creer_compte.php">pas encore de compte ?</a></span>
  </div>
</div>
</body>
</html>