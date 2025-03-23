<?php
/* --------------------------- EXISTENCIA DE USUARIO EN SESIÓN Y MUESTRA DE DATOS EN HEADER A TRAVÉS DE UNA FUNCIÓN -------------------------------- */
   session_start();
   $ruta = ''; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../Ejercicio_04/img/usuarios/'; // como parámetro de la función
   require '../Ejercicio_04/php/header.php'; // llamo a header con mi funcion de mostrar

   require '../Ejercicio_02/conexion.php';
   $conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB


if (!empty($_POST['nombre']) && !empty($_POST['categoria']) && !empty($_POST['precio'])) { // si los datos provenientes del form de articulo_alta.php no están vacíos

    if (!empty($_FILES['imagen']['size'])) {
    
     // TRATAMIENTO A LA IMAGEN: -insertar en la BD el nombre del archivo (se hace junto al resto de datos del form) -mover el archivo a la carpeta seleccionada para luego poder mostrarla:
     $tipoArchivo = $_FILES['imagen']['type']; // establecemos el tipo de archivo que nos brindaron

     if ($tipoArchivo == 'image/png' || $tipoArchivo == 'image/jpg' || $tipoArchivo == 'image/jpeg' || $tipoArchivo == 'image/webp') { // si coincide con los formatos permitidos, se realiza el movimiento a la carpeta seleccionada (evitamos enviar formatos de archivos no permitidos)
      
      $nombre = $_FILES['imagen']['name'];
      $ext = explode('.',$nombre); // extraemos la extension para colocarle al nombre del archivo

      $nuevoNombre = $_POST['nombre'] . '.' . $ext[1]; // establecemos como nombre el colocado al articulo en el form

      $rutaOrigen = $_FILES['imagen']['tmp_name'];
      $destino = '../Ejercicio_04/img/articulos/' . $nuevoNombre;

      if (move_uploaded_file($rutaOrigen,$destino)) {
        $nombreImagen = $nuevoNombre;
      }
     }
    } else {
      $nombreImagen = '';
    }

    $name = (string)$_POST['nombre'];
    $categ = (string)$_POST['categoria'];
    $precio = (double)$_POST['precio']; // CASTEO

    $consulta = 'INSERT INTO articulo(nombre,categoria,precio,foto) VALUES (?,?,?,?)'; // preparo consulta
    $sentencia = mysqli_prepare($conexion,$consulta);
    mysqli_stmt_bind_param($sentencia,'ssds',$name,$categ,$precio,$nombreImagen); // vinculo variable de entrada (form)

    $ex = mysqli_stmt_execute($sentencia); // ejecuto la sentencia
    
   if ($ex) { // carga de datos sin foto
     header('refresh:4;url=../Ejercicio_04/php/articulo_listado.php');
        echo('<section class="text-center bg-light bg-opacity-75 rounded m-5">
        <p class="p-3 text-dark fs-4">Alta exitosa. Será redirigido a la página principal en 4 segundos</p>
        </section>');

    } else { // error de carga
        require_once '../Ejercicio_04/php/header.php';
        echo('<section class="text-center bg-light bg-opacity-75 rounded m-5">
            <p class="p-3 text-dark fs-4">Error al guardar</p>
            <a class="btn btn-dark" href="articulo_alta.php">REINTENTAR</a>
            </section>');
    }

    $desconectar = desconectar($conexion); // me desconecto de mi DB 
}
require_once '../00-enlaces/php/pie.php';
?>