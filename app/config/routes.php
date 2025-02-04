<?php

use app\controllers\ApiExampleController;
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

$alimentationController = new AlimentationController();

$router->get('/',function (){
    Flight::render('test_nourriture');
});


$router->post('/nourrir',[$alimentationController,'Nourrir']);
$router->post('/acheter/nourriture',[$alimentationController,'AcheterAlimentation']);
$router->post('/nourriture',[$alimentationController,'GetAlimentActuel']);

//	Routes des treatments -> ex : /treatment/login
$router->group('/treatment', function() use ($router) {

});