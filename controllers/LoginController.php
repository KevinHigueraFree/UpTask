<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();
            if (empty($alertas)) {
                //!Verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);
                if (!$usuario) {
                    Usuario::setAlerta('error', 'El usuario no existe');
                } else if (!$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no ha sido confirmado');
                } else {
                    //usuario existe
                    if (password_verify($_POST['password'], $usuario->password)) {
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;

                        //! redireccionar
                        header('Location: /dashboard');

                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();

        //!render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }
    public static function logout()
    {
        session_start();
        $_SESSION=[];
        header('Location: /');
    }
    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            // debuguear($usuario);
            $alertas = $usuario->validarNewCuenta();
            //  debuguear($alertas);
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //!ashear password
                    $usuario->hashPassword();

                    //!eliminar passowrd 2
                    unset($usuario->password2);


                    //!generar el token
                    $usuario->generarToken();
                    //debuguear($usuario);
                    //!Crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    //debuguear($email);
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                    //debuguear($usuario);
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email); //mandamos llamar los datos de la base de datos
                if ($usuario && $usuario->confirmado) {
                    //!Generar nuevo token
                    $usuario->generarToken();
                    unset($usuario->password2);
                    //!Actualizar Usuario
                    $usuario->guardar();
                    //!Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->recuperarPassword();
                    //!Imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no ha sido confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Olvide Password',
            'alertas' => $alertas
        ]);
    }
    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostrar = true; //decidir mostrar usuario
        // debuguear($token);
        if (!$token) header('Location: /'); //redireccionamos si no hay token
        //!Identificar usuario con el token
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $mostrar = false;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //!Añadir nuevo password
            $usuario->sincronizar($_POST);
            //!validar password
            $usuario->validarPassword();
            if (empty($alertas)) {
                //! Hashear el nuevo password
                $usuario->hashPassword();
                //!Eliminar el token
                unset($usuario->password2);
                $usuario->token = null;
                //!Guardar el usuario en la BD
                $resultado = $usuario->guardar();
                //! Redireccionar
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        //!Mostrar vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'Creando cuenta UpTask'
        ]);
    }
    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);
        if (!$token) header('Location:  /'); //si alguien intenta entrar a confirmar directamente, lo redireccionamos al inicio

        //!Encontrar al usuario con el token
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token Inválido');
        } else {
            //! Confirmar cuenta
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token = null;
            //! Guardar en la base de datos
            $usuario->guardar();


            Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}
