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


$router->get('/', [ $CapitauxController, 'showFormCapitaux' ]);
$router->post('/capitauxControl', [ $CapitauxController, 'TraiterInsertionCapitaux' ]); 

$router->get('/login', [ $Welcome_Controller, 'homeLogin' ]); 
$router->get('/register', [ $Welcome_Controller, 'homeRegister' ]);
$router->get('/adminConnexion', [ $Welcome_Controller, 'homeAdmin' ]);



//	Routes des treatments -> ex : /treatment/login
$router->group('/treatment', function() use ($router) {

});