<?php
/* --------------------------- EXISTENCIA DE USUARIO EN SESIÓN Y MUESTRA DE DATOS EN HEADER A TRAVÉS DE UNA FUNCIÓN -------------------------------- */
   session_start();
   $ruta = '../'; // para linkeo en header.php y para bloque else en el archivo de función
   $rutaImagen = '../img/usuarios/'; // como parámetro de la función
   require 'header.php'; // llamo a header con mi funcion de mostrar

   require_once '../../Ejercicio_02/conexion.php';
   $conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB

   $tipoUsuario =  (string)obtenerTipo ($_SESSION['username'],$conexion); // llamo a mi funcion de obtencion de tipo
?>

<main>  
	 <h2 class="text-light bg-dark p-3">Vista de usuario de tipo <?php echo $tipoUsuario ?></h2>
   
        <article class="d-flex flex-row text-center justify-content-around align-items-center bg-dark mb-4 p-3">

     	  <form action="" method="get" class="d-flex flex-row justify-content-start align-items-center">
              <input type="search" id="bu" name="busca" placeholder="buscar..." class="bg-light rounded bg-opacity-50 mr-1 text-dark" required>
		      <input type="submit" value="Buscar!" class="m-2 text-dark btn btn-light rounded">
		  </form>
		
<?php 
       if ($tipoUsuario == "Administrador"){
	   echo (' 
          <section class="menu_tmp pt-3 pb-3">
              <a class="btn btn-light" href="../../Ejercicio_05/articulo_alta.php">+ Alta Articulo</a>
          </section> 
        ');
		} else{
		echo (' 
          <section class="menu_tmp pt-3 pb-3">
              <a class="btn btn-light" href="../../CARRITO/miCarrito.php">Mi Carrito</a>
          </section> 
        ');
		}
		
?>		

      <form action="guardar-configuracion.php" method="get" class="d-flex flex-row justify-content-start align-items-center">
         <select name="categoria" class="form-select mx-2 text-dark bg-light rounded">
             <option value="">Seleccione una categoría</option>
             <option value="Celulares">Celulares</option>
             <option value="Electrodomésticos">Electrodomésticos</option>
             <option value="Televisores">Televisores</option>
             <option value="Ninguno">Ninguno</option>
         </select>
		 
		 <input type="submit" value="Filtrar" class="m-2 text-dark btn btn-light rounded">
	  </form>		  
      </article>
	  
                <table class="table table-bordered table-hover table-striped w-auto mx-auto">
                    <caption class="caption-top text-center bg-dark text-white">Listado de articulos</caption>
                    <tr>
                        <th class="bg-secondary text-white">Foto</th>
                        <th class="bg-secondary text-white">Producto</th>
                        <th class="bg-secondary text-white">Categoria</th>
                        <th class="bg-secondary text-white">Precio</th>
<?php 

       if ($tipoUsuario == "Administrador"){
	   echo (' 
            <th class="bg-secondary text-white">Modificar</th> 
            <th class="bg-secondary text-white">Eliminar</th>
			</tr>
        ');
		} else{
		echo (' 
          <th class="bg-secondary text-white">Comprar</th>
		  </tr>
        ');
		}

/* -------------------------------------- CREACION, MUESTRA DE COOKIE (filtrado), BUSCADOR (entre todos los artículos, teniendo en cuenta la categoría seleccionada) ----------------------------------- 
 -- se utilizó una combinación de if/elseif donde va cambiando la condicion de la consulta a la BD según la existencia de cada variable, y considerando que la variable de sesión exista */

  if (!empty($_SESSION['username'])){
	  $usuario = $_SESSION['username'];
	  
	    if (!empty($_COOKIE[$usuario]) && !empty($_GET['busca'])){ //caso: busqueda por filtro de categoria
	      $nom = '%' . $_GET['busca'] . '%';
          $categ = $_COOKIE[$usuario];
		 
		 echo ('<h2 class="fw-bold p-3">Buscó ' . $_GET['busca'] . ' en la categoría "' . $categ . '"</h2>');
		  
		 $consulta = 'SELECT id_articulo, nombre, categoria, precio, foto FROM articulo WHERE nombre LIKE ? AND categoria = ?';
		 $sentencia = mysqli_prepare($conexion,$consulta);
		 
		 mysqli_stmt_bind_param($sentencia,'ss',$nom,$categ);
		   
		} elseif (!empty($_COOKIE[$usuario])){ //caso: solo filtro de categoria
          $categ = $_COOKIE[$usuario];
		   
		  echo ('
           <h2 class="fw-bold p-3"> Viendo los artículos de la categoría "' . $categ . '" </h2>
           ');
			
		  $consulta = 'SELECT id_articulo, nombre, categoria, precio, foto FROM articulo WHERE categoria = ?';
		  $sentencia = mysqli_prepare($conexion,$consulta);
		  mysqli_stmt_bind_param($sentencia,'s',$categ);
         
	    } elseif (!empty($_GET['busca'])){ //solo busqueda
		  $nom = '%' . $_GET['busca'] . '%';
		  
		  echo ('<h2 class="fw-bold p-3">Buscó ' . $_GET['busca'] . '</h2>');
		  
         $consulta = 'SELECT id_articulo, nombre, categoria, precio, foto FROM articulo WHERE nombre LIKE ?';
		 $sentencia = mysqli_prepare($conexion,$consulta);
		 
		 mysqli_stmt_bind_param($sentencia,'s',$nom);
		 
		 
	    } else {//muestra todos los articulos (nada seleccionado)
		  $consulta = 'SELECT id_articulo,nombre,categoria,precio,foto FROM articulo';
		  $sentencia = mysqli_prepare($conexion,$consulta);
	    }
	  
	  mysqli_stmt_execute($sentencia);
      mysqli_stmt_bind_result($sentencia,$id,$nombre,$categoria,$precio,$foto);
      mysqli_stmt_store_result($sentencia); // almaceno las tuplas de la DB
      $cantFilas = mysqli_stmt_num_rows($sentencia); // funcion que cuenta la cantidad de tuplas obtenidas de la consulta
   
      if ($cantFilas > 0) {
            
          while (mysqli_stmt_fetch($sentencia)) {
             if ($foto == '') {
                 $foto = 'sin_imagen.png';
                }
			
	         echo('
               <tr>
                 <td class="bg-secondary text-white"><img class="artic-pic" src="../img/articulos/' . $foto . '"</td>
                 <td class="bg-secondary text-white">' . $nombre . '</td>
                 <td class="bg-secondary text-white">' . $categoria . '</td>
                  <td class="bg-secondary text-white"> $' . number_format($precio,2,',','.') . '</td>
                ');
		 
			  if ($tipoUsuario == "Administrador"){
	             echo (' 
                     <td class="bg-secondary text-white"><a href="modificar.php?id=' . $id . '"><img src="../img/modificar.png" class="img-fluid d-block mx-auto mt-4 profile-pic"></a></td>
                     <td class="bg-secondary text-white"><a href="confirmar.php?id=' . $id . '"><img src="../img/eliminar.png" class="img-fluid d-block mx-auto mt-4 profile-pic"></a></td>
                     </tr>
				   ');
		         } else{
		          echo (' 
                     <td class="bg-secondary text-white"><a href="../../CARRITO/articulo_carrito.php?id=' . $id . '"><img src="../../CARRITO/img/carrito.png" class="profile-pic img-fluid d-block mx-auto mt-4"></a></td>
                     </tr>
			        ');
		        }
	        }
        } else {
			if ($tipoUsuario == "Administrador"){
		     echo('
               <tr>
                 <td class="bg-secondary text-white text-center" colspan="6">No se encontraron artículos.</td>
			  </tr>
             ');
		    } else{
             echo('
               <tr>
                 <td class="bg-secondary text-white text-center" colspan="5">No se encontraron artículos.</td>
			  </tr>
             ');
		    }
		}
    }
$desconectar = desconectar($conexion);
?>
        </table>
</main>

<?php
    require("../../00-enlaces/php/pie.php");
?>