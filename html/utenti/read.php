<?php

require_once "../general/protect.php";
require_once "../general/utils.php";
check_user($_SESSION["role"], array("admin"));
$circ = array("1"=>False, "2"=>False, "3"=>False, "4"=>False, "5"=>False, "6"=>False, "7"=>False, "8"=>False);


// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "../general/config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM utenti WHERE id = ?";
    
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
                
                $sql_circ = "SELECT * FROM utenti_circoscrizioni WHERE id_utente = ".$row["id"];
                if($result_circ = $mysqli->query($sql_circ)){
                    if($result->num_rows > 0){
                        while($row_circ = $result_circ->fetch_array()){
                            $circ[$row_circ["circoscrizione"]] = True;
                        }
                    }
                }

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: ../general/error.php");
                exit();
            }
            
        } else{
            echo "Something went wrong. Please try again later (", $stmt->error, ")";
        }
    }
     
    // Close statement
    $stmt->close();
    
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: ../general/error.php");
    exit();
}
?>
 
<!DOCTYPE html>
<html dir="ltr" lang="it">

<head>
    <?php include "../general/head.php"; ?>
    <!-- This page plugin CSS -->
    <link href="../../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <script type="text/javascript">
    // $(document).ready( function () {
    //     // $('#tabella_immobili').DataTable();
    // } );
    </script>
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Vedi utente</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">Utenti</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Vedi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="customize-input float-right">
                            <?php protect_content('<a href="update.php?id='.$row["id"].'" class="btn btn-secondary btn-circle-lg"><i class="fa fas fa-edit"></i></a>',
                            $_SESSION["role"], array("admin", "editor")) ?>
                            <?php protect_content('<a href="delete.php?id='.$row["id"].'" class="btn btn-danger btn-circle-lg"><i class="fa fas fa-trash-alt"></i></a>',
                            $_SESSION["role"], array("admin", "editor")) ?>
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
                            <div class="card-body">
                                <h3>Record</h3>
                                <br />
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="card-title">ID</h5>
                                        <p class="card-text"><?php echo $row["id"]; ?></p>
                                        <br/>
                                        <h5 class="card-title">Nome</h5>
                                        <p class="card-text"><?php echo $row["nome"]; ?></p>
                                        <br/>
                                        <h5 class="card-title">Cognome</h5>
                                        <p class="card-text"><?php echo $row["cognome"]; ?></p>
                                        <br/>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="card-title">Email</h5>
                                        <p class="card-text"><?php echo $row["email"]; ?></p>
                                        <br/>
                                        <h5 class="card-title">Ruolo</h5>
                                        <p class="card-text"><?php echo $row["role"]; ?></p>
                                        <h5 class="card-title">Circoscrizioni abilitate</h5>
                                        <div class="row">
                                            <?php
                                                foreach ($circ as $c_name => $c_value){
                                                    echo '<div class="col-md-1">';
                                                    echo '<div class="custom-control custom-checkbox">';
                                                    if ($c_value){
                                                        echo '<input type="checkbox" class="custom-control-input" id="circ'.$c_name.'" name="circ'.$c_name.'" value=True disabled checked>';
                                                    } else {
                                                        echo '<input type="checkbox" class="custom-control-input" id="circ'.$c_name.'" name="circ'.$c_name.'" value=True disabled >';
                                                    }
                                                    echo '<label class="custom-control-label" for="circ'.$c_name.'">'.$c_name.'^</label>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Interventi inseriti</h3>
                                <br />
                                <div class="table-responsive">
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#tabella_interventi').DataTable({order:[[1,"desc"]]});
                                    } );
                                </script>
                                <?php                                
                                // Attempt select query execution
                                $sql = "SELECT ii.id, ii.data, ii.descrizione, i.nome AS nome_immobile, d.nome AS nome_ditta
                                FROM interventi_immobili ii
                                LEFT JOIN immobili i ON ii.id_immobile=i.id
                                LEFT JOIN ditte d ON ii.id_ditta=d.id
                                WHERE ii.id_utente = " . $row["id"];
                                if($result = $mysqli->query($sql)){
                                    if($result->num_rows > 0){
                                        echo '<table id="tabella_interventi" class="table table-striped table-bordered no-wrap">';
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Data</th>";
                                        echo "<th>Immobile</th>";
                                        echo "<th>Ditta</th>";
                                        echo "<th>Descrizione</th>";
                                        echo "<th></th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        while($int_row = $result->fetch_array()){
                                            echo "<tr>";
                                            echo "<td>" . $int_row['id'] . "</td>";
                                            echo "<td>" . $int_row['data'] . "</td>";
                                            echo "<td>" . $int_row['nome_immobile'] . "</td>";
                                            echo "<td>" . $int_row['nome_ditta'] . "</td>";
                                            echo "<td>" . $int_row['descrizione'] . "</td>";
                                            echo "<td><a href='../interventi_immobili/read.php?id=". $int_row['id'] ."' title='Vedi Record'><i class='fas fa-eye'></i></a></td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";                            
                                        echo "</table>";
                                        // Free result set
                                        $result->free();
                                    } else{
                                        echo "<p><em>Nessun risultato trovato.</em></p>";
                                    }
                                } else{
                                    echo "ERROR: Non riesco ad eseguire $sql. " . $mysqli->error;
                                }
                                // Close connection
                                $mysqli->close();
                                ?>
                                </div>
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