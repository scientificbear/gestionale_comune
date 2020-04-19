<?php
require_once "protect.php";
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Benvenuto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Ciao, <b><?php echo htmlspecialchars($_SESSION["nome"]); ?> <?php echo htmlspecialchars($_SESSION["cognome"]); ?></b>. Benvenuto.</h1>
    </div>
    <p>
    <ul>
    <li><a href="ditte/">ditte</a></li>
    <li><a href="categoria_ditte/">categoria ditte</a></li>
    <li><a href="immobili/">immobili</a></li>
    <li><a href="tipo_immobili/">tipo immobili</a></li>
    <li><a href="interventi_immobili/">interventi</a></li>
    </ul>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>
