<?php

namespace App;

use App\Propiedad as AppPropiedad;
use PDO;

class Propiedad extends ActiveRecord
{
    protected static $tabla = 'propiedades';
    protected static $columnasBD = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

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
        $this->vendedorId = $args['vendedorId'] ?? "";
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
}
