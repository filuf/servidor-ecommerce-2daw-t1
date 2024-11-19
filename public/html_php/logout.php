<?php

//destruye las cookies, la sesión y manda al inicio de sesión
setcookie("user", $_POST["user"], time() - 1, "/", "", false, true);
setcookie("pass", $_POST["pass"], time() - 1, "/", "", false, true);

session_start();
session_destroy();

header("Location: ../../index.php");
exit;