<?php
session_start(); //busca la sesion
session_unset(); //vacia la sesion
session_destroy(); //destruye la sesion

header("Location: login.php"); //pal login 
exit();
?>