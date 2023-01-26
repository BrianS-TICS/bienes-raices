<?php

require '../../includes/app.php';

use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

validaAutenticacion();

$propiedad = new Propiedad();

// Consulta para obtener vendedores
$vendedores = Vendedor::all();

// Arreglo con mensajes de errores
$errores = Propiedad::getErrores();

// Ejecuta el codigo despues del submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Crea una nueva instancia
    $propiedad = new Propiedad($_POST['propiedad']);

    // Subida de archivos temporales
    // Crear carpeta
    $carpetaImagenes = '../../imagenes/';
    if (!is_dir($carpetaImagenes)) {
        mkdir($carpetaImagenes);
    }

    // // Generar un nombre unico
    $nombreImagen = md5( uniqid( rand(), true )) . ".jpg";


    // Realiza un resize a la imagen con intervetion
    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    // Validacion
    $errores = $propiedad->validar();

    if (empty($errores)) {

        //Crear la carpeta para subir imagenes
        if(!is_dir(CARPETA_IMAGENES)){
            mkdir(CARPETA_IMAGENES);
        }

        // Guarda la imagen en el servidor
        $image->save(CARPETA_IMAGENES . $nombreImagen);

        // Guarda en la base de datos
        $propiedad->guardar();
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear</h1>
    <a href="/admin" class="boton crear boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
    <?php include '../../includes/templates/formularioPropiedades.php' ?>
    
    <input type="submit" value="Crear Propiedad" class="boton-verde">

    </form>
</main>

<?php
incluirTemplate('footer');
?>