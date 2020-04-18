<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$id = $nome = $indirizzo = $cap = $comune = $provincia = $email = $telefono_ref = $nome_ref = $categoria = "";
$id_err = $nome_err = $indirizzo_err = $cap_err = $comune_err = $provincia_err = $email_err = $telefono_ref_err = $nome_ref_err = $categoria_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_nome = trim($_POST["nome"]);
    error_log($input_nome);
    if(empty($input_nome)){
        $nome_err = "Per favore inserisci 'nome'";
    } else{
        $nome = $input_nome;
    }

    $input_indirizzo = trim($_POST["indirizzo"]);
    if(empty($input_indirizzo)){
        $indirizzo_err = "Per favore inserisci 'indirizzo'";
    } else{
        $indirizzo = $input_indirizzo;
    }

    $input_cap = trim($_POST["cap"]);
    if(empty($input_cap)){
        $cap_err = "Per favore inserisci 'cap'";
    } else{
        $cap = $input_cap;
    }

    $input_comune = trim($_POST["comune"]);
    if(empty($input_comune)){
        $comune_err = "Per favore inserisci 'comune'";
    } else{
        $comune = $input_comune;
    }

    $input_provincia = trim($_POST["provincia"]);
    if(empty($input_provincia)){
        $provincia_err = "Per favore inserisci 'provincia'";
    } else{
        $provincia = $input_provincia;
    }

    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Per favore inserisci 'email'";
    } else{
        $email = $input_email;
    }

    $telefono_ref = trim($_POST["telefono_ref"]);

    $nome_ref = trim($_POST["nome_ref"]);

    $input_categoria = trim($_POST["categoria"]);
    if(empty($input_categoria)){
        $categoria_err = "Per favore inserisci 'categoria'";
    } else{
        $categoria = $input_categoria;
    }
    
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
        empty($categoria_err);

    if($error_check){
        // Prepare an update statement
        $sql = "UPDATE ditte SET
        nome=?,
        indirizzo=?,
        cap=?,
        comune=?,
        provincia=?,
        email=?,
        telefono_ref=?,
        nome_ref=?,
        categoria=?,
        last_modified_at=now()
        WHERE id=?";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssssssd",
                $nome,
                $indirizzo,
                $cap,
                $comune,
                $provincia,
                $email,
                $telefono_ref,
                $nome_ref,
                $categoria,
                $id);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later (", $stmt->error, ")";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM ditte WHERE id = ?";
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
                    $nome = $row["nome"];
                    $indirizzo = $row["indirizzo"];
                    $cap = $row["cap"];
                    $comune = $row["comune"];
                    $provincia = $row["provincia"];
                    $email = $row["email"];
                    $nome_ref = $row["nome_ref"];
                    $telefono_ref = $row["telefono_ref"];
                    $categoria = $row["categoria"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
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
    <title>Aggiorna ditta</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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

                        <div class="form-group <?php echo (!empty($categoria_err)) ? 'has-error' : ''; ?>">
                            <label>categoria</label>
                            <input type="text" name="categoria" class="form-control" value="<?php echo $categoria; ?>">
                            <span class="help-block"><?php echo $categoria_err;?></span>
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