<?php
/*Comprobar autenticación y mandar a index al no estar autenticado*/

/*Imprimir cartas*/

/*Imprimir por categorías en caso de haberse seleccionado alguna*/


define("PATH_XML", "../../../config/xml/configuracion_db.xml");
define("PATH_XSD", "../../../config/xml/configuracion_db_schema.xsd");
require_once "../../../config/singleton_db_sesion.php";

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>dashboard</title>
</head>
<body>
    <nav>
        <a href="./dashboard.html"><img class="logo" src="../resources/logo.png" alt="Logo" width="160px" height="50px"></a>
        <a href="./pedidos.html">Pedidos</a>
    </nav>
    <main>
        <aside>
            <form action="" method="post">
                <label for="category">filtrar por categoría</label><br>
                <select name="category" id="">
                    <option value="0">Todo</option>
                    <option value="1">Escritura y corrección</option>
                    <option value="2">Papel y cuadernos</option>
                </select>
                <input type="submit" value="Filtrar">
            </form>
        </aside>
        <div class="content">

            <?php

            ?>

            <div class="card">
                <p>nombre producto</p>
                <img src="" alt="una foto">
                <p>descripcion .. .asd ahdoahdljahd lahldjal djadjadjla j d</p>
                <p>dimensiones: 15cm x 434234cm</p>
                <p>peso: 0.10kg</p>
            </div>
            <div class="card">
                <p>nombre producto</p>
                <img src="" alt="una foto">
                <p>descripcion .. .asd ahdoahdljahd lahldjal djadjadjla j d</p>
                <p>dimensiones: 15cm x 434234cm</p>
                <p>peso: 0.10kg</p>
            </div>
            <div class="card">
                <p>nombre producto</p>
                <img src="" alt="una foto">
                <p>descripcion .. .asd ahdoahdljahd lahldjal djadjadjla j d</p>
                <p>dimensiones: 15cm x 434234cm</p>
                <p>peso: 0.10kg</p>
            </div>
            <div class="card">
                <p>nombre producto</p>
                <img src="" alt="una foto">
                <p>descripcion .. .asd ahdoahdljahd lahldjal djadjadjla j d</p>
                <p>dimensiones: 15cm x 434234cm</p>
                <p>peso: 0.10kg</p>
            </div>
                        <div class="card">
                <p>nombre producto</p>
                <img src="" alt="una foto">
                <p>descripcion .. .asd ahdoahdljahd lahldjal djadjadjla j d</p>
                <p>dimensiones: 15cm x 434234cm</p>
                <p>peso: 0.10kg</p>
            </div>
            <div class="card">
                <p>nombre producto</p>
                <img src="" alt="una foto">
                <p>descripcion .. .asd ahdoahdljahd lahldjal djadjadjla j d</p>
                <p>dimensiones: 15cm x 434234cm</p>
                <p>peso: 0.10kg</p>
            </div>

        </div>
    </main>
</body>
</html>