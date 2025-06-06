<?php
session_start();

function verificarLogin() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../views/login.php");
        exit;
    }
}
function protegerAdmin() {
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
        header("Location: ../login.php");
        exit;
    }

function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function esAlumno() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'alumno';
}

}