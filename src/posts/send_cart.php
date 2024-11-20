<?php

define("PATH_XML", "../../config/xml/configuracion_db.xml");
define("PATH_XSD", "../../config/xml/configuracion_db_schema.xsd");

require_once "../../config/Singleton_db_sesion.php";
require_once "../functions/get_shopping_cart_code.php";
require_once "../functions/send_mail.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

        session_start(); //reanudamos la sesión
        $cart_code = get_shopping_cart_code(PATH_XML, PATH_XSD);
        $db = Connection_db::get_conexion(PATH_XML, PATH_XSD);
        //consulta para obtener el carrito
        $prepare_cart = $db->prepare("
        SELECT `codigo_carrito`, `codigo_empresa`, `estado_carrito` 
        FROM `t_carrito` 
        WHERE `codigo_carrito` = :codigo_carrito AND `codigo_empresa` = :codigo_empresa
        ");
        $prepare_cart->bindParam("codigo_carrito", $cart_code);
        $prepare_cart->bindParam("codigo_empresa", $_SESSION["id_empresa"]);
        $result = $prepare_cart->execute();

        if (!$result){
            throw new Exception("No se ha podido tramitar el pedido");
        }

        //extraer mail y nombre responsable
        $prepare_mail = $db->prepare("
        SELECT `correo_electronico`, `nombre_completo_responsable` FROM `t_credenciales_empresa` WHERE `id_empresa` = :id_empresa
        ");
        $prepare_mail->bindParam("id_empresa", $_SESSION["id_empresa"]);
        $result = $prepare_mail->execute();

        if (!$result){
            throw new Exception("No se ha podido tramitar el pedido");
        }

        $first_row = $prepare_mail->fetch(PDO::FETCH_ASSOC);

        $responsible_employee = $first_row["nombre_completo_responsable"];
        $mail_employee = $first_row["correo_electronico"];

        //extraer mail del departamento
        $prepare_mail = $db->prepare("
        SELECT `correo_electronico_departamento_pedidos` FROM `t_empresas` WHERE codigo_empresa = :id_empresa
        ");
        $prepare_mail->bindParam("id_empresa", $_SESSION["id_empresa"]);
        $result = $prepare_mail->execute();

        if (!$result){
            throw new Exception("No se ha podido tramitar el pedido");
        }
        $first_row = $prepare_mail->fetch(PDO::FETCH_ASSOC);

        $mail_department = $first_row["correo_electronico_departamento_pedidos"];

        //contenido del carrito
        $prepare_products = $db->prepare("
            SELECT t1.codigo_producto, t1.cantidad_producto, t2.nombre_producto, t2.descripcion_producto, t2.precio_producto, t2.imagen_producto
            FROM t_productos_pedidos t1
            LEFT JOIN
            t_productos t2
            ON t1.codigo_producto = t2.codigo_producto
            WHERE
            t1.codigo_carrito = :cart_code
        ");
        $prepare_products->bindParam("cart_code", $cart_code);
        $result = $prepare_products->execute();

        if (!$result){
            throw new Exception("No se ha podido tramitar el pedido");
        }

        $prepare_products->bindColumn(1, $codigo_producto);
        $prepare_products->bindColumn(2, $cantidad_producto);
        $prepare_products->bindColumn(3, $nombre_producto);
        $prepare_products->bindColumn(4, $descripcion_producto);
        $prepare_products->bindColumn(5, $precio_producto);
        $prepare_products->bindColumn(6, $imagen_producto);
        
        $precio_final = 0;
        $message = "<h1>Lista de productos encargados por " . $responsible_employee . "</h1><ul>";
        while ($prepare_products->fetch(PDO::FETCH_BOUND)) {
            $precio_total = $precio_producto * $cantidad_producto;
            $message .= "<li>";
                $message .= "Código del producto: " . $codigo_producto . "<br>";
                $message .= "Nombre: " . $nombre_producto . "<br>";
                if (!empty($imagen_producto)) {
                    $message .= '<img src="data:image/jpeg;base64,' . base64_encode($imagen_producto) . '" alt="imagen producto" height="100px" width="100px"><br>';
                }
                $message .= "Descripción: " . $descripcion_producto . "<br>";
                $message .= "Cantidad: " . $cantidad_producto . " unidades<br>";
                $message .= "Precio unitario : " . number_format($precio_producto, 2) . "€<br>";
                $message .= "Precio total: " . number_format($precio_total, 2) . "€<br><br>";
            $message .= "</li>";
            $precio_final += $precio_total;
        }
        $message .= "</ul>";
        $message .= "<p>Precio final: " . $precio_final . "€</p>";
        $message .= "<p>Código de carrito: " . $cart_code . "</p>";

        //envía mails al comprador y al departamento
        send_mail($message, $mail_department);
        send_mail($message, $mail_employee);

        /*
            UPDATE DEL ESTADO CARRITO
        */
        $prepare_update = $db->prepare("
        UPDATE `t_carrito` SET `estado_carrito`='enviado' WHERE `codigo_carrito` = :codigo_carrito AND `codigo_empresa` = :codigo_empresa
        ");
        $prepare_update->bindParam("codigo_carrito", $cart_code);
        $prepare_update->bindParam("codigo_empresa", $_SESSION["id_empresa"]);

        $prepare_update->execute();
        
        header("Location: ../../public/html_php/dashboard.php");

    } catch (Exception $exc) {
        echo "[!] Ha habido un error";
        echo $exc->getMessage();
    }

}