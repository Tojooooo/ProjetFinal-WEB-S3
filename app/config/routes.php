<?php

use app\controllers\ApiExampleController;
use app\controllers\WelcomeController;
use app\controllers\CapitauxController;
use app\controllers\AlimentationController;
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

$router->get('/', [ $WelcomeController, 'dashboard' ]);
$router->post('/capitauxControl', [ $CapitauxController, 'TraiterInsertionCapitaux' ]); 
$router->get('/accueil',[$CapitauxController,'showPageAcceuil']);
$alimentationController = new AlimentationController();


//	Routes des formulaire -> ex : /formulaire/login
$router->group('/formulaire', function() use ($router) {
	$router->group('/achat', function() use ($router) {
		$router->get('/animal', [  ]);
	});
});

// $router->get('/',function (){
//     Flight::render('test_nourriture');
// });

// $router->get('/nourrir',function (){
//     Flight::render('nourrir');
// });

$router->post('/nourrir',[$alimentationController,'Nourrir']);
$router->post('/achat/alimentation',[$alimentationController,'AcheterAlimentation']);
$router->post('/alimentation',[$alimentationController,'GetAlimentActuel']);

//	Routes des treatments -> ex : /treatment/login
$router->group('/treatment', function() use ($router) {

});