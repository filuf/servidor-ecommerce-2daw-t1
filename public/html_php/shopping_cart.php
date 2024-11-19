<?php

//query a usar

SELECT t1.codigo_carrito, t1.codigo_producto, t1.cantidad_producto, t2.nombre_producto, t2.descripcion_producto, t2.stock_producto
FROM t_productos_pedidos t1
LEFT JOIN
t_productos t2
ON t1.codigo_producto = t2.codigo_producto