<?php

require_once "../general/protect.php";
require_once "../general/utils.php";
check_user($_SESSION["role"], array("admin", "editor"));
// Include config file
require_once "../general/config.php";
 
// Define variables and initialize with empty values
$nome = $indirizzo = $cap = $comune = $provincia = $email = $telefono_ref = $nome_ref = $id_categoria = "";
$id_err = $nome_err = $indirizzo_err = $cap_err = $comune_err = $provincia_err = $email_err = $telefono_ref_err = $nome_ref_err = $id_categoria_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate
    list($nome, $nome_err) = check_variable($_POST["nome"], "nome");
    list($indirizzo, $indirizzo_err) = check_variable($_POST["indirizzo"], "indirizzo");
    list($comune, $comune_err) = check_variable($_POST["comune"], "comune");
    list($cap, $cap_err) = check_variable($_POST["cap"], "cap");
    list($provincia, $provincia_err) = check_variable($_POST["provincia"], "provincia");
    list($email, $email_err) = check_variable($_POST["email"], "email");
    list($id_categoria, $id_categoria_err) = check_variable($_POST["id_categoria"], "id_categoria");

    $telefono_ref = trim($_POST["telefono_ref"]);
    $nome_ref = trim($_POST["nome_ref"]);
    
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
        empty($id_categoria_err);

    if($error_check){
        $table = "ditte";
        $field = array("nome", "indirizzo", "cap", "comune", "provincia", "email", "telefono_ref", "nome_ref", "id_categoria");
        $data = array($nome, $indirizzo, $cap, $comune, $provincia, $email, $telefono_ref, $nome_ref, $id_categoria);
        $result = insert_data($table,$field,$data,$mysqli);

        if($result){

            $last_id = $mysqli->insert_id;
            $insert_values = "";
            foreach(array(1,2,3,4,5,6,7,8) as $c_name){
                $insert_values = $insert_values . "(".$last_id.",".$c_name."), ";
            }
            $insert_values = rtrim($insert_values, ", ");
            $sql_circ = "INSERT INTO ditte_circoscrizioni (id_ditta, circoscrizione) VALUES ".$insert_values;
            $result = $mysqli->query($sql_circ);
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
        $("#sel_categoria_ditte").select2();

        // Read selected option
        $('#but_read').click(function(){
        var categoria = $('#sel_categoria_ditte option:selected').text();
        var id_categoria = $('#sel_categoria_ditte').val();

        $('#result').html("id : " + id_categoria + ", categoria : " + categoria);

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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Inserisci una nuova ditta</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="../home/index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item"><a href="./index.php" class="text-muted">Ditte</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Nuova</li>
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
                                    <h5 class="card-title">Denominazione</h5>
                                    <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $nome_err;?>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h5 class="card-title">Indirizzo</h5>
                                    <input type="text" name="indirizzo" class="form-control" value="<?php echo $indirizzo; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $indirizzo_err;?>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h5 class="card-title">Comune</h5>
                                    <input type="text" name="comune" class="form-control" value="<?php echo $comune; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $comune_err;?>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h5 class="card-title">Provincia</h5>
                                    <input type="text" name="provincia" class="form-control" value="<?php echo $provincia; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $provincia_err;?>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h5 class="card-title">CAP</h5>
                                    <input type="text" name="cap" class="form-control" value="<?php echo $cap; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $cap_err;?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="card-title">Email</h5>
                                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $email_err;?>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h5 class="card-title">Tipologia</h5>
                                    <?php
                                        $sql = "SELECT id, categoria FROM categoria_ditte ORDER BY categoria";
                                        if($result = $mysqli->query($sql)){
                                            echo "<select class='form-control' id='sel_categoria_ditte' style='width: 100%' name='id_categoria'>";
                                            if($result->num_rows > 0){
                                                echo "<option selected='true' disabled='disabled'>categoria</option>";
                                                while($row = $result->fetch_array()){
                                                    if ($row['id']==$id_categoria){
                                                        echo "<option value=" . $row['id'] . " selected>" . $row['categoria'] . "</option>";
                                                    } else {
                                                        echo "<option value=" . $row['id'] . ">" . $row['categoria'] . "</option>";
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
                                        <div id='result'></div>
                                    <div class="invalid-feedback">
                                        <?php echo $id_categoria_err;?>
                                    </div>
                                </div>
                                <br />
                                
                                <div class="form-group">
                                    <h5 class="card-title">Referente</h5>
                                    <input type="text" name="nome_ref" class="form-control" value="<?php echo $nome_ref; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $nome_ref_err;?>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h5 class="card-title">Telefono referente</h5>
                                    <input type="text" name="telefono_ref" class="form-control" value="<?php echo $telefono_ref; ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $telefono_ref_err;?>
                                    </div>
                                </div>
                                <br />
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