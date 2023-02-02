<?php

require '../../includes/app.php';

use App\Vendedor;

validaAutenticacion();

$id = $_GET['id'];

$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
}

// Obtener vendedor desde bd
$vendedor = Vendedor::find($id);

// Arreglo con mensajes de errores
$errores = Vendedor::getErrores();

// Ejecuta el codigo despues del submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Se sincroniza la peticion con la instancia
    $args = $_POST['vendedor'];
    $vendedor->sincronizar($args);

    // Validacion
    $errores = $vendedor->validar();

    if (empty($errores)) {
        $vendedor->guardar();
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar vendedor</h1>
    <a href="/admin" class="boton crear boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST">
        <?php include '../../includes/templates/formularioVendedores.php' ?>

        <input type="submit" value="Guardar cambios" class="boton-verde">

    </form>
</main>

<?php
incluirTemplate('footer');
?>