<?php
require_once "conexion.php";

$sql = "SELECT c.id_compra, c.nombre_comprador, c.documento,
               p.nombre AS producto, c.cantidad, c.total, c.fecha_compra
        FROM compras c
        INNER JOIN productos p ON c.id_producto = p.id_producto
        ORDER BY c.id_compra DESC";

$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoFácil | Compras registradas</title>
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
            <a class="activo" href="listar_compras.php">Compras</a>
        </nav>
    </header>

    <main class="seccion">
        <header class="cabecera-pagina">
            <p class="etiqueta">Base de datos</p>
            <h1>Compras registradas</h1>
            <p>Esta página demuestra la consulta de información mediante PHP y MySQL.</p>
        </header>

        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <div class="contenedor-tabla">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Comprador</th>
                            <th>Documento</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($compra = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?php echo $compra['id_compra']; ?></td>
                                <td><?php echo htmlspecialchars($compra['nombre_comprador']); ?></td>
                                <td><?php echo htmlspecialchars($compra['documento']); ?></td>
                                <td><?php echo htmlspecialchars($compra['producto']); ?></td>
                                <td><?php echo $compra['cantidad']; ?></td>
                                <td>$<?php echo number_format($compra['total'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($compra['fecha_compra']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="estado-vacio">
                <h2>Todavía no hay compras</h2>
                <p>Seleccione un producto y registre la primera operación.</p>
                <a class="boton primario" href="productos.html">Ir al catálogo</a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="pie-pagina"><p><strong>TecnoFácil</strong> · Consulta PHP y MySQL</p></footer>
    <script src="js/funciones.js"></script>
</body>
</html>
