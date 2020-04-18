<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nome = $cognome = $email = $password = $confirm_password = "";
$nome_err = $cognome_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate
    $input_nome = trim($_POST["nome"]);
    if(empty($input_nome)){
        $nome_err = "Per favore inserisci 'nome'";
    } else{
        $nome = $input_nome;
    }

    $input_cognome = trim($_POST["cognome"]);
    if(empty($input_cognome)){
        $cognome_err = "Per favore inserisci 'cognome'";
    } else{
        $cognome = $input_cognome;
    }

    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Per favore inserisci 'email'";
    } else{
        $email = $input_email;
    }
    
    // Validate password
    if(empty($mysqli->real_escape_string(trim($_POST["password"])))){
        $password_err = "Per favore inserisci 'password'.";     
    } elseif(strlen($mysqli->real_escape_string(trim($_POST["password"]))) < 6){
        $password_err = "Password deve avere almeno 6 caratteri.";
    } else{
        $password = $mysqli->real_escape_string(trim($_POST["password"]));
    }
    
    // Validate confirm password
    if(empty($mysqli->real_escape_string(trim($_POST["confirm_password"])))){
        $confirm_password_err = "Per favore conferma la password.";     
    } else{
        $confirm_password = $mysqli->real_escape_string(trim($_POST["confirm_password"]));
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "La password non corrisponde.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($nome_err) && empty($cognome_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO utenti (nome, cognome, email, password) VALUES (?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss",
            $nome,
            $cognome,
            $email,
            password_hash($password, PASSWORD_BCRYPT));
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later (", $stmt->error, ")";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrazione</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registrazione</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                <label>nome</label>
                <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                <span class="help-block"><?php echo $nome_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($cognome_err)) ? 'has-error' : ''; ?>">
                <label>cognome</label>
                <input type="text" name="cognome" class="form-control" value="<?php echo $cognome; ?>">
                <span class="help-block"><?php echo $cognome_err; ?></span>
            </div>    

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Conferma Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Hai già un account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>
