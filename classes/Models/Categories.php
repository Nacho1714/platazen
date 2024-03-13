<?php

namespace App\Models;

class Categories extends BaseModel
{

    protected int $idCategory;
    protected string $name;
    protected string $description;
    protected string $image;
    protected string $imageDescription;

    protected string  $table = "categories";
    protected string $primaryKey = "idCategory";

    protected array $properties = [
        'idCategory',
        'name',
        'description',
        'image',
        'imageDescription',
    ];

    // Setters and Getters

    /**
     * Get the value of idCategory
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * Set the value of idCategory
     *
     * @return  self
     */
    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;

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

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of imageDescription
     */
    public function getImageDescription()
    {
        return $this->imageDescription;
    }

    /**
     * Set the value of imageDescription
     *
     * @return  self
     */
    public function setImageDescription($imageDescription)
    {
        $this->imageDescription = $imageDescription;

        return $this;
    }
}
