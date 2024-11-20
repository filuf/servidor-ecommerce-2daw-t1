<?php

define("PATH_XML", "../../config/xml/configuracion_db.xml");
define("PATH_XSD", "../../config/xml/configuracion_db_schema.xsd");

require_once "../../config/Singleton_db_sesion.php";
require_once "../functions/get_shopping_cart_code.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    try {
        $db = Connection_db::get_conexion(PATH_XML, PATH_XSD);
        $cart_code = get_shopping_cart_code(PATH_XML, PATH_XSD);
        $prepare = $db->prepare("
        UPDATE `t_productos_pedidos`
        SET `cantidad_producto` = :cantidad_producto
        WHERE `codigo_carrito` = :codigo_carrito AND `codigo_producto` = :codigo_producto;
        ");
        $prepare->bindParam("cantidad_producto", $_POST["cantidad_producto"]);
        $prepare->bindParam("codigo_carrito", $cart_code);
        $prepare->bindParam("codigo_producto", $_POST["codigo_producto"]);
        $prepare->execute();
        header("Location: ../../public/html_php/shopping_cart.php");
    } catch (PDOException $exc) {
        echo "Ha habido un error al procesar la actualización de cantidad<br>";
        echo $exc->getMessage();
    }
}
