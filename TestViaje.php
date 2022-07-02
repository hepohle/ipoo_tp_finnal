<?php

include_once 'Pasajero.php';
include_once 'Viaje.php';
include_once 'ResponsableV.php';
include_once 'Empresa.php';


// MENU PRINCIPAL 

function menuPrincipal(){
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
        5. Buscar empresa\n
        6. Volver al menú principal\n";
        
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER EMPRESAS
                listarEmpresa();
                break;

            case '2': // CARGAR EMPRESA
                echo "Ingrese el nombre de la empresa: \n";
                $nombreEmpresa = trim(fgets(STDIN));
                echo "Ingrese la dirección de la empresa: \n";
                $direccionEmpresa = trim(fgets(STDIN));

                $objEmpresa = new Empresa();
                $objEmpresa->cargarDatos($nombreEmpresa, $direccionEmpresa);
                if ($objEmpresa->insertar()) {
                    echo "La empresa se cargó con éxito!\n";
                    
                }else {
                    echo "No pudo cargarse la empresa\n";
                }
                break;

            case '3': // MODIFICAR EMPRESA
                echo "Seleccione la empresa que quiere modificar: \n";
                listarEmpresa();
                $idempresa = trim(fgets(STDIN));
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
                }
                break;
                
            case '4': //ELIMINAR EMPRESA
                echo "Seleccione la empresa que quiere eliminar: \n";
                listarEmpresa();
                $idempresa = trim(fgets(STDIN));
                $objEmpresa = new Empresa();
                if ($objEmpresa->buscar($idempresa)) {
                    if ($objEmpresa->eliminar()) {
                        echo "La empresa fue eliminada\n";
                    }else {
                        echo "No se pudo eliminar la empresa\n";
                    }
                }

                break;

            case '5': // BUSCAR EMPRESA
            
                break;
                
            case '6': // SALIR
                $noSalir = false;
                break;
            
            default:
                
                break;
        }
    }
}


/// METODOS PARA LISTAR

function listarEmpresa(){
    $objEmpresa = new Empresa;
    $allEmpresas = $objEmpresa->listar('');
    $strEmpresas = '';

    for ($i=0; $i < count($allEmpresas); $i++) { 
        $strEmpresas .= ($i+1) . ". " . $allEmpresas[$i] . "\n--------------------\n";
    }
    echo $strEmpresas;
}

function listarViajes(){
    $objViaje = new Viaje;
    $allViajes = $objViaje->listar('');
    $strViajes = '';

    for ($i=0; $i < count($allViajes); $i++) { 
        $strViajes .= ($i+1) . ". " . $allViajes[$i] . "\n--------------------\n";
    }
    echo $strViajes;
}

function listarResponsables(){
    $objResponsable = new ResponsableV;
    $allResponsables = $objResponsable->listar('');
    $strResponsables = '';

    for ($i=0; $i < count($allResponsables); $i++) { 
        $strResponsables .= ($i+1) . ". " . $allResponsables[$i] . "\n--------------------\n";
    }
    echo $strResponsables;
}

function modificarEmpresa(){
    $objEmpresa = new Empresa();
    $empresas = $objEmpresa->listar('');
    $resp = false;
    do {
        echo "Seleccione la empresa que quiere modificar: \n";
        listarEmpresa();
        $opcion = trim(fgets(STDIN));
        if (is_numeric($opcion)) {
            
            if ($opcion > 0 && $opcion <= count($empresas)) {
                $resp = true;
                $empresaElegida = $empresas[$opcion - 1];
            } else {
                echo "Ingrese una opcion válida!";
            }
        }else {
            echo "Ingrese una opcion válida!";
        }
    } while ($resp == false);

}

?>


<!-- switch ($opcion) {
            case '1':
                
                break;

            case '2':
            
                break;

            case '3':
            
                break;
                
            case '4':
            
                break;

            case '5':
            
                break;
                
            case '6':
                $noSalir = false;
                break;
            
            default:
                
                break;
        } -->