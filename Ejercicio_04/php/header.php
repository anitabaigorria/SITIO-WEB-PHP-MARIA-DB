<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="<?php echo $ruta; ?>../00-enlaces/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $ruta; ?>../00-enlaces/css/estilo.css">
</head>
<body>
    <header class="shadow-sm bg-dark bg-opacity-50 text-white">
    <nav class="d-flex flex-row justify-content-between align-items-center">
    <a class="navbar-brand fs-4 p-4 fw-bold" href="#"><?php
        require $ruta . '../00-enlaces/php/funciones.php';
        echo $fecha = mostrarFecha() . '</a>';
        	
        echo ('
        ') .  mostrarUsuario($_SESSION['username'],$_SESSION['foto'],$rutaImagen,$ruta); // envío las variables necesarias para utilizar en la función (incluidas las de sesión).;
    ?>