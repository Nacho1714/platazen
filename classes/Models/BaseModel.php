<?php

namespace App\Models;

use App\Database\DBConexion;
use PDO;

/**
 * Clase base para los modelos
 */
class BaseModel
{

    /** @var string El nombre de la tabla en la base de datos. */
    protected string $table = "";

    /** @var string El campo de la PK  */
    protected string $primaryKey = "";

    /** @var array Las propiedades que se pueden asignar a la instancia de la clase.*/
    protected array $properties = [];

    /**
     * Asigna las propiedades de la clase a la instancia actual.
     * 
     * @param array $model Un arreglo asociativo con los datos de la clase.
     * @return void
     */
    public function assignProperties(array $model): void
    {
        foreach ($this->properties as $property) {
            if (isset($model[$property])) {
                $this->{$property} = $model[$property];
            }
        }
    }

    /**
     * Obtiene todas las instancias de la clase.
     * 
     * @return array|static[] 
     */
    public function getAll(): array
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM {$this->table}";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $model = $stmt->fetchAll();
        return $model;
    }

    /**
     * Retorna el objeto correspondiente al $id provisto.
     * 
     * @param int $id
     * @return static|null
     */
    public function getById(int $id): ?static
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $model = $stmt->fetch();
        return $model ? $model : null;
    }

    /**
     * Esta función cambia el valor de las claves en el array asociativo original
     * según el mapeo proporcionado en el segundo parámetro.
     *
     * @param array $array El array asociativo original.
     * @param array $keyMapping Un array asociativo que contiene las correspondencias
     * entre las claves y los nuevos valores.
     * @return array El array asociativo modificado con las claves renombradas.
     */
    protected function arrayMapping($array, $keyMapping)
    {
        foreach ($keyMapping as $key => $value) {
            if (isset($array[$key])) {
                $array[$key] = $value;
            }
        }

        return $array;
    }
}
