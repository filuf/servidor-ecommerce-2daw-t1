<?php

define("PATH_XML", "../../config/xml/configuracion_db.xml");
define("PATH_XSD", "../../config/xml/configuracion_db_schema.xsd");

require_once "../../config/Singleton_db_sesion.php";
require_once "../functions/get_shopping_cart_code.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start(); //reanudamos la sesión
    add_product_shopping_cart();
}

/**
 * Añade un nuevo producto al pedido almacenado en $POST.
 * Si el producto ya existe en el carrito, actualiza la cantidad sumando la nueva cantidad al elemento.
 * 
 */
function add_product_shopping_cart() {
    try {
        $cart_code = get_shopping_cart_code(PATH_XML, PATH_XSD);
        $db = Connection_db::get_conexion(PATH_XML, PATH_XSD);

        $prepare_select = $db->prepare("SELECT * FROM `t_productos_pedidos` WHERE codigo_carrito = :codigo_carrito AND codigo_producto = :codigo_producto");
        $prepare_select->bindParam("codigo_carrito", $cart_code);
        $prepare_select->bindParam("codigo_producto", $_POST["codigo_producto"]);
        $prepare_select->execute();

        $first_row = $prepare_select->fetch(PDO::FETCH_ASSOC);
        if($first_row) { //el producto existe en el carrito
            //actualizamos la cantidad del producto en el carrito
            $prepare_update = $db->prepare("
            UPDATE `t_productos_pedidos` 
            SET cantidad_producto = cantidad_producto + :cantidad_a_sumar
            WHERE codigo_carrito = :codigo_carrito AND codigo_producto = :codigo_producto
            ");
            $prepare_update->bindParam("cantidad_a_sumar", $_POST["cantidad_producto"]);
            $prepare_update->bindParam("codigo_carrito", $cart_code);
            $prepare_update->bindParam("codigo_producto", $first_row["codigo_producto"]);
            $prepare_update->execute();

        } else { //el producto no existe en el carrito
            //insertamos el producto con x cantidad en el carrito
            $prepare_insert = $db->prepare("INSERT INTO `t_productos_pedidos`(`codigo_carrito`, `codigo_producto`, `cantidad_producto`) VALUES (:codigo_carrito,:codigo_producto,:cantidad_producto)");
            $prepare_insert->bindParam("codigo_carrito", $cart_code);
            $prepare_insert->bindParam("codigo_producto", $_POST["codigo_producto"]);
            $prepare_insert->bindParam("cantidad_producto", $_POST["cantidad_producto"]);
            $prepare_insert->execute();
        }
        header("Location: ../../public/html_php/dashboard.php");
    } catch (PDOException $exc) {
        echo "[!] Ha habido un error de conexión en la base de datos<br>";
        echo $exc->getMessage();
    }
}

