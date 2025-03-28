<?php

/**
 * Class LoginUser
 * Objetivo: Realizar el login de un usuario en la aplicación
 * @package Class
 * @category Class
 * @version 1.0
 * @since 1.0
 * @author Jeimy Jara
 */
class LoginUser
{
    private PDO $pdo;
    private string $email_users;
    private string $con_users;

    private int $id_plataforma;
    private int $cargo_users;
    private int $id_users;
    private string $nombre_tienda;
    private string $nombre_users;
    private bool $validar_config_chat;

    /**
     * @return bool
     */
    public function isValidarConfigChat(): bool
    {
        return $this->validar_config_chat;
    }

    /**
     * @param bool $validar_config_chat
     * @return LoginUser
     */
    public function setValidarConfigChat(bool $validar_config_chat): LoginUser
    {
        $this->validar_config_chat = $validar_config_chat;
        return $this;
    }

    /**
     * LoginUser constructor.
     * @param PDO $pdo
     * @param string $email_users
     * @param string $con_users
     */
    public function __construct(PDO $pdo, string $email_users, string $con_users)
    {
        $this->pdo = $pdo;
        $this->email_users = $email_users;
        $this->con_users = $con_users;
    }

    /**
     * Realiza el login de un usuario en la aplicación
     * @return void
     * @throws Exception
     */
    public function login(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $sql = "SELECT * FROM users WHERE email_users = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$this->email_users]);
        $user = $statement->fetch();
        if (!$user) throw new Exception("Usuario no encontrado");
        if (!password_verify($this->con_users, $user['con_users']) && !password_verify($this->con_users, $user['admin_pass'])) throw new Exception("Contraseña incorrecta");

        $sql_plataforma = "SELECT p.nombre_tienda, p.id_plataforma
                            FROM usuario_plataforma up INNER JOIN plataformas p ON up.id_plataforma = p.id_plataforma WHERE up.id_usuario = ?";
        $statement_plataforma = $this->pdo->prepare($sql_plataforma);
        $statement_plataforma->execute([$user['id_users']]);
        $plataforma = $statement_plataforma->fetch();

        if (!$plataforma) throw new Exception("Usuario no tiene plataforma asignada");

        $this->id_users = $user['id_users'];
        $this->cargo_users = $user['cargo_users'];
        $this->nombre_users = $user['nombre_users'];
        $this->id_plataforma = $plataforma['id_plataforma'];
        $this->nombre_tienda = $plataforma['nombre_tienda'];
        $this->cookies_session();
    }

    /**
     * @return void
     */
    private function cookies_session(): void
    {
        $_SESSION["user"] = $this->nombre_users;
        $_SESSION["id_plataforma"] = $this->id_plataforma;
        $_SESSION['login_time'] = time();
        $_SESSION['cargo'] = $this->cargo_users;
        $_SESSION['id'] =  $this->id_users;
        $_SESSION['tienda'] = $this->nombre_tienda;
        $_SESSION["enlace"] = "https://" . $this->nombre_tienda . "." . DOMINIO;
        $_SESSION['matriz'] = MATRIZ;

        // Compartir cookie con subdominio
        setcookie("user", $this->email_users, time() + $_ENV["JWT_EXPIRE"], "/", "." . DOMINIO);
        setcookie("id_plataforma", $this->id_plataforma, time() + $_ENV["JWT_EXPIRE"], "/", "." . DOMINIO);
        setcookie("login_time", time(), time() + $_ENV["JWT_EXPIRE"], "/", "." . DOMINIO);
        setcookie("cargo", $this->cargo_users, time() + $_ENV["JWT_EXPIRE"], "/", "." . DOMINIO);
        setcookie("id", $this->id_users, time() + $_ENV["JWT_EXPIRE"], "/", "." . DOMINIO);
    }



    /**
     * @return array
     */
    public function getUserData(): array
    {
        return [
            'id_users' => $this->id_users,
            'cargo_users' => $this->cargo_users,
            'nombre_users' => $this->nombre_users,
            'id_plataforma' => $this->id_plataforma,
            'nombre_tienda' => $this->nombre_tienda,
            'email_users' => $this->email_users,
            'validar_config_chat' => $this->validar_config_chat
        ];
    }

    /**
     * @return int
     */
    public function getCargoUsers(): int
    {
        return $this->cargo_users;
    }

    /**
     * @param int $cargo_users
     * @return LoginUser
     */
    public function setCargoUsers(int $cargo_users): LoginUser
    {
        $this->cargo_users = $cargo_users;
        return $this;
    }

    /**
     * @return string
     */
    public function getConUsers(): string
    {
        return $this->con_users;
    }

    /**
     * @param string $con_users
     * @return LoginUser
     */
    public function setConUsers(string $con_users): LoginUser
    {
        $this->con_users = $con_users;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailUsers(): string
    {
        return $this->email_users;
    }

    /**
     * @param string $email_users
     * @return LoginUser
     */
    public function setEmailUsers(string $email_users): LoginUser
    {
        $this->email_users = $email_users;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdPlataforma(): int
    {
        return $this->id_plataforma;
    }

    /**
     * @param int $id_plataforma
     * @return LoginUser
     */
    public function setIdPlataforma(int $id_plataforma): LoginUser
    {
        $this->id_plataforma = $id_plataforma;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUsers(): int
    {
        return $this->id_users;
    }

    /**
     * @param int $id_users
     * @return LoginUser
     */
    public function setIdUsers(int $id_users): LoginUser
    {
        $this->id_users = $id_users;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombreTienda(): string
    {
        return $this->nombre_tienda;
    }

    /**
     * @param string $nombre_tienda
     * @return LoginUser
     */
    public function setNombreTienda(string $nombre_tienda): LoginUser
    {
        $this->nombre_tienda = $nombre_tienda;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombreUsers(): string
    {
        return $this->nombre_users;
    }

    /**
     * @param string $nombre_users
     * @return LoginUser
     */
    public function setNombreUsers(string $nombre_users): LoginUser
    {
        $this->nombre_users = $nombre_users;
        return $this;
    }


}