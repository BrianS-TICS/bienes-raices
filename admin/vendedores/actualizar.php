<?php

require '../../includes/app.php';

use App\Vendedor;

validaAutenticacion();

$vendedor = new Vendedor();

// Arreglo con mensajes de errores
$errores = Vendedor::getErrores();

// Ejecuta el codigo despues del submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    <form class="formulario" method="POST" action="/admin/vendedores/actualizar.php" enctype="multipart/form-data">
        <?php include '../../includes/templates/formularioVendedores.php' ?>

        <input type="submit" value="Guardar cambios" class="boton-verde">

    </form>
</main>

<?php
incluirTemplate('footer');
?>