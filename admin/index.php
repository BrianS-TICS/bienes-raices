<?php

//Sesion
require '../includes/app.php';

validaAutenticacion();

use App\Propiedad;
use App\Vendedor;
// Implementar un metodo para obtener todas las propiedades
$propiedades = Propiedad::all();
$vendedores = Vendedor::all();

// Muestra mensaje condicional en url
$resultado = $_GET['resultado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {

        $propiedad = Propiedad::find($id);
        $propiedad->eliminar();
    }
}


// Incluye templade
incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de bienes raices</h1>

    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta correcto"> Propiedad añadida correctamente </p>

    <?php elseif (intval($resultado) === 2) : ?>
        <p class="alerta correcto"> Propiedad editada correctamente </p>

    <?php elseif (intval($resultado) === 3) : ?>
        <p class="alerta correcto"> Propiedad eliminada correctamente </p>

    <?php endif ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Crear</a>


    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Mostrar los resultados -->
            <?php foreach($propiedades as $propiedad): ?>
                <tr>
                    <td><?php echo $propiedad->id; ?></td>
                    <td><?php echo $propiedad->titulo; ?></td>
                    <td>
                        <img src="/imagenes/<?php echo $propiedad->imagen; ?>" alt="<?php echo 'Imagen de ' . $propiedad->titulo; ?>" class="imagen-tabla">
                    </td>
                    <td>$ <?php echo $propiedad->precio; ?></td>
                    <td>
                        <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id ?>" class="boton-editar-block">Editar</a>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad->id ?>">
                            <input type="submit" class="boton-eliminar-block" value="Eliminar">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</main>

<?php

// Cerrra la conexion
mysqli_close($db);

incluirTemplate('footer');

?>