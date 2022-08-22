<?php
include_once 'datos/BaseDatos.php';

$baseDatos = new BaseDatos();

$baseDatosIniciada = $baseDatos->iniciar();

if ($baseDatosIniciada) {
    echo "\e[1;37;42mConexion con la base de datos establecida\e[0m\n";
} else {
    $errorCarga = $baseDeDatos->ERROR;
    echo $errorCarga;
}