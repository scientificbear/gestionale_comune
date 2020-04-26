<?php
require_once "../general/protect.php";
require_once "../general/config.php";
?>
 
<!DOCTYPE html>
<html dir="ltr" lang="it">

<head>
    <?php include "../general/head.php"; ?>
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
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Ciao <?php echo htmlspecialchars($_SESSION["nome"]); ?> <?php echo htmlspecialchars($_SESSION["cognome"]); ?>!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a>
                                    </li>
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
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Interventi segnalati</h4>
                                <?php $sql = "SELECT count(*) AS c from interventi_immobili";
                                    if($result = $mysqli->query($sql)){
                                        if($result->num_rows == 1){
                                            $row = $result->fetch_array(MYSQLI_ASSOC);
                                            echo "<h1 class='text-center'>".$row["c"]."</h1>";
                                        } else{
                                            header("location: ../general/error.php");
                                            exit();
                                        }       
                                    } else {
                                        echo "ERROR: Non riesco ad eseguire $sql. " . $mysqli->error;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Immobili</h4>
                                <?php $sql = "SELECT count(*) AS c from immobili";
                                    if($result = $mysqli->query($sql)){
                                        if($result->num_rows == 1){
                                            $row = $result->fetch_array(MYSQLI_ASSOC);
                                            echo "<h1 class='text-center'>".$row["c"]."</h1>";
                                        } else{
                                            header("location: ../general/error.php");
                                            exit();
                                        }       
                                    } else {
                                        echo "ERROR: Non riesco ad eseguire $sql. " . $mysqli->error;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Ditte</h4>
                                <?php $sql = "SELECT count(*) AS c from ditte";
                                    if($result = $mysqli->query($sql)){
                                        if($result->num_rows == 1){
                                            $row = $result->fetch_array(MYSQLI_ASSOC);
                                            echo "<h1 class='text-center'>".$row["c"]."</h1>";
                                        } else{
                                            header("location: ../general/error.php");
                                            exit();
                                        }       
                                    } else {
                                        echo "ERROR: Non riesco ad eseguire $sql. " . $mysqli->error;
                                    }
                                ?>
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
</body>

</html>