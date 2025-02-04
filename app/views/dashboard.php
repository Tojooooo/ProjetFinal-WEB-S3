<?php

    if(!Flight::tempModel()->GetAllCapitaux()) {
        Flight::redirect("/insererCapital");
    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accueil Dashboard</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 

</head>


<body>
    <div id="wrapper">
        
        <?php include("frame.php"); ?>
        
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                
                
                <input type="hidden" value="<?= Flight::get('flight.base_url') ?>" id="baseUrl">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="page-header">
                            Dashboard 
                            
                            <small id="pageDate"><?= $date ?></small>
                        </h1>
						<ol class="breadcrumb">
                            <form id="dateForm" method="post">
                                <input type="date" id="date" name="date">
                                <input type="submit" value="submit">
                            </form>
                        </ol>
                    </div>
                </div>
				
                <!-- /. ROW  -->

                <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green green">
                            <div class="panel-left pull-left green">
                                <i class="fa fa-eye fa-5x"></i>
                                
                            </div>
                            <div class="panel-right">
								<h3 id="capital"><?= $capital ?></h3>
                               <strong>Capitaux actuels</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green green">
                            <div class="panel-left pull-left green">
                                <i class="fa fa-eye fa-5x"></i>
                                
                            </div>
                            <div class="panel-right">
								<h3 id="nbAchetes"><?= $nbAchetes ?></h3>
                               <strong>Nombre d'animaux achetés</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green green">
                            <div class="panel-left pull-left green">
                                <i class="fa fa-eye fa-5x"></i>
                                
                            </div>
                            <div class="panel-right">
								<h3 id="nbVendus"><?= $nbVendus ?></h3>
                               <strong>Nombre d'animaux vendus</strong>
                            </div>
                        </div>
                    </div>
                    
                </div>
			
		<div class="row">
			<div class="col-xs-6 col-md-6">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4 id="pourcentage_vente">pourcentage de ventes </h4>
						<div class="easypiechart" id="easypiechart-blue" data-percent="<?= $pourcentage_vente ?>" ><span class="percent"><?= $pourcentage_vente ?>%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-6">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4 id="pourcentage_mort">pourcentage décès animaux</h4>
						<div class="easypiechart" id="easypiechart-orange" data-percent="<?= $pourcentage_mort ?>" ><span class="percent"><?= $pourcentage_mort ?>%</span>
						</div>
					</div>
				</div>
			</div>
			
		</div><!--/.row-->
			
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Nombre d'animaux par especes
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                        <?php foreach($animauxParEspeces as $espece) { ?>
                            <a href="#" class="list-group-item">
                                <span class="badge"><?= $espece["nombre"] ?></span>
                                <i class="fa fa-fw fa-comment"></i> <?= $espece["nom"] ?>
                            </a>
                        <?php } ?>
                        </div>
                        <div class="text-right">
                            <a href="#">More Tasks <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-8 col-sm-12 col-xs-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Description des espècees
                    </div> 
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>id_espèce</th>
                                        <th>nom</th>
                                        <th>poids minimum pour vente</th>
                                        <th>prix de vente par kg</th>
                                        <th>poids maximum</th>
                                        <th>jour survie sans manger</th>
                                        <th>perte de poids par jour sans manger</th>
                                        <th>modifier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(isset($especes)) {
                                            foreach($especes as $espece) { ?>
                                    <tr>
                                        <td><?= $espece["id_espece"] ?></td>
                                        <td><?= $espece["nom"] ?></td>
                                        <td><?= $espece["poids_min_vente"] ?></td>
                                        <td><?= $espece["prix_vente_kg"] ?></td>
                                        <td><?= $espece["poids_max"] ?></td>
                                        <td><?= $espece["jours_sans_manger"] ?></td>
                                        <td><?= $espece["perte_poids_par_jour_sans_manger"] ?></td>
                                        <td><a href="<?= Flight::get('flight.base_url') ?>/modifier/espece?id=<?= $espece["id_espece"] ?>">Modifier</a></td>
                                    </tr>
                                            <?php }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
                <!-- /. ROW  -->
			
		
				<footer><big>Projet ETU : 3078 - 3150 - 3159</big>
				
        
				</footer>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
	 
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Morris Chart Js -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
	
	
	<script src="assets/js/easypiechart.js"></script>
	<script src="assets/js/easypiechart-data.js"></script>
	
	 <script src="assets/js/Lightweight-Chart/jquery.chart.js"></script>
	
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>

    <!-- Dynamic Js loading -->
    <script src="assets/js/dynamic/home-script.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupération de l'URL de base
    const baseUrl = document.getElementById('baseUrl').value;

    // Sélection des éléments du DOM
    const dateForm = document.getElementById('dateForm');
    const dateInput = document.getElementById('date');
    const pageDate = document.getElementById('pageDate');

    // Fonction pour formater la date au format YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Afficher la date d'aujourd'hui par défaut
    const today = new Date();
    const todayFormatted = formatDate(today);
    pageDate.textContent = todayFormatted; // Afficher la date d'aujourd'hui
    dateInput.value = todayFormatted; // Remplir l'input de type date avec la date d'aujourd'hui

    // Écoute de l'événement de soumission du formulaire
    dateForm.addEventListener('submit', function(event) {
        // Empêche le rechargement de la page
        event.preventDefault();

        // Récupération de la date saisie
        const selectedDate = dateInput.value;

        // Affichage de la date dans l'élément <small id="pageDate">
        pageDate.textContent = selectedDate;

        // Envoi de la requête AJAX pour récupérer des données depuis une autre page PHP
        fetch(`${baseUrl}/rafraichir`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `date=${encodeURIComponent(selectedDate)}` // Envoyer la date sous forme de chaîne de caractères
        })
        .then(response => response.json()) // Supposons que la réponse est en JSON
        .then(data => {
            console.log('Données reçues:', data);
            var capital = document.getElementById("capital");
            capital.innerHTML = "";
            capital.innerHTML += data.capital;
            var nbAchetes = document.getElementById("nbAchetes");
            nbAchetes.innerHTML = "";
            nbAchetes.innerHTML += data.nbAchetes;
            var nbVendus = document.getElementById("nbVendus");
            nbAchetes.innerHTML = "";
            nbAchetes.innerHTML += data.nbAchetes;
            var animauxParEspeces = document.getElementById("animauxParEspeces");
            animauxParEspeces.innerHTML = "";
            animauxParEspeces.innerHTML += data.animauxParEspeces;
            var pourcentage_vente = document.getElementById("pourcentage_vente");
            pourcentage_vente.innerHTML = "";
            pourcentage_vente.innerHTML += data.pourcentage_vente;
            var pourcentage_mort = document.getElementById("pourcentage_mort");
            pourcentage_mort.innerHTML = "";
            pourcentage_mort.innerHTML += data.pourcentage_mort;
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données:', error);
        });
    });
});
</script>
 

</body>

</html>