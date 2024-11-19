<?php

/**
 * Obtiene el código del carrito activo del usuario o crea uno en caso de no tener uno activo
 * 
 */
function get_shopping_cart_code ($PATH_XML, $PATH_XSD) {
    try {
        $db = Connection_db::get_conexion($PATH_XML, $PATH_XSD);

        $result = $db->query('SELECT `codigo_carrito` FROM t_carrito WHERE codigo_empresa = ' . $_SESSION["id_empresa"] . ' AND estado_carrito = "pendiente"');
        $first_row = $result->fetch(PDO::FETCH_ASSOC);
        if ($first_row) { //hay un carrito pendiente asociado a esta cuenta
            $shopping_cart_code = $first_row["codigo_carrito"];
        } else { //no hay un carrito y debemos crearlo como carrito pendiente y con el id de empresa del usuario
            $db->query('INSERT INTO `t_carrito`(`codigo_empresa`, `estado_carrito`) VALUES (' . $_SESSION["id_empresa"] . ', "pendiente")');
            //volvemos a hacer la consulta para esta vez obtener el id del código del carrito
            $result = $db->query('SELECT `codigo_carrito` FROM t_carrito WHERE codigo_empresa = ' . $_SESSION["id_empresa"] . ' AND estado_carrito = "pendiente"');
            $first_row = $result->fetch(PDO::FETCH_ASSOC);
            $shopping_cart_code = $first_row["codigo_carrito"];
        }
        return $shopping_cart_code;
    
    } catch (PDOException $exc) {
        echo "[!] Ha habido un error de conexión en la base de datos<br>";
        echo $exc->getMessage();
    }
}