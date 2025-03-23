<?php
session_start(); 
   
require_once '../Ejercicio_02/conexion.php'; 
$conexion = conectar(); // llamo a mi archivo con funciones de conexión y me conecto a mi DB


if ($conexion && !empty($_POST['username']) && !empty($_POST['password'])) { // si se realiza la conexion y  si los datos provenientes del form de index.php no están vacíos

        $consulta = 'SELECT foto FROM usuario WHERE usuario=? AND pass=?'; // preparo consulta
        $sentencia = mysqli_prepare($conexion, $consulta);
		
        $usuario = $_POST['username']; // guardo en una variable cada dato del form 
        $clave = sha1($_POST['password']);  // encripto la contraseña para hacer la comparación con la almacenada en la DB

        mysqli_stmt_bind_param($sentencia, 'ss', $usuario, $clave); // vinculo variable de entrada (form)

        mysqli_stmt_execute($sentencia); // ejecuto la sentencia
        mysqli_stmt_bind_result($sentencia, $foto); // vinculo los resultados a una variable de salida
		
		mysqli_stmt_store_result($sentencia);
		$cantFilas = mysqli_stmt_num_rows($sentencia);

        if ($cantFilas == 1) {  // si la ejecución es exitosa y la cantidad de filas coincidentes es 1, significa que tengo un registro de ese usuario y me da zu respectiva foto de perfil. En el caso que la ejecución no sea exitosa, significa que no se encontró alguna coincidencia, dirigiendo al bloque de error
            mysqli_stmt_fetch($sentencia);
			header('refresh:0;url=../Ejercicio_04/php/articulo_listado.php');
			$_SESSION['username'] = $usuario;
            $_SESSION['foto'] = $foto; // defino el elemento de la variable superglobal con los datos obtenidos de la DB
        } else { 
            header("refresh:3;url=../index.php");
            $ruta = '../00-enlaces/';
            require_once '../00-enlaces/php/encabezado.php';
            echo('<section class="text-center bg-light bg-opacity-75 rounded m-5">
            <p class="p-3 text-dark fs-4">Usuario y clave incorrectas, revise los datos y reenvíe el formulario. Será redirigido a la página principal en 3 segundos</p>
            </section>');
            require_once '../00-enlaces/php/pie.php';
        }
    
    $desconectar = desconectar($conexion); // me desconecto de mi DB
}

?>