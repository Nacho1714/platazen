<?php

namespace App\Auth;

use App\Database\DBConexion;
use App\Models\User;
use DateTime;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;

class RecoverPassword
{
    private User $user;
    private string $token;
    private DateTime $expiration;

    /**
     * Crea una nueva instancia de Categories y asigna de la propiedad $user con el usuario que solicitó el cambio de contraseña
     * 
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Genera un token aleatorio de 32 caracteres y una fecha de expiración, para luego, enviar el email
     *
     * @return void
     */
    public function sendRecoveryEmail(): void
    {
        $this->token = $this->generateToken();
        $this->saveToken();
        $this->sendEmail();
    }

    /**
     * Verifica si el token es válido
     *
     * @return boolean
     */
    public function isValidToken(): bool
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM password_recovery WHERE fkUser = :fkUser AND token = :token";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':fkUser' => $this->user->getIdUser(),
            ':token' => $this->token,
        ]);

        $row = $stmt->fetch();

        if (!$row) return false;

        $this->expiration = new DateTime($row['expiration']);

        return true;
    }

    /**
     * Verifica si el token no ha expirado
     *
     * @return boolean
     */
    public function isValidExpiration(): bool
    {
        $currentDate = new DateTime();

        if ($currentDate >= $this->expiration) {
            $this->deleteToken();
            return false;
        }

        return true;
    }

    /**
     * Actualiza la contraseña del usuario y elimina el token de la base de datos
     * 
     * @param string $password
     * @return void
     */
    public function updatePassword(string $password): void
    {
        $db = DBConexion::getConexion();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = :password WHERE idUser = :idUser";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':idUser', $this->user->getIdUser());
        $stmt->execute();

        $this->deleteToken();
    }

    /**
     * Elimina el token de la base de datos
     *
     * @return void
     */
    private function deleteToken(): void
    {
        $db = DBConexion::getConexion();
        $query = "DELETE FROM password_recovery WHERE fkUser = :fkUser";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':fkUser' => $this->user->getIdUser(),
        ]);
    }

    /**
     * Genera un token aleatorio de 32 caracteres
     * 
     * @return string
     */
    private function generateToken(): string
    {
        $token = openssl_random_pseudo_bytes(32);

        return bin2hex($token);
    }

    /**
     * Guarda el token en la base de datos
     *
     * @return void
     */
    private function saveToken(): void
    {
        $this->deleteToken();

        $db = DBConexion::getConexion();
        $query = "INSERT INTO password_recovery (fkUser, token, expiration) VALUES (:fkUser, :token, :expiration)";
        $stmt = $db->prepare($query);

        // Genero la fecha de expiración
        $this->expiration = (new DateTime())->modify('+1 hour');

        $stmt->execute([
            ':fkUser' => $this->user->getIdUser(),
            ':token' => $this->token,
            ':expiration' => $this->expiration->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Devuelve una instancia de PHPMailer configurada para enviar emails
     *
     * @return PHPMailer
     */
    private function getMailInstance(): PHPMailer
    {
        // Pasando true en el constructor, habilito las excepciones
        $phpmailer = new PHPMailer(true);
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '3763ea17fc192f';
        $phpmailer->Password = '94486c57ef5265';
        $phpmailer->CharSet = 'UTF-8';

        return $phpmailer;
    }


    /**
     * Envía el email con el link para restablecer la contraseña
     *
     * @return void
     */
    private function sendEmail(): void
    {
        try {
            $phpmailer = $this->getMailInstance();
            $phpmailer->setFrom('no-reply@plantazen.com' . $_SERVER['HTTP_HOST']);
            $phpmailer->addAddress($this->user->getEmail());
            $phpmailer->isHTML(true);
            $phpmailer->Subject = 'Recuperación de contraseña';
            $phpmailer->Body = $this->getMailBody();
            // TODO: Configurar el altBody
            $phpmailer->send();
        } catch (\Exception $e) {
            $filename = date('YmdHis') . $this->user->getEmail() . "_password-recovery.html";
            file_put_contents(__DIR__ . '/../../emails-fallidos/' . $filename, $this->getMailBody());
            // Re-lanzamos la excepción para que el controlador la capture y muestre el mensaje de error
            throw $e;
        }
    }

    /**
     * Devuelve el contenido del email
     *
     * @return string
     */
    private function getMailBody(): string
    {
        $data = [
            '@@USER@@' => $this->user->getName() . ' ' . $this->user->getSurname(),
            '@@HOST@@' => $_SERVER['HTTP_HOST'],
            '@@LINK@@' => 'http://localhost/davinci/3%C2%B0er%20CUATRIMESTRE/05%20-%20Programaci%C3%B3n%202/FINAL-PROGRAMACION%202/admin/index.php?s=password-update&token=' . $this->token . '&idUser=' . $this->user->getIdUser(),
        ];

        $message = file_get_contents(__DIR__ . '/../../template/emails/password-recovery.html');
        $message = str_replace([
            '@@USER@@',
            '@@HOST@@',
            '@@LINK@@',
        ], [
            $data['@@USER@@'],
            $data['@@HOST@@'],
            $data['@@LINK@@'],
        ], $message);

        return $message;
    }


    // Getters & Setters

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of expiration
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set the value of expiration
     *
     * @return  self
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }
}
