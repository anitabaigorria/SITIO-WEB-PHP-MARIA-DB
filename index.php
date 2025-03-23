<?php
    session_start();
	if (!empty($SESSION['username']) && !empty($SESSION['username'])){
		session_destroy();
	} // cierro sesion anterior y vuelvo a mi inicio de sesion

 /* PÁGINA DE LOGUEO DEL SITIO */
    $ruta = '00-enlaces/';
    require '00-enlaces/php/encabezado.php'; // llamo al encabezado para usuarios no registrados
?>
<main class="d-flex justify-content-center align-items-center login">
        <section class="text-center">
            <header>
                <h1 class="mb-4">Iniciar Sesión</h1>
            </header>

            <form action="Ejercicio_03/logueo.php" method="POST" class="p-4 rounded">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" id="username" name="username" class="form-control mb-3" required>

                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control mb-4" required>

                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>

            <footer class="mt-3 text-light">
                <p>¿Olvidaste tu contraseña? <a href="#">Recupérala aquí</a></p>
            </footer>
        </section>
    </main>
<?php
    require("00-enlaces/php/pie.php");
?>