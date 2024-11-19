<?php
/**
 * @param string $PATH_XML ruta al fichero de configuración xml de la conexión a la base de datos
 * @param string $PATH_XSD ruta al fichero de validación xsd del xml con la configuración a la conexión de la base de datos
 * @return bool true si se ha encontrado una única fila con esas credenciales en la base de datos, false si las cookies no están seteadas o no se encuentra la credencial
 */
function validate_user(string $PATH_XML, string $PATH_XSD) : bool {
    if (isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) { // si las cookies user y pass existen
        try {
            $db = Connection_db::get_conexion($PATH_XML, $PATH_XSD);
            //consulta preparada
            $prepare = $db->prepare(
                '   SELECT `correo_electronico`, `contraseña`
                    FROM t_credenciales_empresa
                    WHERE `correo_electronico` = :user AND `contraseña` = :pass'
            );
            //proporcionamos los parámetros y ejecutamos
            $prepare->bindParam("user", $_COOKIE["user"]);
            $prepare->bindParam("pass", $_COOKIE["pass"]);
            $result = $prepare->execute();
            if ($result) { // si la consulta es exitosa
                return ($prepare->rowCount() == 1); //si se ha devuelto una fila es que el usuario y contraseña son correctos
            }
        } catch (PDOException $exc) {
            echo "[!] Ha habido un error.<br>";
            echo "[+] Codigo de error " . $exc->getCode() . "<br>";
            echo $exc->getMessage();
        }

    }
    return false;
}