<?php

require_once "../general/protect.php";
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "../general/config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM categoria_ditte WHERE id = ?";
    
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
                $categoria = $row["categoria"];
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Visualizza categoria</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">Categoria ditta</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Vedi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="customize-input float-right">
                            <a href="<?php echo 'update.php?id='.$row["id"]?>" class="btn btn-secondary btn-circle-lg"><i class="fa fas fa-edit"></i></a>
                            <a href="<?php echo 'delete.php?id='.$row["id"]?>" class="btn btn-danger btn-circle-lg"><i class="fa fa-trash-alt"></i></a>
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
                                    <h5 class="card-title">Categoria</h5>
                                    <p class="card-text"><?php echo $row["categoria"]; ?></p>
                                <div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Ditte associate</h3>
                                <br />
                                <div class="table-responsive">
                                <?php                                
                                // Attempt select query execution
                                $sql = "SELECT * FROM ditte WHERE id_categoria = " . $row["id"] . " ORDER BY nome";
                                if($result = $mysqli->query($sql)){
                                    if($result->num_rows > 0){
                                    echo '<table id="zero_config" class="table table-striped table-bordered no-wrap">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Nome</th>";
                                    echo "<th>Indirizzo</th>";
                                    echo "<th>Comune</th>";
                                    echo "<th>Provincia</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($det_row = $result->fetch_array()){
                                        echo "<tr>";
                                        echo "<td>" . $det_row['id'] . "</td>";
                                        echo "<td>" . $det_row['nome'] . "</td>";
                                        echo "<td>" . $det_row['indirizzo'] . "</td>";
                                        echo "<td>" . $det_row['comune'] . "</td>";
                                        echo "<td>" . $det_row['provincia'] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                // Free result set
                                $result->free();
                            } else{
                                echo "<p class='lead'><em>Nessun risultato trovato.</em></p>";
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