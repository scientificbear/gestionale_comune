<?php

require_once "../general/protect.php";
require_once "../general/utils.php";
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "../general/config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM tipo_immobili WHERE id = ?";
    
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
                $tipologia = $row["tipologia"];
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
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"> -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
    <!-- <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script> -->	
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Vedi tipologia</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">Tipologia immobili</a></li>
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
                                <div class="col-md-6 m-auto">
                                    <h5 class="card-title">ID</h5>
                                    <p class="card-text"><?php echo $row["id"]; ?></p>
                                    <br/>
                                    <h5 class="card-title">Tipologia</h5>
                                    <p class="card-text"><?php echo $row["tipologia"]; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Immobili associati</h3>
                                <br />
                                <div class="table-responsive">
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#tabella_immobili').DataTable({order:[[1,"asc"]]});
                                    } );
                                </script>
                                <?php                                
                                // Attempt select query execution
                                $sql = "SELECT * FROM immobili
                                WHERE circoscrizione IN ".$_SESSION["allowed_circ"]." AND id_tipologia = " . $row["id"];
                                if($result = $mysqli->query($sql)){
                                    if($result->num_rows > 0){
                                    echo '<table id="tabella_immobili" class="table table-striped table-bordered no-wrap">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Nome</th>";
                                    echo "<th>Descrizione</th>";
                                    echo "<th>Indirizzo</th>";
                                    echo "<th></th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($det_row = $result->fetch_array()){
                                        echo "<tr>";
                                        echo "<td>" . $det_row['id'] . "</td>";
                                        echo "<td>" . $det_row['nome'] . "</td>";
                                        echo "<td>" . trunc_str($det_row['descrizione']) . "</td>";
                                        echo "<td>" . $det_row['indirizzo'] . "</td>";
                                        echo "<td><a href='../immobili/read.php?id=". $det_row['id'] ."' title='Vedi Record'><i class='fas fa-eye'></i></a></td>";
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
                                ?>
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
                                        $('#tabella_interventi').DataTable({order:[[1,"desc"]], dom: 'Bfrtip', buttons: ['excel']});
                                    } );
                                </script>
                                <?php                                
                                // Attempt select query execution
                                $sql = "SELECT ii.id, ii.data, ii.descrizione, i.nome AS nome_immobile, d.nome AS nome_ditta
                                FROM interventi_immobili ii
                                LEFT JOIN immobili i ON ii.id_immobile=i.id
                                LEFT JOIN ditte d ON ii.id_ditta=d.id
                                WHERE i.circoscrizione IN ".$_SESSION["allowed_circ"]." AND i.id_tipologia = " . $row["id"];
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
                                            echo "<td>" . trunc_str($int_row['descrizione']) . "</td>";
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
    <script type="text/javascript" language="javascript" src="../../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
	<!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script> -->
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<!-- <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> -->
	<!-- <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
	<!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script> -->

    <script src="../../dist/js/pages/datatable/datatable-basic.init.js"></script>    
</body>

</html>