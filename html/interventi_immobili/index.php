<?php
require_once "../general/protect.php";
require_once "../general/utils.php";
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Inteventi</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Interventi</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="customize-input float-right">
                            <a href="create.php" class="btn btn-success btn-circle-lg"><i class="fa fa-plus"></i></a>
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
                                <div class="table-responsive">
                                <?php
                                // Include config file
                                require_once "../general/config.php";
                                
                                // Attempt select query execution
                                $sql = "SELECT ii.*, i.nome AS nome_immobile, i.indirizzo, d.nome AS nome_ditta, d.email
                                FROM interventi_immobili ii
                                LEFT JOIN immobili i ON ii.id_immobile=i.id
                                LEFT JOIN ditte d ON ii.id_ditta=d.id
                                WHERE i.circoscrizione IN ".$_SESSION["allowed_circ"];
                                if($result = $mysqli->query($sql)){
                                    if($result->num_rows > 0){
                                    echo '<table id="zero_config" class="table table-striped table-bordered no-wrap">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>data</th>";
                                    echo "<th>descrizione</th>";
                                    echo "<th>immobile</th>";
                                    echo "<th>ditta</th>";
                                    echo "<th>Azioni</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = $result->fetch_array()){
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['data'] . "</td>";
                                        echo "<td>" . trunc_str($row['descrizione']) . "</td>";
                                        echo "<td>".$row['nome_immobile']."<br/>(".$row['indirizzo'].")</td>";
                                        echo "<td>".$row['nome_ditta']."<br/>(".$row['email'].")</td>";
                                        echo "<td><a href='read.php?id=". $row['id'] ."' title='Vedi Record'><i class='fas fa-eye'></i></a><span> - </span>";
                                        echo "<a href='print.php?id=". $row['id'] ."' title='Stampa Record'><i class='fas fa-print'></i></a><span> - </span>";
                                        echo "<a href='update.php?id=". $row['id'] ."' title='Aggiorna Record'><i class='fas fa-edit'></i></a><span> - </span>";
                                        echo "<a href='delete.php?id=". $row['id'] ."' title='Elimina Record'><i class='fas fa-trash-alt'></i></a></td>";
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