<?php

namespace App;

class ActiveRecord
{
    // BD
    protected static $db;
    protected static $columnasBD = [];

    protected static $tabla = '';

    // Validacion
    protected static $errores = [];


    // Definir conexion a bd
    public static function setDB($database)
    {
        self::$db = $database;
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
        $query = "INSERT INTO " . static::$tabla . " ( ";
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

        $query = "UPDATE " . static::$tabla . " SET ";
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
        $query = "DELETE FROM " . static::$tabla .  " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
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
        foreach (static::$columnasBD as $columna) {
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
        return static::$errores;
    }

    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }
    // Lista todos los registros
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSql($query);

        return $resultado;
    }

    // Lista todos los registros
    public static function get($cantidad)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSql($query);

        return $resultado;
    }


    // Busca un registro por su id
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = " . $id . "";
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
            $array[] = static::crearObjeto($registro);
        }

        // Liberar memoria 
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;
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
