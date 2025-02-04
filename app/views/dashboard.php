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


                <div class="row">
                    <div class="col-md-6">
                        <h1 class="page-header">
                            Dashboard 
                            
                            <small id="pageDate">--/--/--</small>
                        </h1>
						<ol class="breadcrumb">
                            <form id="dateForm" method="post">
                                <input type="date" name="date">
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
								<h3>8,457</h3>
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
								<h3>8,457</h3>
                               <strong>Nombre d'animaux possédés</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green green">
                            <div class="panel-left pull-left green">
                                <i class="fa fa-eye fa-5x"></i>
                                
                            </div>
                            <div class="panel-right">
								<h3>8,457</h3>
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
								<h3>8,457</h3>
                               <strong>Nombre d'animaux vendus</strong>
                            </div>
                        </div>
                    </div>
                    
                </div>
			
		<div class="row">
			<div class="col-xs-6 col-md-6">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>pourcentage alimentation animaux</h4>
						<div class="easypiechart" id="easypiechart-blue" data-percent="82" ><span class="percent">82%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-6">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>pourcentage décès animaux</h4>
						<div class="easypiechart" id="easypiechart-orange" data-percent="55" ><span class="percent">55%</span>
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

                            <a href="#" class="list-group-item">
                                <span class="badge">60</span>
                                <i class="fa fa-fw fa-comment"></i> Boeuf
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="badge">12</span>
                                <i class="fa fa-fw fa-truck"></i> Porc
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="badge">1</span>
                                <i class="fa fa-fw fa-globe"></i> Raphaël
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="badge">28</span>
                                <i class="fa fa-fw fa-user"></i> Poule
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="badge">31</span>
                                <i class="fa fa-fw fa-user"></i> Canard
                            </a>
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
                                        if(isset($data)) {
                                            foreach($data as $espece) { ?>
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
        // Fonction pour formater la date en JJ/MM/AA
        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = String(date.getFullYear()).slice(-2);
            return `${day}/${month}/${year}`;
        }

        // Mettre la date actuelle au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            document.getElementById('pageDate').textContent = formatDate(today);
            
            // Mettre la date actuelle dans l'input date
            const dateInput = document.querySelector('input[type="date"]');
            dateInput.valueAsDate = today;
        });

        // Gérer la soumission du formulaire
        document.getElementById('dateForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher le rechargement de la page
            
            const formData = new FormData(this);
            const selectedDate = new Date(formData.get('date'));
            
            // Mettre à jour l'affichage de la date
            document.getElementById('pageDate').textContent = formatDate(selectedDate);
            
            // Envoyer la date au serveur via Ajax
            fetch('fetch-data-from-date.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Traiter les données reçues du serveur
                console.log('Données reçues:', data);
                // Ici vous pouvez ajouter le code pour utiliser les données
                // Par exemple : mettre à jour d'autres éléments de la page
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    </script>
 

</body>

</html>