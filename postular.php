<?php
session_start();
require_once 'app/Logica/PostulacionLogica.php'; 

if (!isset($_SESSION['usuario']) || $_SESSION['tipo_usuario'] !== 'postulante') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idLlamado = intval($_POST['id_llamado']);
    $ciPostulante = $_SESSION['usuario']; // La cédula del usuario logueado
    $expectativa = floatval($_POST['expectativa_salarial']);
    $nota = $_POST['nota'] ?? '';
    
    $rutaArchivoDB = '';
    if (isset($_FILES['archivo_cv']) && $_FILES['archivo_cv']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = 'cv_' . time() . '_' . basename($_FILES['archivo_cv']['name']);
        $dirDestino = 'static/archivos/';
        if (!is_dir($dirDestino)) {
            mkdir($dirDestino, 0777, true);
        }
        $rutaDestino = $dirDestino . $nombreArchivo;
        if (move_uploaded_file($_FILES['archivo_cv']['tmp_name'], $rutaDestino)) {
            $rutaArchivoDB = $rutaDestino;
        }
    }

    $postulacionLogica = new PostulacionLogica();
    $resultado = $postulacionLogica->registrarPostulacion($ciPostulante, $idLlamado, $expectativa, $nota, $rutaArchivoDB);

    header("Location: index.php?exito=postulado");
    exit();
}