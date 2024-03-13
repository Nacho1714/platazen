<?php

namespace App\Database;

use Exception;
use PDO;

/**
 * Wrapper para PDO en modo Singleton
 */
class DBConexion {

    // Datos de la conexion
    public const DB_HOST = '127.0.0.1';
    public const DB_USER = 'root';
    public const DB_PASS = ''; 
    public const DB_BASE = 'plantazen';
    public const DB_DSN = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_BASE . ';charset=utf8mb4';

    protected static ?PDO $db = null;

    /**
     * Crea una nueva instancia de DBConexion y obtiene la conexión a la base de datos
     * 
     * @return void
     */
    private static function connect()
    {

        try {
            self::$db = new PDO(self::DB_DSN, self::DB_USER, self::DB_PASS);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Esto es para que nos muestre los errores que se produzcan.
        } catch (Exception $e) {
            // TODO: Crear una vista para mostrar los errores
            echo "Error al conectar con MySQL<br>";
            echo "Error: " . $e->getMessage();
            exit;
        }
        
    }

    public static function getConexion(): PDO{
        if (self::$db === null) self::connect();

        return self::$db;
    }
}