<?php

require_once "../protect.php";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "../config.php";
    
    // Prepare a select statement
    $sql = "SELECT ii.*, i.nome AS nome_immobile, i.indirizzo, d.nome AS nome_ditta, d.email
    FROM interventi_immobili ii
    LEFT JOIN immobili i ON ii.id_immobile=i.id
    LEFT JOIN ditte d ON ii.id_ditta=d.id
    WHERE ii.id = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $id = $row["id"];
                $data = $row["data"];
                $descrizione = $row["descrizione"];
                $nome_immobile = $row["nome_immobile"];
                $indirizzo = $row["indirizzo"];
                $nome_ditta = $row["nome_ditta"];
                $email = $row["email"];
                $created_at = $row["created_at"];
                $last_modified_at = $row["last_modified_at"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Something went wrong. Please try again later (", $stmt->error, ")";
        }
    }
     
    // Close statement
    $stmt->close();
    
    // Close connection
    $mysqli->close();
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vedi ditta</title>
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
                        <h1>View intervento</h1>
                    </div>
                    <div class="form-group">
                        <label>ID</label>
                        <p class="form-control-static"><?php echo $row["id"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>data</label>
                        <p class="form-control-static"><?php echo $row["data"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>descrizione</label>
                        <p class="form-control-static"><?php echo $row["descrizione"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>nome immobile</label>
                        <p class="form-control-static"><?php echo $row["nome_immobile"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>indirizzo</label>
                        <p class="form-control-static"><?php echo $row["indirizzo"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>nome ditta</label>
                        <p class="form-control-static"><?php echo $row["nome_ditta"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>email</label>
                        <p class="form-control-static"><?php echo $row["email"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>created_at</label>
                        <p class="form-control-static"><?php echo $row["created_at"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>last_modified_at</label>
                        <p class="form-control-static"><?php echo $row["last_modified_at"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Indietro</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
