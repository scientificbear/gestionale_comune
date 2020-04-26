<?php
// Initialize the session
require_once "protect.php";

?>


<!DOCTYPE html>
<html dir="ltr">

<head>
    <?php include "head.php"; ?>
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(../../assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(../../assets/images/big/4.jpg);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <h2 class="mt-3 text-center">Errore</h2>
                        <p>Non sei autorizzato a vedere questa pagina.</p>
                        <p>Torna alla <a href="../home/index.php">home page</a></p>

                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js "></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $(".preloader ").fadeOut();
    </script>
</body>

</html>