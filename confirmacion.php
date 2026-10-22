<?php
require_once "conexion.php";

$id_compra = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id_compra) {
    header("Location: productos.html");
    exit;
}

$sql = "SELECT c.id_compra, c.nombre_comprador, c.documento, c.correo,
               c.telefono, c.cantidad, c.total, c.fecha_compra, p.nombre AS producto
        FROM compras c
        INNER JOIN productos p ON c.id_producto = p.id_producto
        WHERE c.id_compra = ?";

$consulta = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($consulta, "i", $id_compra);
mysqli_stmt_execute($consulta);
$resultado = mysqli_stmt_get_result($consulta);
$compra = mysqli_fetch_assoc($resultado);

if (!$compra) {
    die("No se encontró la compra solicitada.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoFácil | Compra registrada</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="encabezado">
        <a class="marca" href="index.html"><img src="img/logo-tecnofacil.png" alt="Logotipo de TecnoFácil"></a>
        <button class="boton-menu" type="button" aria-expanded="false" aria-controls="menu-principal">Menú</button>
        <nav id="menu-principal" class="menu" aria-label="Navegación principal">
            <a href="index.html">Inicio</a>
            <a href="tutorial.html">Tutorial</a>
            <a href="productos.html">Productos</a>
            <a href="documentacion.html">Componentes</a>
            <a href="listar_compras.php">Compras</a>
        </nav>
    </header>

    <main class="seccion confirmacion">
        <div class="icono-exito" aria-hidden="true">✓</div>
        <p class="etiqueta">Registro número <?php echo $compra['id_compra']; ?></p>
        <h1>Compra registrada correctamente</h1>
        <p>La información fue almacenada en la base de datos.</p>

        <dl class="resumen-compra">
            <div><dt>Comprador</dt><dd><?php echo htmlspecialchars($compra['nombre_comprador']); ?></dd></div>
            <div><dt>Producto</dt><dd><?php echo htmlspecialchars($compra['producto']); ?></dd></div>
            <div><dt>Cantidad</dt><dd><?php echo $compra['cantidad']; ?></dd></div>
            <div><dt>Total</dt><dd>$<?php echo number_format($compra['total'], 0, ',', '.'); ?></dd></div>
            <div><dt>Fecha</dt><dd><?php echo htmlspecialchars($compra['fecha_compra']); ?></dd></div>
        </dl>

        <div class="grupo-botones centrado">
            <a class="boton primario" href="productos.html">Realizar otra compra</a>
            <a class="boton secundario" href="listar_compras.php">Ver compras registradas</a>
        </div>
    </main>

    <footer class="pie-pagina"><p><strong>TecnoFácil</strong> · Proyecto educativo</p></footer>
    <script src="js/funciones.js"></script>
</body>
</html>
