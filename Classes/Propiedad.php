<?php

namespace App;

use App\Propiedad as AppPropiedad;
use PDO;

class Propiedad
{

    // BD
    protected static $db;
    protected static $columnasBD = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    // Validacion
    protected static $errores = [];


    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    // Definir conexion a bd
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? "";
        $this->precio = $args['precio'] ?? "";
        $this->imagen = $args['imagen'] ?? "";
        $this->descripcion = $args['descripcion'] ?? "";
        $this->habitaciones = $args['habitaciones'] ?? "";
        $this->wc = $args['wc'] ?? "";
        $this->estacionamiento = $args['estacionamiento'] ?? "";
        $this->creado = date("Y/m/d");
        $this->vendedorId = $args['vendedorId'] ?? 1;
    }

    public function guardar()
    {
        if (!is_null($this->id)) {
            //Actualizar
            $this->actualizar();
        } else {
            //Creando
            $this->crear();
        }
    }

    public function crear()
    {
        // Sanitizar la entrada de datos
        $atributos = $this->sanitizarDatos();

        // Insertar en base de datos
        // Itero en $atributos usando join
        $query = "INSERT INTO propiedades ( ";
        $query .=
            join(', ', array_keys($atributos));
        $query .= " )VALUES (' ";
        $query .=
            join("', '", array_values($atributos));
        $query .= " ')";

        $resultado = self::$db->query($query);

        if ($resultado) {
            header("Location: /admin?resultado=1");
        }
    }

    public function actualizar()
    {
        $atributos = $this->sanitizarDatos();
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key} = '{$value}' ";
        }

        $query = "UPDATE propiedades SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" .  self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);

        if ($resultado) {
            header("Location: /admin?resultado=2");
        }

        return $resultado;
    }

    // Elimina un registro
    public function eliminar()
    {
        // Eliminar la propiedad de la base de datos
        $query = "DELETE FROM propiedades WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if ($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    // Identifica los valores de la base de datos
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnasBD as $columna) {
            if ($columna === "id") {
                continue;
            } //Hace que ignore el ciclo
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarDatos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return ($sanitizado);
    }

    // Subida de archivos
    public function setImagen($imagen)
    {   // Elimina la imagen previa
        if (!is_null($this->id)) {
            // Comprobar si existe
            $this->borrarImagen();
        }

        // Asignar al atributo de imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    // * Elimina imagen del servidor
    public function borrarImagen()
    {
        // Elimina el archivo del servidor
        // Comprobar si existe
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // Validacion
    public static function getErrores()
    {
        return self::$errores;
    }

    public function validar()
    {
        if (!$this->titulo) {
            self::$errores[] = "Debes añadir un titulo";
        }

        if (!$this->precio) {
            self::$errores[] = "Debes añadir un precio";
        }

        if (strlen($this->descripcion) < 50) {
            self::$errores[] = "Debes añadir una descripcion con al menos 50 caracteres";
        }

        if (!$this->habitaciones) {
            self::$errores[] = "Debes añadir el numero de habitaciones";
        }

        if (!$this->wc) {
            self::$errores[] = "Debes añadir el numero de baños";
        }

        if (!$this->estacionamiento) {
            self::$errores[] = "Debes añadir el numero de estacionamientos";
        }

        if (!$this->vendedorId) {
            self::$errores[] = "Elige un vendedor";
        }

        // Validar imagen
        if (!$this->imagen) {
            self::$errores[] = "Debes agregar una imagen";
        }

        return self::$errores;
    }
    // Lista todas las propiedades
    public static function all()
    {
        $query = "SELECT * FROM propiedades";

        $resultado = self::consultarSql($query);

        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id)
    {
        // Consultamos la propiedad
        $query = "SELECT * FROM propiedades WHERE id = ${id}";
        $resultado = self::consultarSql($query);

        return (array_shift($resultado));
    }

    public static function consultarSql($query)
    {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar la bd
        $array = [];

        while ($registro = $resultado->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }

        // Liberar memoria 
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new self;
        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Sincronisa el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = [])
    {
        // Ojo
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
