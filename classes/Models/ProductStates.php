<?php

namespace App\Models;

class ProductStates extends BaseModel
{

    protected int $idProductState;
    protected string $name;

    protected string  $table = "product_states";
    protected string $primaryKey = "idProductState";

    protected array $properties = [
        'idProductState',
        'name',
    ];

    /**
     * Get the value of idProductState
     */
    public function getIdProductState()
    {
        return $this->idProductState;
    }

    /**
     * Set the value of idProductState
     *
     * @return  self
     */
    public function setIdProductState($idProductState)
    {
        $this->idProductState = $idProductState;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
