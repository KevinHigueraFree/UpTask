<div class="contenedor reestablecer">
    <?php
    include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa Nuevo Password</p>
        <?php
        include_once __DIR__ . '/../templates/alertas.php';
        if ($mostrar){
        ?>
        <form method="POST" class="formulario">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu password" name="password">
            </div>

            <input type="submit" class="boton" value="Guardar Password">
        </form>
        <div class="acciones">
            <a href="/"> ¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
        <?php } ?>
    </div><!-- contenedor-sm -->

</div>