<?php
/*Mostrar error de autenticación en el index cuando no existan las credenciales*/

/*Comprobar autenticación y mandar a index al no estar autenticado*/

/*Imprimir cartas*/

/*Imprimir por categorías en caso de haberse seleccionado alguna*/


define("PATH_XML", "../../config/xml/configuracion_db.xml");
define("PATH_XSD", "../../config/xml/configuracion_db_schema.xsd");
require_once "../../config/Singleton_db_sesion.php";


try {
    $db = Connection_db::get_conexion(PATH_XML, PATH_XSD);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["category"] != 0) { // si se selecciona un filtrado

        $prepare = $db->prepare("
            SELECT `nombre_producto`, `descripcion_producto`, `peso_kg_producto`, `dimensiones_producto`, `stock_producto`, `imagen_producto`, `codigo_producto`
            FROM t_productos
            WHERE codigo_categoria = :categoria
        ");
        $prepare->bindParam("categoria", $_POST["category"]);

    } else {

        $prepare = $db->prepare("
        SELECT `nombre_producto`, `descripcion_producto`, `peso_kg_producto`, `dimensiones_producto`, `stock_producto`, `imagen_producto`, `codigo_producto` 
        FROM t_productos
        ");

    }
    $result = $prepare->execute();
    if ($result) {
        $prepare->bindColumn(1, $nombre);
        $prepare->bindColumn(2, $descripcion);
        $prepare->bindColumn(3, $peso);
        $prepare->bindColumn(4, $dimensiones);
        $prepare->bindColumn(5, $stock);
        $prepare->bindColumn(6, $imagen);
        $prepare->bindColumn(7, $codigo);
    }

} catch (PDOException $_exc) {
    echo "Ha habido un error al cargar los productos";
}


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
        <a href="./dashboard.php"><img class="logo" src="../resources/logo.png" alt="Logo" width="160px" height="50px"></a>
        <a href="./pedidos.html">Pedidos</a>
    </nav>
    <main>
        <aside>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <label for="category">filtrar por categoría</label><br>
                <select name="category">
                    <option value="0">Todo</option>
                    <option value="1">Escritura y corrección</option>
                    <option value="2">Papel y cuadernos</option>
                </select>
                <input type="submit" value="Filtrar" class="filtro">
            </form>
        </aside>
        <div class="content">

            <?php
                while ($prepare->fetch(PDO::FETCH_BOUND)) { //mientras queden registros
                    echo '<div class="card">';
                        echo '<p>' . $nombre . '</p>';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($imagen) . '" alt="imagen producto" height="100px" width="100px"><br>';
                        echo '<p>' . $descripcion . '</p>';
                        echo '<p>' . "dimensiones: " . $dimensiones . '</p>';
                        echo '<p>' . "peso: " . $peso . 'kg</p>';
                        if ($stock > 0) {
                            echo '<p>' . "solo quedan " . $stock . ' artículos en stock</p>';
                            echo '<form action="./aniadir_carrito.php" method="post">';
                                echo '<label for="cantidad">Cantidad:</label> ';
                                echo '<input type="hidden" name="codigo_producto" value="' . $codigo . '">'; 
                                echo '<input type="number" name="cantidad" min="1" max="' . $stock . '"><br>';
                                echo '<input type="submit" value="añadir">';
                            echo '</form>';
                            
                          
                            
                            
                        
                        } else
                            echo '<p> no quedan artículos en stock</p>';

                        
                    echo '</div>';
                }
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