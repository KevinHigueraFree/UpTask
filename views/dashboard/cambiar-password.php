<?php
include_once __DIR__ . '/header-dashboard.php';
?>
<div class="contenedor-sm">
    <?php
    include_once __DIR__ . '/../templates/alertas.php';
    ?>
        <a href="/perfil" class="enlace">Volver a Perfil</a>

    <form method="POST" class="formulario" action="/cambiar-password">
        <div class="campo">
            <label for="passwordActual">Password Actual</label>
            <input type="password" id="passwordActual" name="passwordActual" placeholder="Password actual">
        </div>
        <div class="campo">
            <label for="passwordNuevo">Password Nuevo</label>
            <input type="password" id="passwordNuevo" name="passwordNuevo" placeholder="Password nuevo">
        </div>
        <input type="submit" value="Guardar Cambios">
        
    </form>
</div>


<?php
include_once __DIR__ . '/footer-dashboard.php';
?>