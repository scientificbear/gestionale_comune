<?php

require_once "../general/protect.php";
require_once "../general/utils.php";
check_user($_SESSION["role"], array("admin"));

// Include config file
require_once "../general/config.php";
 
// Define variables and initialize with empty values
$nome = $cognome = $email = $role = "";
$nome_err = $cognome_err = $email_err = $password_err = $confirm_password_err = $role_err = "";

error_log("1");
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    error_log("2");
    // Get hidden input value
    $id = $_POST["id"];
    
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
    
    $input_role = trim($_POST["role"]);
    if(empty($input_role)){
        $role_err = "Per favore specifica 'ruolo'";
    } else{
        $role = $input_role;
    }

    // Check input errors before inserting in database
    $error_check = empty($tipologia_err);

    if(empty($nome_err) && empty($cognome_err) && empty($email_err) && empty($role_err)){
        // Prepare an update statement
        $table = "utenti";
        $field = array("nome", "cognome", "email", "role");
        $data = array($nome, $cognome, $email, $role);
        $result = update_data($table,$field,$data,$id,$mysqli);
        error_log("ciao!");
        // Attempt to execute the prepared statement
        if($result){
            // Records updated successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else{
            echo "Something went wrong. Please try again later (", $mysqli->error, ")";
        }
    }
    
    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM utenti WHERE id = ?";
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
                    $cognome = $row["cognome"];
                    $email = $row["email"];
                    $role = $row["role"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: ../general/error.php");
                    exit();
                }
                
            } else{
                echo "Something went wrong. Please try again later (", $mysqli->error, ")";
            }
        }
        
        // Close statement
        $stmt->close();
        
        // Close connection
        $mysqli->close();
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: ../general/error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html dir="ltr" lang="it">

<head>
    <?php include "../general/head.php"; ?>
    <!-- This page plugin CSS -->
    <link href="../../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <?php include "../general/preloader.php"; ?>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include "../general/page_header.php"; ?>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include "../general/sidebar.php"; ?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Aggiorna utente</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="./index.php" class="text-muted">Utenti</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Aggiorna</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- *************************************************************** -->
                <!-- Start Top Leader Table -->
                <!-- *************************************************************** -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body col-md-6 m-auto">
                                <form class="mt-4" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                                    <div class="form-group">
                                        <h5 class="card-title">ID</h5>
                                        <input type="text" class="form-control" name="id" readonly value="<?php echo $id; ?>">
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <h5 class="card-title">Nome</h5>
                                        <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                                        <div class="invalid-feedback">
                                            <?php echo $nome_err;?>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <h5 class="card-title">Cognome</h5>
                                        <input type="text" name="cognome" class="form-control" value="<?php echo $cognome; ?>">
                                        <div class="invalid-feedback">
                                            <?php echo $cognome_err;?>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <h5 class="card-title">Email</h5>
                                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                                        <div class="invalid-feedback">
                                            <?php echo $email_err;?>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <h5 class="card-title">Ruolo</h5>
                                        <select class="form-control" name="role" value="<?php echo $role; ?>">
                                        <?php
                                        foreach(array("admin", "editor", "base") as $c){
                                            if ($c == $role){
                                                echo '<option value="'.$c.'" selected>'.$c.'</option>';
                                            } else {
                                                echo '<option value="'.$c.'">'.$c.'</option>';
                                            }
                                        }
                                        ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?php echo $role_err;?>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="form-actions">
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-info">Salva</button>
                                            <a href="index.php" class="btn btn-dark">Annulla</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- *************************************************************** -->
                <!-- End Top Leader Table -->
                <!-- *************************************************************** -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php include "../general/page_footer.php"; ?>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <?php include "../general/footer.php"; ?>
    <!--This page plugins -->
    <script src="../../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../dist/js/pages/datatable/datatable-basic.init.js"></script>    
</body>

</html>