<?php

require "../protect.php";
require "../utils.php";

// Include config file
require "../config.php";
 
// Define variables and initialize with empty values
$nome = $descrizione = $indirizzo = $cap = $circoscrizione = $codice = $id_tipologia = $telefono_ref = $nome_ref = "";
$nome_err = $descrizione_err = $indirizzo_err = $cap_err = $circoscrizione_err = $codice_err = $id_tipologia_err = $telefono_ref_err = $nome_ref_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate
    list($nome, $nome_err) = check_variable($_POST["nome"], "nome");
    list($indirizzo, $indirizzo_err) = check_variable($_POST["indirizzo"], "indirizzo");
    list($cap, $cap_err) = check_variable($_POST["cap"], "cap");
    list($circoscrizione, $circoscrizione_err) = check_variable($_POST["circoscrizione"], "circoscrizione");
    list($codice, $codice_err) = check_variable($_POST["codice"], "codice");
    list($id_tipologia, $id_tipologia_err) = check_variable($_POST["id_tipologia"], "tipologia");
    
    $descrizione = trim($_POST["descrizione"]);
    $telefono_ref = trim($_POST["telefono_ref"]);
    $nome_ref = trim($_POST["nome_ref"]);

    // Check input errors before inserting in database
    $error_check = empty($nome_err) &&
    empty($descrizione_err) &&
    empty($indirizzo_err) &&
    empty($cap_err) &&
    empty($circoscrizione_err) &&
    empty($codice_err) &&
    empty($id_tipologia_err) &&
    empty($telefono_ref_err) &&
    empty($nome_ref_err);

    if($error_check){
        $table = "immobili";
        $field = array("nome","descrizione","indirizzo","cap","circoscrizione","codice","id_tipologia","telefono_ref","nome_ref");
        $data = array($nome,$descrizione,$indirizzo,$cap,$circoscrizione,$codice,$id_tipologia,$telefono_ref,$nome_ref);
        $result = insert_data($table,$field,$data,$mysqli);

        if($result){
            // Records created successfully. Redirect to landing page
            header("location: index.php");
            exit();

           // Close connection
            $mysqli->close();

        } else{
            echo "Something went wrong. Please try again later (", $mysqli->error, ")";
        }
    }
    
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
        
        // Initialize select2
        $("#sel_tipo_immobili").select2();

        // Read selected option
        $('#but_read').click(function(){
        var tipologia = $('#sel_tipo_immobili option:selected').text();
        var id_tipologia = $('#sel_tipo_immobili').val();

        $('#result').html("id : " + id_tipologia + ", tipologia : " + tipologia);

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
                        <h2>Crea immobile</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                            <span class="help-block"><?php echo $nome_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($descrizione_err)) ? 'has-error' : ''; ?>">
                            <label>descrizione</label>
                            <input type="text" name="descrizione" class="form-control" value="<?php echo $descrizione; ?>">
                            <span class="help-block"><?php echo $descrizione_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($indirizzo_err)) ? 'has-error' : ''; ?>">
                            <label>indirizzo</label>
                            <input type="text" name="indirizzo" class="form-control" value="<?php echo $indirizzo; ?>">
                            <span class="help-block"><?php echo $indirizzo_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($cap_err)) ? 'has-error' : ''; ?>">
                            <label>cap</label>
                            <input type="text" name="cap" class="form-control" value="<?php echo $cap; ?>">
                            <span class="help-block"><?php echo $cap_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($circoscrizione_err)) ? 'has-error' : ''; ?>">
                            <label>circoscrizione</label>
                            <input type="text" name="circoscrizione" class="form-control" value="<?php echo $circoscrizione; ?>">
                            <span class="help-block"><?php echo $circoscrizione_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($codice_err)) ? 'has-error' : ''; ?>">
                            <label>codice</label>
                            <input type="text" name="codice" class="form-control" value="<?php echo $codice; ?>">
                            <span class="help-block"><?php echo $codice_err;?></span>
                        </div>
                        <div class="search-box form-group <?php echo (!empty($id_tipologia_err)) ? 'has-error' : ''; ?>">
                            <label>id_tipologia</label>
                            <?php
                            $sql = "SELECT id, tipologia FROM tipo_immobili ORDER BY tipologia";
                            if($result = $mysqli->query($sql)){
                                echo "<select id='sel_tipo_immobili' style='width: 200px;' name='id_tipologia'>";
                                if($result->num_rows > 0){
                                    echo "<option selected='true' disabled='disabled'>Tipologia</option>";
                                    while($row = $result->fetch_array()){
                                        if ($row['id']==$id_tipologia){
                                            echo "<option value=" . $row['id'] . " selected>" . $row['tipologia'] . "</option>";
                                        } else {
                                            echo "<option value=" . $row['id'] . ">" . $row['tipologia'] . "</option>";
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
                            <div id='result'></div>
                            <span class="help-block"><?php echo $id_tipologia_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($telefono_ref_err)) ? 'has-error' : ''; ?>">
                            <label>telefono_ref</label>
                            <input type="text" name="telefono_ref" class="form-control" value="<?php echo $telefono_ref; ?>">
                            <span class="help-block"><?php echo $telefono_ref_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($nome_ref_err)) ? 'has-error' : ''; ?>">
                            <label>nome_ref</label>
                            <input type="text" name="nome_ref" class="form-control" value="<?php echo $nome_ref; ?>">
                            <span class="help-block"><?php echo $nome_ref_err;?></span>
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
