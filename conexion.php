<?php
// Datos del servidor MySQL/MariaDB de XAMPP.
$servidor = "127.0.0.1";
$usuario = "root";
$contrasena = "";
$base_datos = "tienda_virtual_basica";
$puerto = 3307;

// Crear la conexión.
$conexion = mysqli_connect(
    $servidor,
    $usuario,
    $contrasena,
    $base_datos,
    $puerto
);

// Detener el programa si la conexión falla.
if (!$conexion) {
    die("No fue posible conectarse con la base de datos.");
}

// Permitir tildes, la letra ñ y otros caracteres.
mysqli_set_charset($conexion, "utf8mb4");
