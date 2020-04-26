<?php

require_once "protect.php";

check_user($_SESSION["role"], array("admin"));

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nome = $cognome = $email = $password = $confirm_password = $role = "";
$nome_err = $cognome_err = $email_err = $password_err = $confirm_password_err = $role_err = "";

error_log(json_encode($_POST));

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

    $input_role = trim($_POST["role"]);
    if(empty($input_role)){
        $role_err = "Per favore specifica 'ruolo'";
    } else{
        $role = $input_role;
    }
    
    // Check input errors before inserting in database
    if(empty($nome_err) && empty($cognome_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO utenti (nome, cognome, email, password, role) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss",
            $nome,
            $cognome,
            $email,
            password_hash($password, PASSWORD_BCRYPT),
            $role);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: ../home/index.php");
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
<html dir="ltr">

<head>
    <?php include "head.php"; ?>
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(../../assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(../../assets/images/big/4.jpg);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="../../assets/images/big/icon.png" alt="wrapkit">
                        </div>
                        <h2 class="mt-3 text-center">Registrazione</h2>
                        <p class="text-center">Inserisci i dati per creare un nuovo utente.</p>
                        <form class="mt-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="email">Nome</label>
                                        <input class="form-control" type="text"
                                            placeholder="Inserisci il nome" name="nome" value="<?php echo $nome; ?>">
                                        <span class="help-block"><?php echo $nome_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="cognome">Cognome</label>
                                        <input class="form-control" type="text"
                                            placeholder="Inserisci il cognome" name="cognome" value="<?php echo $cognome; ?>">
                                        <span class="help-block"><?php echo $cognome_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="email">Email</label>
                                        <input class="form-control" type="text"
                                            placeholder="Inserisci l'indirizzo email" name="email" value="<?php echo $email; ?>">
                                        <span class="help-block"><?php echo $email_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="role">Ruolo</label>
                                        <select class="form-control" name="role">
                                            <option>admin</option>
                                            <option>editor</option>
                                            <option>base</option>
                                        </select>
                                        <span class="help-block"><?php echo $role_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Password</label>
                                        <input class="form-control" type="password" name="password"
                                            placeholder="Inserisci la tua password">
                                            <span class="help-block"><?php echo $password_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Conferma password</label>
                                        <input class="form-control" type="password" name="confirm_password"
                                            placeholder="Conferma la nuova password">
                                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-dark">Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js "></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $(".preloader ").fadeOut();
    </script>
</body>

</html>