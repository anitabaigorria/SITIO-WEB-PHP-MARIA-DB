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
        
         echo('
          <h2 class="p-3 fw-bold">Está por eliminar a:</h2>
          <table class="table table-bordered table-hover table-striped w-auto mx-auto">
          <tr>
          <th class="bg-secondary text-white">Foto</th>
          <th class="bg-secondary text-white">Producto</th>
          <th class="bg-secondary text-white">Categoria</th>
          <th class="bg-secondary text-white">Precio</th>
          </tr>');
 
         if (mysqli_stmt_fetch($sentencia)) { // como solo tenemos que recorrer 1 tupla (1 solo valor para cada variable), utilizamos if

            if ($foto == '' || $foto == 'NULL') {
                $foto = 'sin_imagen.png';
            }

         echo('
          <tr>
           <td class="bg-secondary text-white"><img src="../img/articulos/' . $foto . '"</td>
           <td class="bg-secondary text-white">' . $nombre . '</td>
           <td class="bg-secondary text-white">' . $categoria . '</td>
           <td class="bg-secondary text-white"> $' . number_format($precio,2,',','.') . '</td>
           </tr>
          </table>
           
          <section class="bg-light bg-opacity-75 rounded container p-3 mb-5">
            <h2 class="p-2 fw-bold">Eliminar Artículo</h2>
            <p class="p-3 text-center fs-4">¿Está seguro que quiere eliminar a <strong> '. $nombre . '</strong>?</p>
            <article class="d-flex flex-row justify-content-around pt-3 fs-3">
                <a href="borrar.php?id=' . $id . '" class="btn btn-primary me-2">Aceptar</a>
                <a href="articulo_listado.php" class="btn btn-secondary">Cancelar</a>
            </article>
          </section>
          ');
          }
    }
    $desconectar = desconectar($conexion);
}

require '../../00-enlaces/php/pie.php';
?>

