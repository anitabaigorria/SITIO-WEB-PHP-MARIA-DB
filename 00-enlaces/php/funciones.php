<?php
function mostrarFecha(){
    date_default_timezone_set('America/Argentina/Tucuman');
        $fechaActual = date('Y-m-d');
        $fecha = explode('-',$fechaActual);
        
        $mes = [
            '01' => 'Enero', 
            '02' => 'Febrero', 
            '03'=> 'Marzo',
            '04' => 'Abril', 
            '05' => 'Mayo',
            '06' => 'Junio', 
            '07' => 'Julio', 
            '08' => 'Agosto', 
            '09' => 'Septiembre', 
            '10' => 'Octubre',
            '11' => 'Noviembre', 
            '12' => 'Diciembre'
        ];
        
        $retornar =  $fecha[2] . ' de ' . $mes[$fecha[1]] . ' de ' . $fecha[0];

    return $retornar;
}

  /* ------------------------------ EXTRA: CREO UNA FUNCION QUE HAGA LA CONSULTA CON LA BD PARA MOSTRAR LOS DATOS DEL USUARIO LOGUEADO ------------------------------ */

function mostrarUsuario ($usuario,$fotoPerfil,$rutaImagen,$ruta){	
   if (!empty($_SESSION['username'])){
	  
	   if (empty($fotoPerfil)) { // si la línea del campo foto esta vacía, defino la variable con la imagen por defecto
           $fotoPerfil = 'usuario_default.png';
        }
      
       echo('
          <section class="d-flex flex-row justify-content-end align-items-center">
        <article class="d-flex flex-row justify-content-end align-items-center p-3">
            <p class="mt-3 fw-bold fs-3">' . $usuario . '</p>
            <figure class="mb-0 ms-2">
                <img class="img-fluid rounded-circle profile-pic" src="' . $rutaImagen . $fotoPerfil . '" alt="foto de perfil">
            </figure>
        </article>
        <article>
            <button class="btn btn-dark p-2 fw-bold m-4">
                <a href="' . $ruta . '../index.php" class="text-light text-decoration-none fs-5">Cerrar Sesión</a>
            </button>
        </article>
    </section>
      </nav>
     </header>');
   } else {
	   header('refresh:0;url='. $ruta . '../index.php');
   }
} 


function obtenerTipo ($usuario,$conexion){
	 if (!empty($usuario) && $conexion) {
		$consulta = 'SELECT tipo FROM usuario WHERE usuario = ?';
		$sentencia = mysqli_prepare($conexion,$consulta);
		mysqli_stmt_bind_param($sentencia,'s',$usuario);
		
		$ex = mysqli_stmt_execute($sentencia);
		mysqli_stmt_bind_result($sentencia,$tipo);

        if ($ex) {  // si la ejecución es exitosa, significa que tengo un registro del tipo de ese usuario.
           mysqli_stmt_fetch($sentencia);
           $_SESSION['tipo'] = $tipo;
        }
	 }

   return $_SESSION['tipo'];
   mysqli_stmt_close($sentencia);
}


function eliminarCookie($usuario){ //BORRAR
      unset($_COOKIE[$usuario]);
      setcookie($usuario, '', time() - 3600, '/');
    }

?>