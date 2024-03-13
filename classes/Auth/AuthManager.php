<?php

namespace App\Auth;

use App\Models\User;
use App\Models\BaseModel;

class AuthManager extends BaseModel
{

    /**
     * Verifica las credenciales del usuario
     * 
     * @param array $data: email y password
     * @return bool
     */
    public function login(array $data): bool
    {

        $email = $data['email'];
        $userPassword = $data['password'];

        $user = (new User)->getByEmail($email);
        
        if (!$user) return false;
        
        $dbPassword = $user->getPassword();

        if (!password_verify($userPassword, $dbPassword)) return false;
        
        $this->userAuth($user);

        return true;
    }

    /**
     * Cierra la sesión del usuario
     * 
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['authIdUser'], $_SESSION['authIdRol']);
    }

    /**
     * Autentica la sesión del usuario
     * 
     * @param User $user
     * @return void
     */
    public function userAuth(User $user): void
    {
        $_SESSION['authIdUser'] = $user->getIdUser();
        $_SESSION['authIdRol'] = $user->getFkRol();
    }

    /**
     * Verifica si el usuario está autenticado
     * 
     * @return bool
     */
    public function checkAuth(): bool
    {
        return isset($_SESSION['authIdUser']);
    }

    /**
     * Verifica si el usuario es administrador
     * 
     * @return bool
     */
    public function checkAdmin(): bool
    {
        return $_SESSION['authIdRol'] === 1;
    }

    /**
     * Redirecciona al usuario a la página de inicio de sesión si no está autenticado.
     * 
     * @return void
     */
    public function authFailureRedirect(): void
    {
        if (!$this->checkAuth() || !$this->checkAdmin()) {
            $_SESSION['errors'] = 'Acceso denegado, por favor inicie sesión';
            header('Location: ../index.php?s=log-in');
            exit;
        }
    }

    /**
     * Devuelve el id del usuario autenticado
     * 
     * @return int|null
     */
    public function getIdUserAuth(): ?int
    {
        return $this->checkAuth() ? $_SESSION['authIdUser'] : null;
    }

    /**
     * Devuelve el usuario autenticado
     * 
     * @return User|null
     */
    public function getUserByAuth(): ?User
    {
        return $this->checkAuth() ? (new User)->getById($_SESSION['authIdUser']) : null;
    }
}