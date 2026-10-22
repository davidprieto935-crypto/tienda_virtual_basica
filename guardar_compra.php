<?php
// Este archivo recibe el formulario, valida los datos y guarda la compra.
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: productos.html");
    exit;
}

// Recibir y limpiar los datos enviados.
$id_producto = filter_input(INPUT_POST, "id_producto", FILTER_VALIDATE_INT);
$nombre = trim($_POST["nombre"] ?? "");
$documento = trim($_POST["documento"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT);
$acepta = isset($_POST["acepta"]);

// Validaciones básicas realizadas nuevamente en el servidor.
if (!$id_producto || $nombre === "" || $documento === "" ||
    $telefono === "" || !$cantidad || $cantidad < 1 || $cantidad > 10 || !$acepta) {
    die("Los datos enviados están incompletos o no son válidos.");
}

if (!preg_match('/^[0-9]{6,15}$/', $documento)) {
    die("El documento debe contener entre 6 y 15 números.");
}

if (!preg_match('/^[0-9]{7,15}$/', $telefono)) {
    die("El teléfono debe contener entre 7 y 15 números.");
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("El correo electrónico no tiene un formato válido.");
}

// Consultar el precio real del producto. No se confía en el precio del navegador.
$sql_producto = "SELECT precio FROM productos WHERE id_producto = ? AND activo = 1";
$consulta_producto = mysqli_prepare($conexion, $sql_producto);
mysqli_stmt_bind_param($consulta_producto, "i", $id_producto);
mysqli_stmt_execute($consulta_producto);
$resultado_producto = mysqli_stmt_get_result($consulta_producto);
$producto = mysqli_fetch_assoc($resultado_producto);

if (!$producto) {
    die("El producto seleccionado no existe o no está disponible.");
}

$total = $producto["precio"] * $cantidad;

// Insertar la compra utilizando parámetros preparados.
$sql = "INSERT INTO compras
        (nombre_comprador, documento, correo, telefono, id_producto, cantidad, total)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$consulta = mysqli_prepare($conexion, $sql);
// Cuatro textos (ssss), dos enteros (ii) y un decimal (d).
mysqli_stmt_bind_param($consulta, "ssssiid", $nombre, $documento, $correo, $telefono, $id_producto, $cantidad, $total);

if (!mysqli_stmt_execute($consulta)) {
    die("No fue posible registrar la compra.");
}

$id_compra = mysqli_insert_id($conexion);

header("Location: confirmacion.php?id=" . $id_compra);
exit;
