<?php

use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

require '../../includes/app.php';

validaAutenticacion();

$id = ($_GET["id"]);
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin'); // Si se modifica el id redireccionamos
}

// Obtener los datos de la propiedad
$propiedad = Propiedad::find($id);
$venderores = Vendedor::all();

// Consultamos para obtener los vendedores
$consulta = "SELECT * FROM vendedores";


// Respuesta de vendedores
$respuesta = mysqli_query($db, $consulta);
// Arreglo con mensajes de errores
$errores = Propiedad::getErrores();

// Ejecuta el codigo despues del submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Asignar los atributos
    $args = $_POST['propiedad'];

    $propiedad->sincronizar($args);

    // Validacion
    $errores = $propiedad->validar();

    // Generar un nombre unico
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    // Subida de archivos
    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }


    if (empty($errores)) {
        if ($_FILES['propiedad']['tmp_name']['imagen']) {
            // Almacenar la imagen
            $image->save(CARPETA_IMAGENES . $nombreImagen);
        }
        $propiedad->guardar();
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar</h1>
    <a href="/admin" class="boton crear boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php
        include '../../includes/templates/formularioPropiedades.php'
        ?>

        <input type="submit" value="Guardar cambios" class="boton-verde">

    </form>
</main>

<?php
incluirTemplate('footer');
?>