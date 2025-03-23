<?php
session_start();


if (!empty($_GET['categoria'])){
	 $valor = $_GET['categoria'];
	 $usuario = $_SESSION['username'];
	 $tiempo_expira = time() + 10 * 60;
    
     // Establecer la cookie
     setcookie($usuario, $valor, $tiempo_expira, '/');
	 
	 if ($valor == 'Ninguno'){
		 unset($_COOKIE[$usuario]);
         setcookie($usuario, '', time() - 3600, '/');
		} 
	 
    }
    header('refresh:0; url=articulo_listado.php');
?>