<?php
    session_start();
    if (!isset($_SESSION['usuario_validado']) || $_SESSION['usuario_validado'] == false) {
        header("Location: ../login.php?erro=login");
        exit();
    }
?>