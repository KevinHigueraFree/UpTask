<?php
include_once __DIR__ . '/header-dashboard.php';
?>
<?php if (count($proyectos) === 0) { ?>
    <p class="no-proyectos">No hay Proyectos aún
        <a href="/crear-proyecto">Crear Proyecto</a>
    </p>
<?php } else { ?>
    <ul class="listado-proyectos" id="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) { ?>
            <li class="proyectos">
                <a href="/proyecto?id=<?php echo $proyecto->url ?>">
                    <?php echo $proyecto->proyecto; ?>
                </a>
               
                <form action="/eliminar-proyecto" class="eliminar-proyecto" method="POST">
                    <input type="hidden" name="id" value="<?php echo $proyecto->id ?>">
                    <input type="submit" name="borrar" class="boton-eliminar boton-eliminar-proyecto" value=" × ">
                </form>
            </li>
        <?php } ?>
    </ul>
<?php }  ?>



<?php
include_once __DIR__ . '/footer-dashboard.php';
?>