<div class="contenedor olvide">
    <?php
    include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar Acceso a UpTask</p>
        <?php
        include_once __DIR__ . '/../templates/alertas.php';
        ?>
        <form action="/olvide" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email"  placeholder="Tu email" name="email" value="<?php echo $usuario->email; ?>">
            </div>
            <input type="submit" href="/" class="boton" value="Enviar Instrucciones">
        </form>
        <div class="acciones">
            <a href="/"> ¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
    </div><!-- contenedor-sm -->

</div>