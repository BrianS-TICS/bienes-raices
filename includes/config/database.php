<?php 

function conectarBD() :mysqli {
    $db = new mysqli('localhost','root', 'root', 'bienesraices');

    if(!$db){
        echo 'Error no se pudo conectar';
        exit; // Hace que no se ejecuten las siguientes lineas
    }

    return $db;
}