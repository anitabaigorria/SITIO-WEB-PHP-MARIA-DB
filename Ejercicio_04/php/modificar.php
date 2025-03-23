<?php
/* --------------------------- EXISTENCIA DE USUARIO EN SESIÓN Y MUESTRA DE DATOS EN HEADER A TRAVÉS DE UNA FUNCIÓN -------------------------------- */
   session_start();
   $ruta = '../'; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../img/usuarios/'; // como parámetro de la función
   require 'header.php'; // llamo a header con mi funcion de mostrar

require_once '../../Ejercicio_02/conexion.php';
$conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB
    

if ($conexion && !empty($_GET['id'])) {

    $id = $_GET['id'];
    
    $consulta = 'SELECT nombre,categoria,precio,foto FROM articulo WHERE id_articulo = ?';
    
    $sentencia = mysqli_prepare($conexion,$consulta);
    /* $? = */mysqli_stmt_bind_param($sentencia,'i',$id);
    mysqli_stmt_execute($sentencia);
    mysqli_stmt_bind_result($sentencia,$nombre,$categoria,$precio,$foto);

    mysqli_stmt_store_result($sentencia);
    $cantFilas = mysqli_stmt_num_rows($sentencia); 
                
    if ($cantFilas > 0) {
        mysqli_stmt_fetch($sentencia);
}

?>

<main class="container py-3">
        <section class="d-flex justify-content-center">
            <form class="p-4 border rounded w-50" action="aceptar.php" method="POST" enctype="multipart/form-data">
                <fieldset>
                    <legend class="text-center mb-4">Modificar Artículo</legend>

                    <label for="nombre" class="form-label">Nombre del artículo</label>
                    <input type="text" id="nombre" name="nombre" class="form-control mb-3" value="<?php echo $nombre ?>">

                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" id="categoria" name="categoria" class="form-control mb-3" value="<?php echo $categoria ?>">

                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" id="precio" name="precio" class="form-control mb-3" value="<?php echo $precio ?>">

                    <label for="imagen" class="form-label">Subir imagen del artículo</label>
                    <input type="file" id="imagen" name="imagen" class="form-control mb-4" value="<?php echo $foto ?>">

                    <input type="hidden" name="usu" value="<?php echo $usu; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <a href="aceptar.php" class="fw-bold"><button type="submit" class="mt-4 btn btn-primary w-100">Actualizar</button></a>
                    <a href="../../index.php?usu=<?php echo $usu; ?>" class="fw-bold"><button type="submit" class="mt-4 btn btn-primary w-100">Cancelar</button></a>
                </fieldset>
            </form>
        </section>
    </main>

<?php
}
$desconectar = desconectar($conexion);
    require("../../00-enlaces/php/pie.php");
?>