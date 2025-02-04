<?php

use app\controllers\WelcomeController;
use app\controllers\CapitauxController;
use app\controllers\AlimentationController;
use app\controllers\AnimauxController;
use flight\Engine;
use flight\net\Router;
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */
/*$router->get('/', function() use ($app) {
	$Welcome_Controller = new WelcomeController($app);
	$app->render('welcome', [ 'message' => 'It works!!' ]);
});*/

$CapitauxController = new CapitauxController();
$WelcomeController = new WelcomeController();


$AlimentationController = new AlimentationController();
$AnimauxController = new AnimauxController();

$router->get('/', [ $WelcomeController, 'dashboard' ]);
$router->post('/capitauxControl', [ $CapitauxController, 'TraiterInsertionCapitaux' ]); 
$router->get('/accueil',[$CapitauxController,'showPageAcceuil']);


//	Routes des formulaire -> ex : /formulaire/login
$router->group('/achat', function() use ($router) {
	$AnimauxController = new AnimauxController();
	$router->get('/animal', [ $AnimauxController, 'formulaireAchat' ]);
});

$router->group('/achat', function() use ($router) {
	$AnimauxController = new AnimauxController();
	$router->get('/alimentation', [ $AnimauxController, 'formulaireAchat2' ]);
});
//$router->get('/alimentation',[$AnimauxController,'formulaireAchat2']);

// $router->get('/',function (){
//     Flight::render('test_nourriture');
// });

// $router->get('/nourrir',function (){
//     Flight::render('nourrir');
// });

$router->get('/insererCapital', [ $CapitauxController, 'showFormCapitaux' ]);
$router->post('/nourrir',[$AlimentationController,'Nourrir']);
$router->post('/achat/alimentation',[$AlimentationController,'AcheterAlimentation']);
$router->post('/alimentation',[$AlimentationController,'GetAlimentActuel']);
$router->get('/dashboard', [ $WelcomeController, 'dashboard' ]);
$router->post('/rafraichir', [ $WelcomeController, 'refreshDate' ]);
$router->get('/reinitialiser', [ $WelcomeController, 'reinitialiser' ]);

//	Routes des treatments -> ex : /treatment/login
$router->group('/treatment', function() use ($router) {
	$CapitauxController = new CapitauxController();
	$router->group('/achat', function() use ($router) {
		$AnimauxController = new AnimauxController();
		$router->post('/animal', [ $AnimauxController, 'acheterAnimal' ]);
	});
});

$router->group('/treatment', function() use ($router) {
	$router->group('/achat', function() use ($router) {
		$AlimentationController = new AlimentationController();
		$router->post('/alimentation', [ $AlimentationController, 'actionAcheterAlimentation' ]);
	});
	$CapitauxController = new CapitauxController();
	$router->post('/insertionCapital', [ $CapitauxController, 'TraiterInsertionCapitaux' ]);
});