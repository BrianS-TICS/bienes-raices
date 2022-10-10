<?php

// Importacion de la conexion
include 'includes/app.php';

$db = conectarBD();

// Crear un email y password
$email = 'correo@correo.com';
$password = '123456';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);


var_dump($passwordHash); 


// Query para crear usuario
$query = " INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}'); ";

echo $query;
// Agregarlo a la base de datos

mysqli_query($db, $query);

?>