<?php
require 'includes/app.php';

// Importacion de la conexion
$db = conectarBD();
// Autemticacion del usuario
$errores = [];

if($_SERVER['REQUEST_METHOD'] === "POST") {

    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) );
    $password = mysqli_real_escape_string($db, $_POST['password'] );

    if(!$email){
        $errores[] = 'El email esta vacio o no es valido';
    }

    if(!$password){
        $errores[] = 'El password es obligatorio';
    }

    // Empiezan validaciones para crear al usuario
    if(empty($errores)){
        // Revisar si un usuario existe
        $query = "SELECT * FROM usuarios WHERE email = '${email}' ";
        $resutado = mysqli_query($db, $query);

        if( $resutado -> num_rows ){
            // Verificamos si el password es correcto
            $usuario = mysqli_fetch_assoc($resutado);

            $auth = password_verify($password, $usuario['password']);

            if(!$auth){
                $errores[] = "La contraseña es incorrecta";
            }else{
                // Si no hay errores y la contraseña es correcta
                session_start();
                 
                // Llenar el arreglo de la sesion
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;

                header('Location: /admin');
            }

        }
        else{
            $errores[] = "Este usuario no existe"; 
        }

    }

}
// Inclusion de header
incluirTemplate('header');
?>

<main class="contenedor contenido-centrado">
    <h1>Iniciar seción</h1>

    <?php 
        foreach($errores as $error):
    ?> 
        <div class="alerta error">
            <?php echo $error ?> 
        </div>
    <?php 
        endforeach;
    ?>

    <form action="" class="formulario" method="POST" novalidate >
        <fieldset class="">
            <legend>Email y password</legend>
            <label for="email">E-mail</label>
            <input name="email" type="email" placeholder="Escribe tu email" id="email" required >
            <label for="password">Password</label>
            <input name="password" type="password" placeholder="Escribe tu contraseña" id="password" required >

        </fieldset>
        <input type="submit" class="boton-verde" value="Iniciar seción" />
    </form>

</main>

<?php
incluirTemplate('footer');
?>