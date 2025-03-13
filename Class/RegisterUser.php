<?php

/**
 * Class RegisterUser
 * Objetivo: Registrar usuarios en la base de datos
 * @package Class
 * @category Class
 * @version 1.0
 * @since 1.0
 * @link https://www.php.net/manual/es/class.pdo.php
 * @link https://www.php.net/manual/es/pdo.prepare.php
 * @link https://www.php.net/manual/es/pdo.commit.php
 * @link https://www.php.net/manual/es/pdo.rollback.php
 * @link https://www.php.net/manual/es/pdo.lastinsertid.php
 * @author Jeimy Jara
 */
class RegisterUser
{
    private int $id_users;
    private int $id_plataforma;
    private string $nombre;
    private string $correo;
    private string $pais;
    private string $telefono;
    private string $contrasena;
    private string $tienda;
    private int $referido;
    private int $cargo;
    private PDO $pdo;

    /**
     * Constructor para registrar usuarios ya sea por referido o por registro normal
     * @param PDO $pdo
     * @param string $nombre
     * @param string $correo
     * @param string $pais
     * @param string $telefono
     * @param string $contrasena
     * @param string $tienda
     * @param int $referido
     * @param int $cargo
     */
    public function __construct(PDO $pdo, string $nombre, string $correo, string $pais, string $telefono, string $contrasena, string $tienda, int $referido, int $cargo)
    {
        $this->pdo = $pdo;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->pais = $pais;
        $this->telefono = $telefono;
        $this->contrasena = $contrasena;
        $this->tienda = $tienda;
        $this->referido = $referido;
        $this->cargo = $cargo;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function register_user(): void
    {
        try {
            if (strlen($this->pais) != 2) throw new Exception("El pais debe tener 2 caracteres");
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO users (nombre_users, email_users, con_users, usuario_users, date_added, cargo_users) VALUES (:nombre, :correo, :contrasena, :usuario, :date, :cargo)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nombre' => $this->nombre,
                'correo' => $this->correo,
                'contrasena' => password_hash($this->contrasena, PASSWORD_DEFAULT),
                'date' => date('Y-m-d H:i:s'),
                'usuario' => $this->correo,
                'cargo' => $this->cargo
            ]);
            $this->id_users = $this->pdo->lastInsertId();

            $this->register_plataforma(MATRIZ, $this->telefono, $this->tienda, $this->pais, $this->referido);
            $this->register_perfil();
            $this->register_user_platform();
            $this->register_features();
            $this->register_storage();

            $this->pdo->commit();

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $matriz
     * @param $telefono
     * @param $tienda
     * @param $pais
     * @param $referido
     * @return void
     */
    public function register_plataforma($matriz, $telefono, $tienda, $pais, $referido): void
    {
        $sql = "INSERT INTO plataformas (id_matriz, contacto, whatsapp, fecha_actualza, fecha_ingreso, id_plan, url_imporsuit, carpeta_servidor, email, referido, token_referido, refiere, pais) 
                    VALUES (:id_matriz, :contacto, :whatsapp, :fecha_ac, :fecha_in, :id_plan, :url_imp, :carpeta, :email, :referido, :url_referido, :refiere, :pais)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id_matriz' => $matriz,
            'contacto' => $this->nombre,
            'whatsapp' => $telefono,
            'fecha_ac' => date('Y-m-d H:i:s'),
            'fecha_in' => date('Y-m-d H:i:s'),
            'id_plan' => 1,
            'url_imp' => 'https://' . $tienda . '.' . DOMINIO,
            'carpeta' => '/public_html/' . $tienda,
            'email' => $this->correo,
            'referido' => 0,
            'url_referido' => $tienda,
            'refiere' => $referido == 0 ? null : $referido,
            'pais' => $pais
        ]);
        $this->id_plataforma = $this->pdo->lastInsertId();
    }

    /**
     * @return void
     */
    public function register_perfil(): void
    {
        $sql = "INSERT INTO perfil (nombre_empresa, id_plataforma, whatsapp, telefono) VALUES (:nombre, :id_plataforma, :whatsapp, :telefono)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'nombre' => $this->nombre,
            'id_plataforma' => $this->id_plataforma,
            'whatsapp' => $this->telefono,
            'telefono' => $this->telefono
        ]);
    }

    /**
     * @return void
     */
    public function register_user_platform(): void
    {
        $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (:id_users, :id_plataforma)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_users' => $this->id_users,
            'id_plataforma' => $this->id_plataforma
        ]);

    }

    /**
     * @return void
     */
    public function register_features(): void
    {
        $sql = "INSERT INTO caracteristicas_tienda (id_plataforma, id_producto, texto, icon_text, enlace_icon, subtexto_icon, accion) 
                    VALUES (:id_plataforma, 0,'Envío Gratis a todo el País', 'fa-check', '',  'Llegamos a todo el País', 1),
                           (:id_plataforma, 0, 'Pago Contra Entrega', 'fa-lock', NULL, 'Paga cuando recibes el producto', 2),
                           (:id_plataforma, 0, 'Atención al cliente', 'fa-headset', NULL, 'Soporte 100% garantizado', 2)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_plataforma' => $this->id_plataforma
        ]);
    }

    /**
     * @return void
     */
    public function register_storage(): void
    {
        $sql = "INSERT INTO bodega(nombre, id_empresa, responsable, contacto, id_plataforma) VALUES (:nombre, :id_empresa, :responsable, :contacto, :id_plataforma)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $this->tienda,
            'id_empresa' => $this->id_plataforma,
            'responsable' => $this->nombre,
            'contacto' => $this->telefono,
            'id_plataforma' => $this->id_plataforma
        ]);
    }

    /**
     * @param $cargo
     * @return void
     */
    public function session_cookies($cargo): void
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Guardamos en sesión
        $_SESSION["user"] = $this->correo;
        $_SESSION["id_plataforma"] = $this->id_plataforma;
        $_SESSION['login_time'] = time();
        $_SESSION['cargo'] = $cargo;
        $_SESSION['id'] = $this->id_users;
        $_SESSION['tienda'] = $this->tienda;
        $_SESSION['matriz'] = MATRIZ;
        $_SESSION["enlace"] = "https://" . $this->tienda . "." . DOMINIO;
        $_SESSION["session_lifetime"] = 3600;

        // Compartir cookie con subdominio
        setcookie("user", $this->correo, time() + 3600, "/", "." . DOMINIO);
        setcookie("id_plataforma", $this->id_plataforma, time() + 3600, "/", "." . DOMINIO);
        setcookie("login_time", time(), time() + 3600, "/", "." . DOMINIO);
        setcookie("cargo", 1, time() + 3600, "/", "." . DOMINIO);
        setcookie("id", $this->id_users, time() + 3600, "/", "." . DOMINIO);
    }

    /**
     * @return int
     */
    public function getIdUsers(): int
    {
        return $this->id_users;
    }

    /**
     * @return int
     */
    public function getIdPlataforma(): int
    {
        return $this->id_plataforma;
    }

    /**
     * @return array
     */
    public function getUserData(): array
    {
        return [
            'id_users' => $this->id_users,
            'id_plataforma' => $this->id_plataforma,
            'nombre_users' => $this->nombre,
            'email_users' => $this->correo,
            'pais' => $this->pais,
            'telefono' => $this->telefono,
            'contrasena' => $this->contrasena,
            'tienda' => $this->tienda,
            'cargo_users' => $this->cargo
        ];
    }

}