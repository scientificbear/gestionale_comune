<?php

require_once "../protect.php";
require_once "../utils.php";

// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$tipologia = "";
$tipologia_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate
    list($tipologia, $tipologia_err) = check_variable($_POST["tipologia"], "tipologia");
    
    // Check input errors before inserting in database
    $error_check = empty($tipologia_err);

    if($error_check){
        $table = "tipo_immobili";
        $field = array("tipologia");
        $data = array($tipologia);
        $result = insert_data($table,$field,$data,$mysqli);

        if($result){
            // Records created successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else{
            echo "Something went wrong. Please try again later (", $mysqli->error, ")";
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
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Crea tipologia di immobile</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>Tipologia</label>
                            <input type="text" name="tipologia" class="form-control" value="<?php echo $tipologia; ?>">
                            <span class="help-block"><?php echo $tipologia_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Invia">
                        <a href="index.php" class="btn btn-default">Annulla</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
