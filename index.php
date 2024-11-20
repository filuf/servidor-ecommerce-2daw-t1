<?php

require_once "./config/Singleton_db_sesion.php";
require_once "./src/functions/validate_user.php";

define("PATH_XML", "./config/xml/configuracion_db.xml");
define("PATH_XSD", "./config/xml/configuracion_db_schema.xsd");
define("PATH_DASHBOARD", "./public/html_php/dashboard.php");

session_start();

if (validate_user(PATH_XML, PATH_XSD)) {
    //guardamos el id de la empresa en sesión
    $db = Connection_db::get_conexion(PATH_XML,PATH_XSD);
    $prepare = $db->prepare("
    SELECT `id_empresa` FROM `t_credenciales_empresa` WHERE `correo_electronico` = :mail
    ");
    $prepare->bindParam("mail", $_COOKIE["user"]);
    $prepare->execute();
    $first_row = $prepare->fetch(PDO::FETCH_NUM);
    if ($first_row) {
        $_SESSION["login_attemp_error"] = false;
        $_SESSION["id_empresa"] = $first_row[0];
        header("Location: " . PATH_DASHBOARD);
    } else {
        echo "Hubo un problema al iniciar sesión.";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["user"]) && isset($_POST["pass"])) {
        $_SESSION["login_attemp_error"] = true;
        //establece las cookies user y pass con una duración de una semana
        setcookie("user", $_POST["user"], time() + (3600 * 24 * 7), "/", "", false, true);
        setcookie("pass", $_POST["pass"], time() + (3600 * 24 * 7), "/", "", false, true);
        //la página necesita ser recargada antes de usar las cookies
        header("Location: ./index.php"); 
    }
}


?>

<!DOCTYPE html>
<!--Aitor Pascual Jiménez-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/login.css">
    <title>login</title>
</head>
<body>
    <h1>LOGIN</h1>
    <div id="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <label for="user">usuario</label><br>
            <input type="text" name="user" id="user" placeholder="" required><br>
            <label for="pass">contraseña</label><br>
            <input type="password" name="pass" id="pass" value="" required><br>
            <input type="submit" value="Login">

            <div id="error">
                <h2 style="color: red;">
                    <?php 
                        if (isset($_SESSION["login_attemp_error"]) && $_SESSION["login_attemp_error"] == true) {
                            echo"El usuario o la contraseña no son correctos";
                        }
                    ?>
                </h2>
            </div>
            
        </form>
    </div>

    <h3>Aitor Pascual Jiménez</h3>
    <h3>Ningún derecho reservado</h3>
</body>
</html>