<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $passwordActual;
    public $passwordNuevo;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {

        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? null;
        $this->passwordActual = $args['passwordActual'] ?? null;
        $this->passwordNuevo = $args['passwordNuevo'] ?? null;
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }
    //!Validar login de usuario
    public function validarLogin(): array
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'No has ingresado un Correo';
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no valido';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'No has ingresado un Password';
        } else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener almenos 6 Caracteres';
        }
        return self::$alertas;
    }

    //!Validación para cuentas nuevas
    public function validarNewCuenta(): array
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'No has ingresado el Nombre de Usuario';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'No has ingresado un Correo';
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no valido';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'No has ingresado un Password';
        } else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener almenos 6 Caracteres';
        } else if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Tus Passwords son diferentes';
        }
        return self::$alertas;
    }

    //!Valida un email
    public function validarEmail(): array
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'No has ingresado un Email';
        }
        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no valido';
        }
        return self::$alertas;
    }
    //!Valida password
    public function validarPassword(): array
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'No has ingresado un Password';
        } else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe contener almenos 6 Caracteres';
        }
        return self::$alertas;
    }
    public function validarPerfil(): array
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'No has ingresado el nombre';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'No has ingresado el email';
        }
        return self::$alertas;
    }

    public  function nuevoPassword(): array
    {
        if (!$this->passwordActual) {
            self::$alertas['error'][] = 'No has ingresado el password actual';
        }
        if (!$this->passwordNuevo) {
            self::$alertas['error'][] = 'No has ingresado el password nuevo';
        } else if (strlen($this->passwordNuevo) < 6) {
            self::$alertas['error'][] = 'El password nuevo debe tener almenos 6 caracteres';
        }
        return self::$alertas;
    }
    //! comprobar password
    public function comprobarPassword(): bool
    {
        return password_verify($this->passwordActual, $this->password);
    }


    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken(): void
    {
        $this->token = uniqid();
    }
}
