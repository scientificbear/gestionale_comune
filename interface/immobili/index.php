<!-- vedi https://www.tutorialrepublic.com/php-tutorial/php-mysql-crud-application.php -->
<?php
require_once "../protect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Dettaglio immobili</h2>
                        <a href="create.php" class="btn btn-success pull-right">Aggiungi nuovo immobile</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "../config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM immobili i LEFT JOIN tipo_immobili ti ON i.id_tipologia=ti.id";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>nome</th>";
                                    echo "<th>descrizione</th>";
                                    echo "<th>indirizzo</th>";
                                    echo "<th>cap</th>";
                                    echo "<th>circoscrizione</th>";
                                    echo "<th>codice</th>";
                                    echo "<th>tipologia</th>";
                                    echo "<th>nome_ref</th>";
                                    echo "<th>telefono_ref</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['nome'] . "</td>";
                                    echo "<td>" . $row['descrizione'] . "</td>";
                                    echo "<td>" . $row['indirizzo'] . "</td>";
                                    echo "<td>" . $row['cap'] . "</td>";
                                    echo "<td>" . $row['circoscrizione'] . "</td>";
                                    echo "<td>" . $row['codice'] . "</td>";
                                    echo "<td>" . $row['tipologia'] . "</td>";
                                    echo "<td>" . $row['nome_ref'] . "</td>";
                                    echo "<td>" . $row['telefono_ref'] . "</td>";
                                    echo "<td>";
                                            echo "<a href='read.php?id=". $row['id'] ."' title='Vedi Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Aggiorna Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Elimina Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
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
</body>
</html>
