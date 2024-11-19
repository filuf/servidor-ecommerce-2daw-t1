<?php

//destruye las cookies, la sesión y manda al inicio de sesión

setcookie("user", "", time() - (3600 * 24 * 7), "/", "", false, true);
setcookie("pass", "", time() - (3600 * 24 * 7), "/", "", false, true);

session_start();
session_destroy();

header("Location: ../../index.php");
exit;