<?php

namespace App;

use App\Core\Router;
use App\Core\ConstantManager;
use App\Core\Security;

require "Autoload.php";
Autoload::register();

Security::connect();

new ConstantManager();

//require "Core/Router.php";

//Instance de la classe router (dossier CORE) avec en paramètre la slug
$route = new Router($_SERVER["REQUEST_URI"]);
//On récupère le controller et l'action correspond au slug
$c = $route->getController();
$a = $route->getAction();
$d = $route->getData();

//vérification que le fichier du controller existe
if (file_exists("./Controllers/" . $c . ".php")) {

	//include car on vérifie avant l'existance du fichier et surtout
	//le include est plus rapide à executer
	include "./Controllers/" . $c . ".php";

	//Le fichie existe mais est-ce que la classe existe ?


	$c = "App\\Controller\\" . $c;
	if (class_exists($c)) {

		// $c = UserController
		// Instance de la classe : la classe dépend du fichier routes.yml qui lui dépend  du slug
		//$c  =  User

		$cObject = new $c(); // new App\User
		//Est-ce que la méthode existe dans l'objet
		if (method_exists($cObject, $a)) {

			if (isset($d)) {
				//$a => addAction
				//Appel de la méthode dans l'objet, exemple UserController->addAction();
				$cObject->$a($d);
			} else {
				$cObject->$a();
			}
		} else {
			die("Error la methode n'existe pas !!!");
		}
	} else {
		die("Error la classe n'existe pas!!!");
	}
} else {
	die("Error le fichier controller " . $_SERVER["REQUEST_URI"] . " n'existe pas !!!");
}
