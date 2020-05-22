<?php

require_once "../general/protect.php";
require_once "../general/utils.php";
$circ = array("1"=>False, "2"=>False, "3"=>False, "4"=>False, "5"=>False, "6"=>False, "7"=>False, "8"=>False);

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "../general/config.php";
    
    // Prepare a select statement
    $sql = "SELECT d.*, cd.categoria, cd.id as id_categoria FROM ditte d LEFT JOIN categoria_ditte cd ON d.id_categoria=cd.id WHERE d.id = ?";
    
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
                
                $sql_circ = "SELECT * FROM ditte_circoscrizioni WHERE id_ditta = ".$row["id"];
                if($result_circ = $mysqli->query($sql_circ)){
                    if($result_circ->num_rows > 0){
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Visualizza ditta</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">Ditte</a></li>
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
                                        <h5 class="card-title">Denominazione</h5>
                                        <p class="card-text"><?php echo $row["nome"]; ?></p>
                                        <br/>
                                        <h5 class="card-title">Indirizzo</h5>
                                        <p class="card-text"><?php echo $row["indirizzo"] . "&emsp; <a href='https://www.google.com/maps/place/". $row["indirizzo"] . "+" . $row["comune"] . "+" . $row["provincia"] . "+Italia' target='_blank'><i class='far fa-map'></i></a>"; ?></p>
                                        <br/>
                                        <h5 class="card-title">Comune</h5>
                                        <p class="card-text"><?php echo $row["comune"]; ?></p>
                                        <br/>
                                        <h5 class="card-title">Provincia</h5>
                                        <p class="card-text"><?php echo $row["provincia"]; ?></p>
                                        <br/>
                                        <h5 class="card-title">CAP</h5>
                                        <p class="card-text"><?php echo $row["cap"]; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="card-title">Email</h5>
                                        <p class="card-text"><?php echo $row["email"]; ?></p>
                                        <br/>
                                        <h5 class="card-title">Categoria</h5>
                                        <p class="card-text"><?php echo $row["categoria"] . "&emsp; <a href='../categoria_ditte/read.php?id=" . $row["id_categoria"] . "'><i class='fas fa-external-link-alt'></i></a>"; ?></p>
                                        <br/>
                                        <h5 class="card-title">Referente</h5>
                                        <p class="card-text"><?php echo $row["nome_ref"]; ?> (<?php echo $row["telefono_ref"]; ?>)</p>
                                        <br/>
                                        <h5 class="card-title">Data creazione (ultima modifica) record</h5>
                                        <p class="card-text"><?php echo $row["created_at"]; ?> (<?php echo $row["last_modified_at"]; ?>)</p>
                                        <br/>
                                        <div class="form-group">
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
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Interventi associati</h3>
                                <br />
                                <div class="table-responsive">
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#tabella_interventi').DataTable({order:[[1,"desc"]]});
                                    } );
                                </script>
                                <?php                                
                                // Attempt select query execution
                                $sql = "SELECT ii.*, i.nome AS nome_immobile, i.indirizzo
                                FROM interventi_immobili ii
                                LEFT JOIN immobili i ON ii.id_immobile=i.id
                                WHERE ii.id_ditta = " . $row['id'];
                                error_log($sql);
                                if($result = $mysqli->query($sql)){
                                    if($result->num_rows > 0){
                                    echo '<table id="tabella_interventi" class="table table-striped table-bordered no-wrap">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Data</th>";
                                    echo "<th>Descrizione</th>";
                                    echo "<th>Immobile</th>";
                                    echo "<th></th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($det_row = $result->fetch_array()){
                                        echo "<tr>";
                                        echo "<td>" . $det_row['id'] . "</td>";
                                        echo "<td>" . $det_row['data'] . "</td>";
                                        echo "<td>" . trunc_str($det_row['descrizione'], 100) . "</td>";
                                        echo "<td>" . $det_row['nome_immobile'] . "</td>";
                                        echo "<td><a href='../interventi_immobili/read.php?id=". $det_row['id'] ."' title='Vedi Record'><i class='fas fa-eye'></i></a></td>";
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