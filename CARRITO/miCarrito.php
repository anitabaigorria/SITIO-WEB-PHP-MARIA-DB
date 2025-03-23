<?php
/* VER CARRITO */
    session_start();
   $ruta = ''; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../Ejercicio_04/img/usuarios/'; // como parámetro de la función
   require '../Ejercicio_04/php/header.php'; // llamo a header con mi funcion de mostrar
   
   require_once '../Ejercicio_02/conexion.php';
   $conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB

   if (!empty($_SESSION['carrito'])){
     $conexion = conectar();
     $consulta = 'SELECT nombre, categoria, precio, foto FROM articulo WHERE id_articulo = ?';
     $sentencia = mysqli_prepare($conexion, $consulta);
     mysqli_stmt_bind_param($sentencia, 'i', $id);
     mysqli_stmt_bind_result($sentencia, $nombre, $categ, $precio, $foto);
   
     echo ('
	 <section class="d-flex justify-content-center">
     <table class="table table-bordered table-hover table-striped w-auto m-3">
           <caption class="caption-top text-center bg-dark text-white fw-bold fs-4">Mi carrito:</caption>
           <tr>
              <th class="bg-secondary text-white">Producto</th>
              <th class="bg-secondary text-white">Categoria</th>
              <th class="bg-secondary text-white">Precio Unitario</th>
              <th class="bg-secondary text-white">Foto</th>		
              <th class="bg-secondary text-white">Cantidad</th>		
              <th class="bg-secondary text-white">Subtotal</th>		
           </tr>   
     ');

    $suma = 0;
    foreach ($_SESSION['carrito'] as $id => $cant) {
        mysqli_stmt_execute($sentencia);
        mysqli_stmt_fetch($sentencia);
        $carrito = $_SESSION['carrito'];
		
		if ($foto == '') {
          $foto = 'sin_imagen.png';
        }

        echo('
		  <tr>
		      <td class="bg-secondary text-white">' . $nombre . '</td>
              <td class="bg-secondary text-white">' . $categ . '</td>
              <td class="bg-secondary fw-bold text-warning">$AR ' . $precio . '</td>			  
              <td class="bg-secondary text-white"><img class="artic-pic" src="../Ejercicio_04/img/articulos/' . $foto . '"</td>
		      <td class="bg-secondary text-white text-center fs-4 pt-5">' . $cant . '</td>
		      <td class="bg-secondary text-white">$AR ' . number_format($precio * $cant, 2, ",", ".") . '</td>
           </tr>
		');
        $suma += $cant * $precio;
    }

    mysqli_stmt_close($sentencia);
    echo ('
	   </table></section>
	   <section class="m-2 p-3 bg-light bg-opacity-50 rounded d-flex flex-row justify-content-around fw-bold fs-4"
	   <h3 class="text-center p-2">Total: $AR ' . number_format($suma, 2, ",", ".") . '</h3>
	   <a class="btn btn-dark text-decoration-none p-2 fs-5 fw-bold" href="#">Pagar</a>
	   <a class="btn btn-dark text-decoration-none p-2 fs-5 fw-bold" href="../Ejercicio_04/php/articulo_listado.php">Seguir Comprando</a>
	   </section>
   ');
   }
   
    $desconectar = desconectar($conexion);
    require("../00-enlaces/php/pie.php");
?>
