<?php
   session_start();
   $ruta = ''; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../Ejercicio_04/img/usuarios/'; // como parámetro de la función
   require '../Ejercicio_04/php/header.php'; // llamo a header con mi funcion de mostrar

   
   if (!empty($_SESSION['username']) && !empty($_GET['id'])){ // si la variable de sesion existe y el envío del id se realizo correctamente
	   
	   // CONTROLO CON UN ENCADENAMIENTO DE CONDICIONALES CADA SITUACION POSIBLE CON LA INCLUSION DE UN NUEVO ARTICULO AL CARRITO
	   if (empty($_SESSION['carrito'])){ // si es el primer articulo a agregar, primero debemos crear el arreglo que va a contener los elementos correspondientes a cada articulo y su cantidad
		   $_SESSION['carrito'] = array();
	   }
	   if (empty($_SESSION['carrito'][$_GET['id']])){ // seguidamente creamos el elemento y le asignamos una cantidad
		   $_SESSION['carrito'][$_GET['id']] = 1;
	   } else {
		   $_SESSION['carrito'][$_GET['id']]++; // si el elemento ya estaba creado, solo incrementamos la cantidad, para no repetir el mismo producto
	   }
	   
	   echo('
        <section class="bg-light bg-opacity-75 rounded container p-3 mb-5 mt-4">
          <h2 class="text-success p-4 fw-bold text-center bg-success bg-opacity-25 rounded">Artículo agregado al carrito exitosamente</h2>
          <article class="d-flex flex-row justify-content-around m-4">
              <a class="btn btn-dark text-decoration-none fw-bold fs-5" href="miCarrito.php">Ver Carrito</a>
              <a class="btn btn-dark text-decoration-none fw-bold fs-5" href="../Ejercicio_04/php/articulo_listado.php">Seguir Comprando</a>
          </article>
        </section>');
		
   } else {
	   header('refresh:3; url=../Ejercicio_04/php/articulo_listado.php');
        echo('
        <section class="bg-light bg-opacity-75 rounded container p-3 mb-5">
            <h2 class="p-2 fw-bold">Ocurrió un error en la carga del artículo seleccionado, reinténtalo</h2>
            <p class="p-3 text-center fs-4"> Redirigiendo a la página principal en 3 segundos.</p>
        </section>');
   }
   
?>