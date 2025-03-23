<?php
function conectar(){ //funcion para conectarse a la DB
 $servidor = 'localhost';
 $usuario = 'root';
 $clave = '';
 $nombreBase = 'labo2';
 
 set_error_handler(function(){
    throw new Exception("Error");
 });

 try {
    $conexion = mysqli_connect($servidor,$usuario,$clave,$nombreBase);
 } catch (Exception $e) {
    $conexion = false;
    echo ('<p>Conexión Fallida</p>');
 }

 return $conexion;
}

function desconectar($conexion){ // funcion para desconectarse de la DB
    if ($conexion) {
        mysqli_close($conexion);
    } else {
        echo ('<p>No se puede desconectar ya que no hay una conexión abierta</p>');
    }

}
?>