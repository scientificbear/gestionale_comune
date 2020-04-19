<?php

require_once "../protect.php";
require_once "../utils.php";

// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$nome = $indirizzo = $cap = $comune = $provincia = $email = $telefono_ref = $nome_ref = $id_categoria = "";
$id_err = $nome_err = $indirizzo_err = $cap_err = $comune_err = $provincia_err = $email_err = $telefono_ref_err = $nome_ref_err = $id_categoria_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate
    list($nome, $nome_err) = check_variable($_POST["nome"], "nome");
    list($indirizzo, $indirizzo_err) = check_variable($_POST["indirizzo"], "indirizzo");
    list($comune, $comune_err) = check_variable($_POST["comune"], "comune");
    list($cap, $cap_err) = check_variable($_POST["cap"], "cap");
    list($provincia, $provincia_err) = check_variable($_POST["provincia"], "provincia");
    list($email, $email_err) = check_variable($_POST["email"], "email");
    list($id_categoria, $id_categoria_err) = check_variable($_POST["id_categoria"], "id_categoria");

    $telefono_ref = trim($_POST["telefono_ref"]);
    $nome_ref = trim($_POST["nome_ref"]);
    
    // Check input errors before inserting in database
    $error_check = empty($id_err) &&
        empty($nome_err) &&
        empty($indirizzo_err) &&
        empty($cap_err) &&
        empty($comune_err) &&
        empty($provincia_err) &&
        empty($email_err) &&
        empty($telefono_ref_err) &&
        empty($nome_ref_err) &&
        empty($id_categoria_err);

    if($error_check){
        $table = "ditte";
        $field = array("nome", "indirizzo", "cap", "comune", "provincia", "email", "telefono_ref", "nome_ref", "id_categoria");
        $data = array($nome, $indirizzo, $cap, $comune, $provincia, $email, $telefono_ref, $nome_ref, $id_categoria);
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

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
        
        // Initialize select2
        $("#sel_categoria_ditte").select2();

        // Read selected option
        $('#but_read').click(function(){
        var categoria = $('#sel_categoria_ditte option:selected').text();
        var id_categoria = $('#sel_categoria_ditte').val();

        $('#result').html("id : " + id_categoria + ", categoria : " + categoria);

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
                        <h2>Crea ditta</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                            <span class="help-block"><?php echo $nome_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($indirizzo_err)) ? 'has-error' : ''; ?>">
                            <label>Indirizzo</label>
                            <input type="text" name="indirizzo" class="form-control" value="<?php echo $indirizzo; ?>">
                            <span class="help-block"><?php echo $indirizzo_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($cap_err)) ? 'has-error' : ''; ?>">
                            <label>CAP</label>
                            <input type="text" name="cap" class="form-control" value="<?php echo $cap; ?>">
                            <span class="help-block"><?php echo $cap_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($comune_err)) ? 'has-error' : ''; ?>">
                            <label>comune</label>
                            <input type="text" name="comune" class="form-control" value="<?php echo $comune; ?>">
                            <span class="help-block"><?php echo $comune_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($provincia_err)) ? 'has-error' : ''; ?>">
                            <label>provincia</label>
                            <input type="text" name="provincia" class="form-control" value="<?php echo $provincia; ?>">
                            <span class="help-block"><?php echo $provincia_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>email</label>
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err;?></span>
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

                        <div class="search-box form-group <?php echo (!empty($id_categoria_err)) ? 'has-error' : ''; ?>">
                            <label>id_categoria</label>
                            <?php
                            $sql = "SELECT id, categoria FROM categoria_ditte ORDER BY categoria";
                            if($result = $mysqli->query($sql)){
                                echo "<select id='sel_categoria_ditte' style='width: 200px;' name='id_categoria'>";
                                if($result->num_rows > 0){
                                    echo "<option selected='true' disabled='disabled'>categoria</option>";
                                    while($row = $result->fetch_array()){
                                        if ($row['id']==$id_categoria){
                                            echo "<option value=" . $row['id'] . " selected>" . $row['categoria'] . "</option>";
                                        } else {
                                            echo "<option value=" . $row['id'] . ">" . $row['categoria'] . "</option>";
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
                            <span class="help-block"><?php echo $id_categoria_err;?></span>
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
