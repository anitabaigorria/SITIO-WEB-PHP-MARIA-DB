<?php
   /* --------------------------- EXISTENCIA DE USUARIO EN SESIÓN Y MUESTRA DE DATOS EN HEADER A TRAVÉS DE UNA FUNCIÓN -------------------------------- */
   session_start();
   $ruta = ''; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../Ejercicio_04/img/usuarios/'; // como parámetro de la función
   require '../Ejercicio_04/php/header.php'; // llamo a header con mi funcion de mostrar
   
   require_once '../Ejercicio_02/conexion.php';
   $conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB

?>

<main class="container py-3">
        <section class="d-flex justify-content-center">
            <form class="p-4 border rounded w-50" action="articulo_alta_ok.php" method="POST" enctype="multipart/form-data">
                <fieldset>
                    <legend class="text-center mb-4">Alta de Artículo</legend>

                    <label for="nombre" class="form-label">Nombre del artículo</label>
                    <input type="text" id="nombre" name="nombre" class="form-control mb-3" required>

                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" id="categoria" name="categoria" class="form-control mb-3" required>

                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" id="precio" name="precio" class="form-control mb-3" required>

                    <label for="imagen" class="form-label">Subir imagen del artículo</label>
                    <input type="file" id="imagen" name="imagen" class="form-control mb-4">

                    <input type="hidden" name="usu" value="<?php echo $usu; ?>">
                    <button type="submit" class="btn btn-primary w-100">Dar de alta</button>
                </fieldset>
            </form>
        </section>
    </main>

<?php
    $desconectar = desconectar($conexion);
    require("../00-enlaces/php/pie.php");
?>