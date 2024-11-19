<?php

define("PATH_XML", "../../config/xml/configuracion_db.xml");
define("PATH_XSD", "../../config/xml/configuracion_db_schema.xsd");

require_once "../../config/Singleton_db_sesion.php";
require_once "../../src/validate_user.php";

//comprueba que el id empresa de la sesión y las cookies de sesión estén activas y sean correctas
session_start();
if (!validate_user(PATH_XML, PATH_XSD) || !isset($_SESSION["id_empresa"])) {
    header("Location: ../../index.php");
}



//query a usar

/*
SELECT t1.codigo_carrito, t1.codigo_producto, t1.cantidad_producto, t2.nombre_producto, t2.descripcion_producto, t2.stock_producto, t2.precio_producto
FROM t_productos_pedidos t1
LEFT JOIN
t_productos t2
ON t1.codigo_producto = t2.codigo_producto
*/


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>shopping cart</title>
</head>
<body>
    <nav>
        <a href="./dashboard.php"><img class="logo" src="../resources/logo.png" alt="Logo" width="160px" height="50px"></a>
        <a href="./orders.html">Pedidos</a>
        <a href="./shopping_cart.php">Carrito</a>
        <a href="./logout.php">Cerrar Sesión</a>
    </nav>