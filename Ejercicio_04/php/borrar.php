<?php

/* --------------------------- EXISTENCIA DE USUARIO EN SESIÓN Y MUESTRA DE DATOS EN HEADER A TRAVÉS DE UNA FUNCIÓN -------------------------------- */
   session_start();
   $ruta = '../'; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../img/usuarios/'; // como parámetro de la función
   require 'header.php'; // llamo a header con mi funcion de mostrar

require_once '../../Ejercicio_02/conexion.php';
$conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB

   /* ------------------------------ BORRO LA FILA SELECCIONADA DE LA BD ------------------------------ */

if ($conexion && !empty($_GET['id'])) {

    $id = (int)$_GET['id']; // hago un casteo a la variable y la declaro al principio para usarla dentro del WHERE de mis proximas consultas

    $consulta = 'SELECT foto FROM articulo WHERE id_articulo = ?'; // borro la imagen almacenada (si es que hay) en img/articulos mediante la obtencion de su nombre a traves de la conexion con la BD
    $sentencia = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($sentencia, 'i', $id);
    mysqli_stmt_execute($sentencia);
    mysqli_stmt_bind_result($sentencia, $fotoActual);
    mysqli_stmt_fetch($sentencia);
    mysqli_stmt_close($sentencia);

    if ($fotoActual !== '') {
     $ubicacionArchivo = "../img/articulos/" . $fotoActual;
     if (file_exists($ubicacionArchivo)) {
          $borrar = unlink($ubicacionArchivo);  
        } 
    } 

    $consulta = 'DELETE FROM articulo WHERE id_articulo = ?';
    
    $sentencia = mysqli_prepare($conexion,$consulta);
    /* $? = */mysqli_stmt_bind_param($sentencia,'i',$id);
    $ex = mysqli_stmt_execute($sentencia);
    mysqli_stmt_close($sentencia);
    
    if ($ex || $borrar) { // se verifican también los casos donde se borra una imagen 
        header('refresh:3; url=articulo_listado.php');
        echo('
        <section class="bg-light bg-opacity-75 rounded container p-3 mb-5">
            <h2 class="p-2 fw-bold">Eliminación exitosa</h2>
            <p class="p-3 text-center fs-4"> Redirigiendo a la página principal en 3 segundos.</p>
        </section>');
    } else {
        //eader('refresh:3; url=articulo_listado.php?usu=' . $usu);
        echo($borrar . '
        <section class="bg-light bg-opacity-75 rounded container p-3 mb-5">
            <h2 class="p-2 fw-bold">Eliminación Fallida</h2>
            <p class="p-3 text-center fs-4">No se pudo eliminar, reinténtalo. Redirigiendo a la página principal en 3 segundos.</p>
        </section>');
    }
} else {
 echo('<p>No se pudo eliminar, reinténtalo.</p>');
}

$desconectar = desconectar($conexion);

require '../../00-enlaces/php/pie.php';
?>

