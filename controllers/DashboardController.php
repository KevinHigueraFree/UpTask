<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Usuario;

class DashboardController
{
    public static function index(Router $router)
    {
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);


        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos

        ]);
    }
    public static function eliminar_proyecto(){
        session_start();
        isAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //debuguear($_POST);// sabemos lo que mandamos a la pagina
            $id = $_POST['id'];
            $proyecto = Proyecto::find($id);
           // debuguear($proyecto);
            $proyecto->eliminar();
            header('Location: /dashboard');
        }
    }
    public static function crear_proyecto(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if (empty($alertas)) {
                //!Generar una URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //! Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id']; //se toma el id del usuario que esta en session activa

                //! Guardar el proyecto
                $proyecto->guardar();


                //!Redireccionar

                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas

        ]);
    }

    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();
        $token = $_GET['id'];
        if (!$token) header('Location: /dashboard');
        //!revisar que la persona que visita el proyecto es el creador
        $proyecto = Proyecto::where('url', $token);
        if ($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $alertas = [];
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
            'alertas' => $alertas

        ]);
    }
    public static function perfil(Router $router)
    {
        session_start();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();
            //debuguear($usuario);
            if (empty($alertas)) {
                //! verificar que usuario no existe
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //!mostrar mensaje de error si existe
                    Usuario::setAlerta('error', 'Este email ya ha sido registrado');
                    $alertas = $usuario->getAlertas();
                } else {
                    //!guardar usuario
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Guardado  Correctamente');
                    $alertas = $usuario->getAlertas();
                    $_SESSION['nombre'] = $usuario->nombre; //actualizamos el nombre usuario de la sesion
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario

        ]);
    }
    public static function cambiar_password(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            // sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevoPassword();

            if (empty($alertas)) {
                $resultado = $usuario->comprobarPassword();
                if ($resultado) {
                    //! asignar nuevo password
                    $usuario->password = $usuario->passwordNuevo; //cambiamos el password
                    //! Eliminar propiedades no necesarias
                    unset($usuario->passwordActual);
                    unset($usuario->passwordNuevo);

                    //!hashear password
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Tu password se ha actualizado');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Password actual incorrecto');
                    $alertas = $usuario->getAlertas();
                }
                //debuguear($resultado);
            }
        }
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar password',
            'alertas' => $alertas
        ]);
    }
}
