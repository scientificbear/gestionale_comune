<?php

require_once "../general/protect.php";
require_once "../general/utils.php";
check_user($_SESSION["role"], array("admin"));

// Include config file
require_once "../general/config.php";
 
// Define variables and initialize with empty values
$circ = array("1"=>False, "2"=>False, "3"=>False, "4"=>False, "5"=>False, "6"=>False, "7"=>False, "8"=>False);

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    error_log("POST: ".json_encode($_POST));
    
    // Delete previous data
    $sql_circ = "DELETE FROM utenti_circoscrizioni WHERE id_utente = ".$id;
    error_log($sql_circ);
    $result = $mysqli->query($sql_circ);
    if(! $result){
        echo "Something went wrong 1. Please try again later (", $mysqli->error, ")";
        die();
    }

    // Add new data
    $insert_values = "";
    foreach ($circ as $c_name => $c_value){
        if (array_key_exists("circ".$c_name, $_POST)){
            if ($_POST["circ".$c_name] == True){
                $insert_values = $insert_values . "(".$id.",".$c_name."), ";
            }
        }
    }
    $insert_values = rtrim($insert_values, ", ");

    if (strlen($insert_values) > 0){
        $sql_circ = "INSERT INTO utenti_circoscrizioni (id_utente, circoscrizione) VALUES ".$insert_values;
        $result = $mysqli->query($sql_circ);
        if($result){
            header("location: index.php");
            exit();
            // Close connection
            $mysqli->close();
        } else {
            echo "Something went wrong 2. Please try again later (", $mysqli->error, ")";
        }
    }    

} else{
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

                    $sql_circ = "SELECT * FROM utenti_circoscrizioni WHERE id_utente = ".$id;
                    if($result_circ = $mysqli->query($sql_circ)){
                        if($result->num_rows > 0){
                            while($row_circ = $result_circ->fetch_array()){
                                $circ[$row_circ["circoscrizione"]] = True;
                            }
                        }
                    }

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
        
    }  else{
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Abilita circoscrizioni</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="./index.php" class="text-muted">Utenti</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Abilita</li>
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
                                        <input type="text" class="form-control" name="id" readonly value="<?php echo $row["id"]; ?>">
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <h5 class="card-title">Email</h5>
                                        <input type="text" class="form-control" name="nome" readonly value="<?php echo $row["email"]; ?>">
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <h5 class="card-title">Circoscrizioni abilitate</h5>
                                        <div class="row">
                                            <?php
                                                foreach ($circ as $c_name => $c_value){
                                                    echo '<div class="col-md-1">';
                                                    echo '<div class="custom-control custom-checkbox">';
                                                    if ($c_value){
                                                        echo '<input type="checkbox" class="custom-control-input" id="circ'.$c_name.'" name="circ'.$c_name.'" value=True checked>';
                                                    } else {
                                                        echo '<input type="checkbox" class="custom-control-input" id="circ'.$c_name.'" name="circ'.$c_name.'" value=True >';
                                                    }
                                                    echo '<label class="custom-control-label" for="circ'.$c_name.'">'.$c_name.'^</label>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                            ?>
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
                </div>                <!-- *************************************************************** -->
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