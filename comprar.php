<?php
// Se incluye la conexión para consultar el producto seleccionado.
require_once "conexion.php";

// Se recibe el identificador enviado mediante GET.
$id_producto = filter_input(INPUT_GET, "producto", FILTER_VALIDATE_INT);

if (!$id_producto) {
    header("Location: productos.html");
    exit;
}

// La consulta preparada evita insertar directamente el dato en el SQL.
$sql = "SELECT id_producto, nombre, descripcion, precio, imagen
        FROM productos
        WHERE id_producto = ? AND activo = 1";

$consulta = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($consulta, "i", $id_producto);
mysqli_stmt_execute($consulta);
$resultado = mysqli_stmt_get_result($consulta);
$producto = mysqli_fetch_assoc($resultado);

if (!$producto) {
    header("Location: productos.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Formulario para registrar una compra en TecnoFácil.">
    <title>TecnoFácil | Registrar compra</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="encabezado">
        <a class="marca" href="index.html"><img src="img/logo-tecnofacil.png" alt="Logotipo de TecnoFácil"></a>
        <button class="boton-menu" type="button" aria-expanded="false" aria-controls="menu-principal">Menú</button>
        <nav id="menu-principal" class="menu" aria-label="Navegación principal">
            <a href="index.html">Inicio</a>
            <a href="tutorial.html">Tutorial</a>
            <a class="activo" href="productos.html">Productos</a>
            <a href="documentacion.html">Componentes</a>
            <a href="listar_compras.php">Compras</a>
        </nav>
    </header>

    <main class="seccion pagina-compra">
        <header class="cabecera-pagina">
            <p class="etiqueta">Compra directa</p>
            <h1>Registrar compra</h1>
            <p>Complete la información y revise el total antes de confirmar.</p>
        </header>

        <div class="distribucion-compra">
            <article class="resumen-producto">
                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <p class="categoria">Producto seleccionado</p>
                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <p class="precio">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
                <a class="enlace-texto" href="productos.html">← Cambiar producto</a>
            </article>

            <form id="formulario-compra" class="formulario" action="guardar_compra.php" method="POST">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                <input type="hidden" id="precio" value="<?php echo $producto['precio']; ?>">

                <div class="campo campo-completo">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" minlength="3" maxlength="100" required>
                </div>

                <div class="campo">
                    <label for="documento">Documento</label>
                    <input type="text" id="documento" name="documento" inputmode="numeric" pattern="[0-9]{6,15}" maxlength="15" required>
                    <small>Escriba entre 6 y 15 números.</small>
                </div>

                <div class="campo">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" inputmode="tel" pattern="[0-9]{7,15}" maxlength="15" required>
                </div>

                <div class="campo campo-completo">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" maxlength="100" required>
                </div>

                <div class="campo">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" max="10" value="1" required>
                </div>

                <div class="campo">
                    <label for="total-mostrado">Total</label>
                    <input type="text" id="total-mostrado" value="$<?php echo number_format($producto['precio'], 0, ',', '.'); ?>" readonly>
                    <small>El servidor calculará nuevamente este valor.</small>
                </div>

                <label class="aceptacion campo-completo">
                    <input type="checkbox" name="acepta" value="1" required>
                    Confirmo que los datos registrados son correctos.
                </label>

                <div id="mensaje-validacion" class="mensaje-validacion campo-completo" aria-live="polite"></div>

                <button class="boton primario campo-completo" type="submit">Registrar compra</button>
            </form>
        </div>
    </main>

    <footer class="pie-pagina">
        <p><strong>TecnoFácil</strong> · Formulario académico</p>
        <p>No se procesan pagos reales.</p>
    </footer>
    <script src="js/funciones.js"></script>
</body>
</html>
