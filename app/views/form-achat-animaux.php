<?php

    $especes = Flight::tempModel()->GetAllEspeces();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap Styles-->
    <link href="<?= Flight::get("flight.base_url") ?>/assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="<?= Flight::get("flight.base_url") ?>/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="<?= Flight::get("flight.base_url") ?>/assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="<?= Flight::get("flight.base_url") ?>/assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 
    <link rel="stylesheet" href="<?= Flight::get('flight.base_url') ?>/assets/css/form-crud.css">
    <title>CRUD</title>
</head>
<body>

<div id="wrapper">
    <?php include("frame.php"); ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10 mx-auto">
                    <div class="panel panel-primary text-center bg-color-green green" style="margin-top: 20px; color: cadetblue;">
                        <h1>Formulaire d'ajout d'animal</h1>
                        <form action="<?= Flight::get("flight.base_url") ?>/treatment/achat/animal" method="post" class="form" style="max-width: none;">
                            <label>
                                <select required name="id_espece" class="select">
                                    <?php
                                    foreach($especes as $espece) { ?>
                                        <option value="<?= $espece["id_espece"] ?>"><?= $espece["nom"] ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <span>select espece</span>
                            </label>
                                    
                            <label>
                                <input required name="poids" type="number" step="0.01" min="0" placeholder="" class="input">
                                <span>poids</span>
                            </label>
                            
                            <label>
                                <input required name="quantite" type="number" placeholder="" class="input">
                                <span>quantite</span>
                            </label>
                                
                            <label>
                                <input required name="date_achat" type="date" placeholder="" class="input">
                                <span>date d'achat</span>
                            </label>
                    
                            <button type="submit" class="fancy" href="#">
                                <span class="top-key"></span>
                                <span class="text">submit</span>
                                <span class="bottom-key-1"></span>
                                <span class="bottom-key-2"></span>
                            </button>

                            <?php
                            if (isset($error)) { ?>
                            <h2 style="color: red;"><?= $error ?></h2>
                            <?php }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        function updateFileNames(input) {
            const fileNamesDiv = input.parentElement.querySelector('.file-names');
            if (input.files.length > 0) {
                const fileNames = Array.from(input.files).map(file => file.name).join(', ');
                fileNamesDiv.textContent = fileNames;
            } else {
                fileNamesDiv.textContent = '';
            }
        }
    </script>

    <?php include("bootstrap-script.php"); ?>
    
</body>
</html>