<?php

require_once "../general/protect.php";
require_once "../general/utils.php";

// Include config file
require_once "../general/config.php";
 
// Define variables and initialize with empty values
$data = date("Y-m-d");
$descrizione = $id_immobile = $id_ditta = "";
$data_err = $descrizione_err = $id_immobile_err = $id_ditta_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate
    list($descrizione, $descrizione_err) = check_variable($_POST["descrizione"], "descrizione");

    $id_utente = $_SESSION["id"];
    $id_immobile = $_POST["id_immobile"];
    $id_ditta = $_POST["id_ditta"];
    $data = $_POST["data"];

    // Check input errors before inserting in database
    $error_check = empty($id_err) &&
    empty($descrizione_err) &&
    empty($data_err);

    if($error_check){
        $table = "interventi_immobili";
        $field = array("descrizione","data","id_immobile","id_utente","id_ditta");
        $data = array($descrizione,$data,$id_immobile,$id_utente,$id_ditta);
        $result = insert_data($table,$field,$data,$mysqli);

        if($result){
            // Records created successfully. Redirect to landing page
            header("location: index.php");
            exit();

            // Close connection
            $mysqli->close();

        } else{
            echo "Something went wrong. Please try again later (", $mysqli->error, ")";
        }
    }
}
?>
 
<!DOCTYPE html>
<html dir="ltr" lang="it">

<head>
    <?php include "../general/head.php"; ?>
    <!-- This page plugin CSS -->
    <link href="../../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
        
        // Initialize select2
        $("#sel_immobili").select2();

        // Read selected option
        $('#but_read').click(function(){
        var nome = $('#sel_immobili option:selected').text();
        var id_immobile = $('#sel_immobili').val();

        $('#result_immobili').html("id : " + id_immobile + ", nome : " + nome);

        });
    });
    </script>

    <script type="text/javascript">
    $(document).ready(function(){
        
        // Initialize select2
        $("#sel_ditte").select2();

        // Read selected option
        $('#but_read').click(function(){
        var nome = $('#sel_ditte option:selected').text();
        var id_ditta = $('#sel_ditte').val();

        $('#result_ditte').html("id : " + id_ditta + ", nome : " + nome);

        });
    });
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Inserisci un nuovo intervento</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="./index.php" class="text-muted">Interventi</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Nuovo</li>
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
                            <div class="card-body">
                        <form class="mt-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="card-title">Data</h5>
                                    <input type="text" name="data" class="form-control" value="<?php echo $data; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $data_err;?>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h5 class="card-title">Descrizione</h5>
                                    <input type="text" name="descrizione" class="form-control" value="<?php echo $descrizione; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $descrizione_err;?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="card-title">Immobile</h5>
                                    <?php
                                        $sql = "SELECT id, nome, indirizzo, circoscrizione FROM immobili WHERE circoscrizione IN ".$_SESSION["allowed_circ"]." ORDER BY circoscrizione, nome";
                                        if($result = $mysqli->query($sql)){
                                            echo "<select class='form-control' id='sel_immobili' name='id_immobile' style='width:100%'>";
                                            if($result->num_rows > 0){
                                                echo "<option selected='true' disabled='disabled'>Immobile</option>";
                                                while($row = $result->fetch_array()){
                                                    if ($row['id']==$id_immobile){
                                                        echo "<option value=".$row['id']." selected>".$row['circoscrizione']."^: ".$row['nome']." (".$row['indirizzo'].")</option>";
                                                    } else {
                                                        echo "<option value=".$row['id'].">".$row['circoscrizione']."^: ".$row['nome']." (".$row['indirizzo'].")</option>";
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
                                        <div id='result_immobili'></div>
                                    <div class="invalid-feedback">
                                        <?php echo $id_immobili_err;?>
                                    </div>
                                </div>
                                <br />
                                
                                <div class="form-group">
                                    <h5 class="card-title">Ditta</h5>
                                    <?php
                                        $sql = "SELECT d.id, d.nome, cd.categoria FROM ditte d
                                        LEFT JOIN categoria_ditte cd ON d.id_categoria=cd.id
                                        INNER JOIN (SELECT DISTINCT (id_ditta)
                                        FROM utenti_circoscrizioni uc
                                        INNER JOIN ditte_circoscrizioni dc
                                        ON uc.circoscrizione=dc.circoscrizione
                                        WHERE id_utente=".$_SESSION["id"].")
                                        AS sd ON d.id=sd.id_ditta
                                        ORDER BY nome";
                                        if($result = $mysqli->query($sql)){
                                            echo "<select class='form-control' id='sel_ditte' name='id_ditta' style='width:100%'>";
                                            if($result->num_rows > 0){
                                                echo "<option selected='true' disabled='disabled'>Ditta</option>";
                                                while($row = $result->fetch_array()){
                                                    if ($row['id']==$id_ditta){
                                                        echo "<option value=".$row['id']." selected>".$row['nome']." (".$row['categoria'].")</option>";
                                                    } else {
                                                        echo "<option value=".$row['id'].">".$row['nome']." (".$row['categoria'].")</option>";
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
                                    <div id='result_ditte'></div>
                                    <div class="invalid-feedback">
                                        <?php echo $id_ditte_err;?>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="form-actions">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-info">Salva</button>
                                    <button type="reset" class="btn btn-dark">Reset</button>
                                </div>
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