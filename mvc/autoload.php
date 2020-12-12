<?php
//Llamo a la función spl_autoload_register que cargará automáticamente todas las clases dentro del path /classes
spl_autoload_register(function($className) {
	$className = str_replace('\\', '/', $className);
	$path = '../api/' . $className . '.php';
	if(file_exists($path)) { require $path; }
});