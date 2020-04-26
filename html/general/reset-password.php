<?php
// Initialize the session
require_once "protect.php";

 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty($mysqli->real_escape_string(trim($_POST["new_password"])))){
        $new_password_err = "Inserisci una nuova password.";     
    } elseif(strlen($mysqli->real_escape_string(trim($_POST["new_password"]))) < 6){
        $new_password_err = "Password deve avere almeno 6 caratteri.";
    } else{
        $new_password = $mysqli->real_escape_string(trim($_POST["new_password"]));
    }
    
    // Validate confirm password
    if(empty($mysqli->real_escape_string(trim($_POST["confirm_password"])))){
        $confirm_password_err = "Conferma la password.";
    } else{
        $confirm_password = $mysqli->real_escape_string(trim($_POST["confirm_password"]));
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "La password non corrisponde.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE utenti SET password = ? WHERE id = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
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
                        <h2 class="mt-3 text-center">Cambia password</h2>
                        <form class="mt-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="row">
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Nuova password</label>
                                        <input class="form-control" id="pwd" type="password" name="new_password"
                                            placeholder="Inserisci la nuova password">
                                            <span class="help-block"><?php echo $new_password_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Conferma password</label>
                                        <input class="form-control" id="pwd" type="password" name="confirm_password"
                                            placeholder="Conferma la nuova password">
                                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-danger">Aggiorna</button>
                                    <a class="btn btn-block btn-dark" href="index.php">Annulla</a>
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