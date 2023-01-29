<?php


define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
define('CARPETA_IMAGENES', __DIR__ . '/../imagenes/');


function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/${nombre}.php";
}

function validaAutenticacion()
{
    session_start();
    if (!$_SESSION['login']) {
        header('Location: /');
    }
}

function debugear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";

    exit;
}

// Escapa sanitiza
function sanitizar($html): string
{
    $sanitizar = htmlspecialchars($html);
    return $sanitizar;
}

// Valida tipo de contenido
function validarTipoContenido($tipo)
{
    $tipos = ['vendedor', 'propiedad'];
    return in_array($tipo, $tipos);
}
