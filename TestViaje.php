<?php

include_once 'Pasajero.php';
include_once 'Viaje.php';
include_once 'ResponsableV.php';
include_once 'Empresa.php';

/**
 * \\\\ MENU PRINCIPAL /////
 */
function menuPrincipal(){
echo"     ___                  _   ___        _     __\n";
echo"    / _ )__ _____ ___    | | / (_)__ _  (_)__ / /\n";
echo"   / _  / // / -_) _ \   | |/ / / _ `/ / / -_)_/ \n";
echo"  /____/\_,_/\__/_//_/   |___/_/\_,_/_/ /\__(_)  \n";
echo"                                   |___/         \n";
    echo "\n-----  MENU PRINCIPAL  -----\n
    1. EMPRESAS\n
    2. VIAJES\n
    3. RESPONSABLES\n
    4. PASAJEROS\n
    5. SALIR DEL MENÚ\n";
}

$noSalir = true;
while ($noSalir) {
    menuPrincipal();
    $opcion = trim(fgets(STDIN));

    switch ($opcion) {
        case '1': // EMPRESA
            menuEmpresas();
            break;
    
        case '2': // VIAJES
            menuViajes();
            break;
        
        case '3': // RESPONSABLES
            # code...
            break;

        case '4': // PASAJEROS
            # code...
            break;
        
        case '5': // SALIR
            $noSalir = false;
            break;
        
        default:
        
            break;
    }
}

/**
 * \\\\\ MENU DE OPCIONES DE EMPRESA. /////
 */
function menuEmpresas(){
    $noSalir = true;

    while ($noSalir) {
        echo "\n-----  EMPRESAS  -----\n
        1. Ver empresas\n
        2. Cargar empresa\n
        3. Modificar empresa\n
        4. Eliminar empresa\n
        5. Mostrar viajes de empresa\n
        6. Volver al menú principal\n";
        
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER EMPRESAS
                $objEmpresa = new Empresa();
                $arrEmpresas = $objEmpresa->listar();
                strArray($arrEmpresas);
                break;

            case '2': // CARGAR EMPRESA
                cargarEmpresa();
                break;

            case '3': // MODIFICAR EMPRESA
                echo "Seleccione la empresa que quiere modificar: \n";
                $objEmpresa = new Empresa();
                $arrEmpresas = $objEmpresa->listar();
                strArray($arrEmpresas);
                $idempresa = trim(fgets(STDIN));
                modificarEmpresa($idempresa);
                break;
                
            case '4': //ELIMINAR EMPRESA
                echo "Seleccione la empresa que quiere eliminar: \n";
                $objEmpresa = new Empresa();
                $arrEmpresas = $objEmpresa->listar();
                strArray($arrEmpresas);
                $idempresa = trim(fgets(STDIN));
                
                if ($objEmpresa->buscar($idempresa)) {
                    $viaje = new Viaje();
                    $condicion = 'idempresa = ' . $idempresa;
                    $viajesEmpresa = $viaje->listar($condicion);
                    if (!empty($viajesEmpresa)) {
                        echo "La empresa tiene viajes y pasajeros, desea borrar todo? (si / no)\n"; // si o no
                        $opcion = trim(fgets(STDIN));
                        if ($opcion == 'si') {
                            eliminarViajesEmpresa($objEmpresa);
                            eliminarEmpresa($objEmpresa);
                        }
                    }
                    if ($objEmpresa->eliminar()) {
                        echo "La empresa fue eliminada\n";
                    }else {
                        echo "No se pudo eliminar la empresa\n";
                    }
                }

                break;

            case '5': // MOSTRAR VIAJES DE EMPRESA
                echo "Elija el ID de la empresa de la que desea ver sus viajes: \n";
                $objEmpresa = new Empresa();
                $arrEmpresas = $objEmpresa->listar();
                strArray($arrEmpresas);
                $idempresa = trim(fgets(STDIN));
                $viajes = viajesEmpresa($idempresa);
                strArray($viajes);
                break;
                
            case '6': // SALIR
                $noSalir = false;
                break;
            
            default:
                
                break;
        }
    }
}

/**
 * Modifica una empresa.
 */
function modificarEmpresa($idempresa){
    $objEmpresa = new Empresa();
    if ($objEmpresa->buscar($idempresa)) {
        echo $objEmpresa->__toString();
        echo "Ingrese el nombre: \n";
        $enombre = trim(fgets(STDIN));
        echo "Ingrese la dirección\n";
        $edireccion = trim(fgets(STDIN));
        $objEmpresa->setEnombre($enombre);
        $objEmpresa->setEdireccion($edireccion);

        if ($objEmpresa->modificar()) {
            echo "La empresa se modificó con exito!\n";
        }else {
            echo "No se pudo modificar la empresa\n";
        }
    } else {
        echo "Ingrese un ID de empresa válido\n";
    }
}

/**
 * Carga una empresa a la base de datos 
 */
function cargarEmpresa(){
    $objEmpresa = new Empresa();
    echo "Ingrese el nombre de la empresa: \n";
    $nombreEmpresa = trim(fgets(STDIN));
    echo "Ingrese la dirección de la empresa: \n";
    $direccionEmpresa = trim(fgets(STDIN));
    $objEmpresa->cargarDatos($nombreEmpresa, $direccionEmpresa);

    if ($objEmpresa->insertar()) {
        echo "La empresa se cargó con éxito!\n";
        
    }else {
        echo "No pudo cargarse la empresa\n";
        echo $objEmpresa->getMensajeoperacion();
    }
    return $objEmpresa;
}

/**
 * recibe el id de una empresa y elimina todos los viajes de la empresa.
 */
function eliminarViajesEmpresa($idEmpresa)
{
    $viaje = new Viaje();
    $condicion = 'idempresa = ' . $idEmpresa->getIdempresa();
    $viajes = Viaje::listar($condicion);
    foreach ($viajes as $viaje) {
        eliminarPasajeros($viaje);
        eliminarViaje($viaje);
        
    }
}

/**
 * recibe un objeto viaje y elimina todos sus pasajeros.
 */
function eliminarPasajeros($viaje){
    $pasajeros = listadoPasajeros($viaje->getidviaje);
    
    foreach ($pasajeros as $pasajero) {
        eliminarPasajero($pasajero);
    }
}

/**
 * recibe un id de un viaje y devuelve los pasajeros del viaje
 */
function listadoPasajeros($idviaje){
    $pasajero = new Pasajero();
    $condicion = 'idviaje = ' . $idviaje;
    $pasajeros = $pasajero->listar($condicion);
    return $pasajeros;
}

/**
 * recibe el id de una empresa y devuelve un array con los viajes de la empresa
 */
function viajesEmpresa($id){
    $viaje = new Viaje();
    $condicion = 'idempresa = ' . $id;
    $arrViajesEmpresa = $viaje->listar($condicion);
    return $arrViajesEmpresa;
}

/**
 * recibe un objeto empresa y lo elimina
 */
function eliminarEmpresa($objEmpresa){
    if($objEmpresa->eliminar()) {
        echo "La empresa se eliminó con éxito\n";
    } else {
        echo "La empresa no se pudo eliminar.\n";
    }
}

/**
 * recibe un objeto pasajero y lo elimina
 */
function eliminarPasajero($pasajero){
    if ($pasajero->eliminar()) {
        echo "Se elimino el pasajero\n";
    } else {
        echo "No se pudo eliminar el pasajero\n";
    }
}

/**
 * recibe un objeto viaje y lo elimina
 */
function eliminarViaje($objViaje){
     if ($objViaje->eliminar()) {
         echo "El viaje se elimino con éxito.\n";
     } else {
         echo "No se pudo eliminar el viaje\n";
     }
}

/**
 * \\\\\ MENU OPCIONES DE VIAJES ////
 */
function menuViajes(){
    $noSalir = true;

    while ($noSalir) {
        echo "\n-----  VIAJES  -----\n
        1. Ver viajes\n
        2. Cargar un viaje\n
        3. Modificar viaje\n
        4. Eliminar viaje\n
        5. Mostrar pasajeros del viaje\n
        6. Volver al menú principal\n";

        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER VIAJES
                $objViaje = new Viaje();
                $arrViajes = $objViaje->listar();
                strArray($arrViajes);
                break;
            case '2': // CARGAR VIAJE
            
                break;
            case '3': // MODIFICAR VIAJE
            
                break;
            case '4': // ELIMINAR VIAJE
            
                break;
            case '5': // MOSTRAR PASAJEROS DEL VIAJE
            
                break;
            case '6': // SALIR
                $noSalir = false;
                break;
        }
    }
}

/**
 * Convierte un array pasado por parametro en string
 * para ser presentado de una forma más clara.
 */
function strArray($array)
{
    $str = "\n-------------------\n";
    foreach ($array as $item) {
        $str = $str . $item->__toString() . "\n";
    }
    echo $str;
}

?>