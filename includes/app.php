<?php

require "funciones.php";
require "config/database.php";
require __DIR__ . "/../vendor/autoload.php";

$db = conectarBD();

use App\Propiedad;

Propiedad::setDB($db);
