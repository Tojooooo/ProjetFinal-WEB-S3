<?php

use app\controllers\ApiExampleController;
use app\controllers\WelcomeController;
use app\controllers\CapitauxController;
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


//	Routes des formulaire -> ex : /formulaire/login
$router->group('/formulaire', function() use ($router) {
	$router->group('/achat', function() use ($router) {
		$router->get('/animal', [  ]);
	});
});