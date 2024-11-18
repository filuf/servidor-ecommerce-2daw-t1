<?php

require_once "./config/Singleton_db_sesion.php";
require_once "./src/validate_user.php";

define("PATH_XML", "./config/xml/configuracion_db.xml");
define("PATH_XSD", "./config/xml/configuracion_db_schema.xsd");
define("PATH_DASHBOARD", "./public/html_php/dashboard.php");

if (validate_user(PATH_XML, PATH_XSD)) {
    header("Location: " . PATH_DASHBOARD);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["user"]) && isset($_POST["pass"])) {
        //establece las cookies user y pass con una duración de una semana
        setcookie("user", $_POST["user"], time() + (3600 * 24 * 7), "", "", false, true);
        setcookie("pass", $_POST["pass"], time() + (3600 * 24 * 7), "", "", false, true);
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
                        if (isset($err)) {
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