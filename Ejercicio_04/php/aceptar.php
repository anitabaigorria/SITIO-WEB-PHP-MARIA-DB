<?php

/* --------------------------- EXISTENCIA DE USUARIO EN SESIÓN Y MUESTRA DE DATOS EN HEADER A TRAVÉS DE UNA FUNCIÓN -------------------------------- */
   session_start();
   $ruta = '../'; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../img/usuarios/'; // como parámetro de la función
   require 'header.php'; // llamo a header con mi funcion de mostrar

require_once '../../Ejercicio_02/conexion.php';
$conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB


if (!empty($_POST['nombre']) && !empty($_POST['categoria']) && !empty($_POST['precio']) && !empty($_POST['id']) && $conexion) {

    $id = (int)$_POST['id']; 

    if (!empty($_FILES['imagen']['size'])) { // SI SE SUBE UNA NUEVA IMAGEN (independientemente si previamente tenia una imagen o no)
    
        // TRATAMIENTO A LA IMAGEN
        $tipoArchivo = $_FILES['imagen']['type']; // establecemos el tipo de archivo que nos brindaron
   
        if ($tipoArchivo == 'image/png' || $tipoArchivo == 'image/jpg' || $tipoArchivo == 'image/jpeg' || $tipoArchivo == 'image/webp') { // si coincide con los formatos permitidos, se realiza el movimiento a la carpeta seleccionada (evitamos enviar formatos de archivos no permitidos)
         
         $nombre = $_FILES['imagen']['name'];
         $ext = explode('.',$nombre); // extraemos la extension para colocarle al nombre del archivo
   
         $nuevoNombre = $_POST['nombre'] . '.' . $ext[1]; // establecemos como nombre el colocado al articulo en el form
   
         $rutaOrigen = $_FILES['imagen']['tmp_name'];
         $destino = '../img/articulos/' . $nuevoNombre;
   
         if (move_uploaded_file($rutaOrigen,$destino)) {
            $nombreImagen = $nuevoNombre;
         }
        }
    } else { // SI NO SE SUBE UNA NUEVA IMAGEN 
        
        $consulta = 'SELECT foto FROM articulo WHERE id_articulo = ?';
        $sentencia = mysqli_prepare($conexion, $consulta);
        mysqli_stmt_bind_param($sentencia, 'i', $id);
        mysqli_stmt_execute($sentencia);
        mysqli_stmt_bind_result($sentencia, $fotoActual);
        mysqli_stmt_fetch($sentencia);
        mysqli_stmt_close($sentencia);

        if ($fotoActual !== '') { // en el caso de no estar previamente vacío, borramos la imagen
         $ubicacionArchivo = "../img/articulos/" . $fotoActual;
         if (file_exists($ubicacionArchivo)) {
            unlink($ubicacionArchivo); 
            $nombreImagen = ''; 
            }
        } else {
            $nombreImagen = ''; // redefinimos la variable porque es la utilizada en la consulta (por mas que previamente no contenga nada, debemos definirla para no obtener error)
        }

    }

    $name = (string)$_POST['nombre'];
    $categ = (string)$_POST['categoria'];
    $precio = (double)$_POST['precio']; // CASTEO

    $consulta = 'UPDATE articulo SET nombre = ?, categoria = ?, precio = ?, foto = ? WHERE id_articulo = ?';
    $sentencia = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($sentencia, 'ssdsi', $name , $categ , $precio, $nombreImagen, $id); //nombreImagen verifica que el proceso de cada imagen se haya realizado
    
    $exDatos = mysqli_stmt_execute($sentencia);
    mysqli_stmt_close($sentencia);
      
    if ($exDatos) { // carga de datos + foto
       header('refresh:4;url=articulo_listado.php');
           echo('<section class="text-center bg-light bg-opacity-75 rounded m-5">
               <p class="p-3 text-dark fs-4">Actualización de datos e imagen exitosa. Será redirigido a la página principal en 4 segundos</p>
               </section>');
           
       } else { // error de carga
           require_once '../../00-enlaces/php/encabezado.php';
           echo('<section class="text-center bg-light bg-opacity-75 rounded m-5">
               <p class="p-3 text-dark fs-4">Error al guardar</p>
               <a class="btn btn-dark" href="articulo_alta.php">REINTENTAR</a>
               </section>');
       }
   
    $desconectar = desconectar($conexion); // me desconecto de mi DB    
}

require("../../00-enlaces/php/pie.php");

?>