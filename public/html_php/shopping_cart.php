<?php

define("PATH_XML", "../../config/xml/configuracion_db.xml");
define("PATH_XSD", "../../config/xml/configuracion_db_schema.xsd");

require_once "../../config/Singleton_db_sesion.php";
require_once "../../src/functions/validate_user.php";
require_once "../../src/functions/get_shopping_cart_code.php";

//comprueba que el id empresa de la sesión y las cookies de sesión estén activas y sean correctas
session_start();
if (!validate_user(PATH_XML, PATH_XSD) || !isset($_SESSION["id_empresa"])) {
    header("Location: ../../index.php");
}

//trae los productos asociados al carrito
try {
    $db = Connection_db::get_conexion(PATH_XML, PATH_XSD);
    $cart_code = get_shopping_cart_code(PATH_XML, PATH_XSD);
    $prepare = $db->prepare("
        SELECT t1.codigo_carrito, t1.codigo_producto, t1.cantidad_producto, t2.nombre_producto, t2.descripcion_producto, t2.stock_producto, t2.precio_producto, t2.imagen_producto
        FROM t_productos_pedidos t1
        LEFT JOIN
        t_productos t2
        ON t1.codigo_producto = t2.codigo_producto
        WHERE
        t1.codigo_carrito = :cart_code
    ");
    $prepare->bindParam("cart_code", $cart_code);
    $result = $prepare->execute();
    if ($result) {
        $prepare->bindColumn(1, $codigo_carrito);
        $prepare->bindColumn(2, $codigo_producto);
        $prepare->bindColumn(3, $cantidad_producto);
        $prepare->bindColumn(4, $nombre_producto);
        $prepare->bindColumn(5, $descripcion_producto);
        $prepare->bindColumn(6, $stock_producto);
        $prepare->bindColumn(7, $precio_producto);
        $prepare->bindColumn(8, $imagen_producto);
    }
} catch (PDOException $exc) {
    echo "Ha habido un error con la base de datos";
    echo $exc->getMessage();
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/shopping_cart.css">
    <title>shopping cart</title>
</head>
<body>
    <nav>
        <a href="./dashboard.php"><img class="logo" src="../resources/logo.png" alt="Logo" width="160px" height="50px"></a>
        <a href="./orders.html">Pedidos</a>
        <a href="./shopping_cart.php">Carrito</a>
        <a href="./logout.php">Cerrar Sesión</a>
    </nav>
    <main>
        <div class="content">
            <?php
            //muestra todos los productos asociados al carrito
            while ($prepare->fetch(PDO::FETCH_BOUND)) {
                if ($cantidad_producto > 0) {
                    echo'<div class="card">';
                        echo '<p><strong>' . $nombre_producto . '</strong></p>';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($imagen_producto) . '" alt="imagen producto" height="100px" width="100px"><br>';
                        echo '<p>' . $descripcion_producto . '</p>';
                        echo '<p>cantidad: ' . $cantidad_producto . ' unidades</p>';
                        echo '<p>precio total: ' . number_format($precio_producto * $cantidad_producto, 2) . '€<br>(' . $precio_producto . '€ la unidad)</p>';
                        //formulario para modificar unidades
                        echo '<form action="../../src/posts/update_cart_item_quantity.php" method="post">';
                            echo '<label for="cantidad">Cantidad:</label> ';
                            echo '<input type="hidden" name="codigo_producto" value="' . $codigo_producto . '">'; //codigo de producto
                            echo '<input type="number" name="cantidad_producto" value="' . $cantidad_producto . '" min="0" max="' . ($cantidad_producto + $stock_producto) . '"><br>'; //numero de artículos
                            echo '<input type="submit" value="modificar">';
                        echo '</form>';
                    echo'</div>';
                }
            }
            ?>
        </div>
    </main>
    <!-- Tramita el pedido -->
    <div class="send-order">
        <form action="../../src/posts/send_cart.php" method="post">
            <input type="hidden" name="codigo_carrito" value="<?php echo $codigo_carrito;?>"> 
            <input type="submit" value="tramitar">
        </form>
    </div>