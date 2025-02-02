<?php

use app\controllers\ApiExampleController;
use app\controllers\WelcomeController;
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

$Welcome_Controller = new WelcomeController();
$router->get('/', [ $Welcome_Controller, 'homeLogin' ]);
$router->get('/home', [ $Welcome_Controller, 'home' ]); 
$router->get('/login', [ $Welcome_Controller, 'homeLogin' ]); 
$router->get('/register', [ $Welcome_Controller, 'homeRegister' ]);
$router->get('/adminConnexion', [ $Welcome_Controller, 'homeAdmin' ]);



//	Routes des treatments -> ex : /treatment/login
$router->group('/treatment', function() use ($router) {

	$ConnectionController = new ConnectionController();
	$router->post('/login', [ $ConnectionController, 'testConnection' ]);
	$router->post('/register', [ $ConnectionController, 'createUser' ]);
	$router->get('/deconnection', [ $ConnectionController, 'deconnectionUser' ]);
	$router->post('/loginAdmin', [ $ConnectionController, 'testConnectionAdmin' ]);
	$router->get('/deconnectionAdmin', [ $ConnectionController, 'deconnectionAdmin' ]);

});