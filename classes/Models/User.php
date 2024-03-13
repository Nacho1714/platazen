<?php

namespace App\Models;

use App\Database\DBConexion;
use PDO;

class User extends BaseModel
{
    protected int $idUser;
    protected int $fkRol;
    protected string $name;
    protected string $surname;
    protected string $email;
    protected string $password;

    protected string  $table = "users";
    protected string $primaryKey = "idUser";

    protected array $properties = [
        'idUser',
        'fkRol', 
        'email',
        'password',
        'name',
        'surname',
    ];

    /**
     * Obtiene un usuario por su email
     * 
     * @param string $email
     * @return self|null
     */
    public function getByEmail(string $email): ?self
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $user = $stmt->fetch();

        return $user ? $user : null;
    }

    public function create(array $data)
    {
        $db = DBConexion::getConexion();
        $query = 
            "INSERT INTO users 
                    (fkRol, email, password) 
            VALUES  (:fkRol, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':fkRol' => $data['fkRol'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
    }

    // Setters and Getters

    /**
     * Get the value of idUser
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

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
     * Get the value of surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     *
     * @return  self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of fkRol
     */ 
    public function getFkRol()
    {
        return $this->fkRol;
    }

    /**
     * Set the value of fkRol
     *
     * @return  self
     */ 
    public function setFkRol($fkRol)
    {
        $this->fkRol = $fkRol;

        return $this;
    }
}
