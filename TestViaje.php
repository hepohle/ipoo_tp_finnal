<?php

include_once 'Pasajero.php';
include_once 'Viaje.php';
include_once 'ResponsableV.php';
include_once 'Empresa.php';


// MENU PRINCIPAL 

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
            # code...
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
                        echo "La empresa tiene viajes y pasajeros, desea borrar todo?\n"; // si o no
                        $opcion = trim(fgets(STDIN));
                        if ($opcion == 'si') {
                            
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
                
                break;
                
            case '6': // SALIR
                $noSalir = false;
                break;
            
            default:
                
                break;
        }
    }
}


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

function eliminarViajesEmpresa($idEmpresa)
{
    $viaje = new Viaje();
    $condicion = 'idempresa = ' . $idEmpresa->getIdempresa();
    $viajes = Viaje::listar($condicion);
    foreach ($viajes as $viaje) {
        eliminarPasajeros($viaje);
        
    }
}

function eliminarPasajeros($viaje){
    $pasajero = new Pasajero();
    $condicion = 'idviaje = ' . $viaje->getidviaje;
    $pasajeros = $pasajero ->listar($condicion);

    foreach ($pasajeros as $pasajero_i) {
        if ($pasajero_i->Eliminar()) {
            echo "Pasajero eliminado!\n";
        }else {
            echo "No se pudo eliminar al pasajero\n";
        }
    }
}

function eliminarPasajero(){

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