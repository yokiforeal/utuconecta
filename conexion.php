<?php
function obtenerConexion() {
    $host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "utuconecta";

    $conn = new mysqli($host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // es para el tema de los tildes y eso como lo del header
    $conn->set_charset("utf8mb4");

    return $conn;
}
?>