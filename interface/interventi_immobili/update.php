<?php

require_once "../protect.php";
require_once "../utils.php";
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$data = date("Y-m-d");
$id = $descrizione = $id_immobile = $id_ditta = "";
$id_err = $data_err = $descrizione_err = $id_immobile_err = $id_ditta_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    list($descrizione, $descrizione_err) = check_variable($_POST["descrizione"], "descrizione");
    list($data, $data_err) = check_variable($_POST["data"], "data");

    $id_utente = $_SESSION["id"];
    $id_immobile = $_POST["id_immobile"];
    $id_ditta = $_POST["id_ditta"];

    // Check input errors before inserting in database
    $error_check = empty($id_err) &&
    empty($descrizione_err) &&
    empty($data_err);

    if($error_check){
        // Prepare an update statement
        $table = "interventi_immobili";
        $field = array("id","descrizione","data","id_immobile","id_utente","id_ditta");
        $data = array($id,$descrizione,$data,$id_immobile,$id_utente,$id_ditta);
        
        $result = update_data($table,$field,$data,$id,$mysqli);
        var_dump($result);
        // Attempt to execute the prepared statement
        if($result){
            // Records updated successfully. Redirect to landing page
            header("location: index.php");
            exit();
            // Close connection
            $mysqli->close();            
        } else{
            echo "Something went wrong. Please try again later (", $mysqli->error, ")";
        }
    }

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM interventi_immobili WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
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
                    $id_immobile = $row["id_immobile"];
                    $id_ditta = $row["id_ditta"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Something went wrong. Please try again later (", $mysqli->error, ")";
            }
        }
        
        // Close statement
        $stmt->close();
        
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aggiorna intervento</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>

<script type="text/javascript">
    $(document).ready(function(){
        
        // Initialize select2
        $("#sel_immobili").select2();

        // Read selected option
        $('#but_read').click(function(){
        var nome = $('#sel_immobili option:selected').text();
        var id_immobile = $('#sel_immobili').val();

        $('#result_immobili').html("id : " + id_immobile + ", nome : " + nome);

        });
    });
    </script>

    <script type="text/javascript">
    $(document).ready(function(){
        
        // Initialize select2
        $("#sel_ditte").select2();

        // Read selected option
        $('#but_read').click(function(){
        var nome = $('#sel_ditte option:selected').text();
        var id_ditta = $('#sel_ditte').val();

        $('#result_ditte').html("id : " + id_ditta + ", nome : " + nome);

        });
    });
    </script>

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($data_err)) ? 'has-error' : ''; ?>">
                            <label>data</label>
                            <input type="text" name="data" class="form-control" value="<?php echo $data; ?>">
                            <span class="help-block"><?php echo $data_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($descrizione_err)) ? 'has-error' : ''; ?>">
                            <label>descrizione</label>
                            <input type="text" name="descrizione" class="form-control" value="<?php echo $descrizione; ?>">
                            <span class="help-block"><?php echo $descrizione_err;?></span>
                        </div>
                        <div class="search-box form-group <?php echo (!empty($id_immobile_err)) ? 'has-error' : ''; ?>">
                            <label>immobile</label>
                            <?php
                            $sql = "SELECT id, nome, indirizzo FROM immobili ORDER BY nome";
                            if($result = $mysqli->query($sql)){
                                echo "<select id='sel_immobili' style='width: 200px;' name='id_immobile'>";
                                if($result->num_rows > 0){
                                    echo "<option selected='true' disabled='disabled'>Immobile</option>";
                                    while($row = $result->fetch_array()){
                                        if ($row['id']==$id_immobile){
                                            echo "<option value=".$row['id']." selected>".$row['nome']." (".$row['indirizzo'].")</option>";
                                        } else {
                                            echo "<option value=".$row['id'].">".$row['nome']." (".$row['indirizzo'].")</option>";
                                        }
                                    }
                                    // Free result set
                                    $result->free();
                                }
                                echo "</select>";
                            } else{
                                echo "ERROR: Non riesco ad eseguire $sql. " . $mysqli->error;
                            }
                            ?>
                            <br />
                            <div id='result_immobili'></div>
                            <span class="help-block"><?php echo $id_immobili_err;?></span>
                        </div>

                        <div class="search-box form-group <?php echo (!empty($id_ditta_err)) ? 'has-error' : ''; ?>">
                            <label>ditta</label>
                            <?php
                            $sql = "SELECT id, nome, categoria FROM ditte ORDER BY nome";
                            if($result = $mysqli->query($sql)){
                                echo "<select id='sel_ditte' style='width: 200px;' name='id_ditta'>";
                                if($result->num_rows > 0){
                                    echo "<option selected='true' disabled='disabled'>Ditta</option>";
                                    while($row = $result->fetch_array()){
                                        if ($row['id']==$id_ditta){
                                            echo "<option value=".$row['id']." selected>".$row['nome']." (".$row['categoria'].")</option>";
                                        } else {
                                            echo "<option value=".$row['id'].">".$row['nome']." (".$row['categoria'].")</option>";
                                        }
                                    }
                                    // Free result set
                                    $result->free();
                                }
                                echo "</select>";
                            } else{
                                echo "ERROR: Non riesco ad eseguire $sql. " . $mysqli->error;
                            }
                            ?>
                            <br />
                            <div id='result_ditte'></div>
                            <span class="help-block"><?php echo $id_immobili_err;?></span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Invia">
                        <a href="index.php" class="btn btn-default">Annulla</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>