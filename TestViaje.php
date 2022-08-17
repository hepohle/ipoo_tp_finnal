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
            menuResponsable();
            break;

        case '4': // PASAJEROS
            menuPasajero();
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
                if (hayEmpresa()) {
                    strArray($arrEmpresas);    
                } else {
                    echo "No hay empresas cargadas\n";
                }
                
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
                if (count($viajes) > 0) {
                    strArray($viajes);    
                } else {
                    echo "La empresa no tiene viajes cargados.\n";
                }
                
                break;
                
            case '6': // SALIR
                $noSalir = false;
                break;
            
            default:
            echo "Opcion incorrecta!\n";
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
    $pasajeros = listadoPasajeros($viaje->getidviaje());
    
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
                if (hayViaje()) {      
                    strArray($arrViajes);
                }else {
                    echo "No hay viajes cargados\n";
                }
                
                break;
            case '2': // CARGAR VIAJE
                if (hayEmpresa() && hayResponsable()) {
                    cargarViaje();
                }else {
                    echo "No hay Empresas y/o Responsables cargados. No es posible cargar viajes.\n";
                }
                break;
            case '3': // MODIFICAR VIAJE
                if (hayViaje()) {
                    $viaje = new Viaje();
                    $arrViajes = $viaje->listar("");
                    strArray($arrViajes);

                    echo "Ingrese el ID del viaje que quiere modificar: \n";
                    $id = trim(fgets(STDIN));

                    if ($viaje->buscar($id)) {
                        modificarViaje($viaje);
                    } else {
                        echo "El ID ingresado no corresponde a ningún viaje cargado.\n";
                    }

                }else {
                    echo "No hay viajes cargados para modificar\n";
                }
                break;
            case '4': // ELIMINAR VIAJE
                $viaje = new Viaje();
                $arrViajes = $viaje->listar("");
                strArray($arrViajes);
                echo "Ingrese el ID del viaje para borrarlo:\n";
                $idViaje = trim(fgets(STDIN));
                if($viaje->buscar($idViaje)){
                    if (count(listadoPasajeros($idViaje)) > 0) {
                        echo "El viaje tiene pasajeros, quiere borrarlos? (si / no)\n";
                        $resp = strtoupper(trim(fgets(STDIN)));
                        if ($resp == "SI") {
                            eliminarPasajeros($viaje);
                            eliminarViaje($viaje);
                        }
                    }else {
                        eliminarViaje($viaje);
                    }
                } else {
                    echo "El ID ingresado no corresponde a ningún viaje cargado.\n";
                }
                break;
            case '5': // MOSTRAR PASAJEROS DEL VIAJE
                if (hayViaje()) {
                    $viaje = new Viaje();
                    $arrViajes = $viaje->listar("");
                    strArray($arrViajes);

                    echo "Ingrese el ID del viaje para ver sus pasajeros: \n";
                    $idViaje = trim(fgets(STDIN));

                    if ($viaje->buscar($idViaje)) {
                        $pasajeros = listadoPasajeros($idViaje);
                        if (count($pasajeros) > 0) {
                            strArray($pasajeros);
                        } else {
                            echo "El viaje no tiene pasajeros\n";
                        }
                    } else {
                        echo "El ID ingresado no corresponde a ningún viaje cargado.\n";
                    }

                }else {
                    echo "No hay viajes cargados.\n";
                }
                break;
            case '6': // SALIR
                $noSalir = false;
                break;
            default:
            echo "Opcion incorrecta!\n";
        }
    }
}

/**
 * Retorna un boolean si hay o no empresas cargadas.
 */
function hayEmpresa(){
    $objEmpresa = new Empresa();
    $arrEmpresas = $objEmpresa->listar();
    $hayEmpresaCargada = count($arrEmpresas) > 0;
    return $hayEmpresaCargada;
}

/**
 * Retorna un boolean si hay o no Responsables cargados.
 */ 
function hayResponsable(){
    $objResponsable = new ResponsableV();
    $arrResponsable = $objResponsable->listar();
    $hayResponsableCargado = count($arrResponsable) > 0;
    return $hayResponsableCargado;
}

/**
 * Retorna un boolean si hay o no viajes
 */
function hayViaje(){
    $objViaje = new Viaje();
    $arrViajes = $objViaje->listar();
    $hayViajeCargado = count($arrViajes) > 0;
    return $hayViajeCargado;
}

function hayPasajero(){
    $objPasajero = new Pasajero();
    $arrPasajeros = $objPasajero->listar("");
    $hayPasajeroCargado = count($arrPasajeros) > 0;
    return $hayPasajeroCargado;
}

/**
 * Retorna un boolean si hay o no lugar en el viaje
 */
function hayLugar($idViaje){
    $viaje = new Viaje();
    $viaje->buscar($idViaje);
    $lugares = $viaje->getvcantmaxpasajeros();
    $pasajeros = listadoPasajeros($idViaje);
    return count($pasajeros) < $lugares;
}

function cargarViaje(){
    $viaje = new Viaje();
    echo "Ingrese los datos del viaje: \n";

    do {//Comprueba que no haya viajes al mismo destino que se intenta cargar.
        echo "Destino: \n";
        $destino = trim(fgets(STDIN));
        $hayViajesMismoDestino = viajeMismoDestino($destino);
        if ($hayViajesMismoDestino) {
            echo "Ya hay un viaje cargado a ese destino\n";
        }
    } while ($hayViajesMismoDestino);

    echo "Cantidad máxima de pasajeros: \n";
    $cantMaxPasajeros = trim(fgets(STDIN));

    do {// Comprueba que el ID corresponda a una empresa cargada.
        echo "ID de la Empresa: \n";
        $idEmpresa = trim(fgets(STDIN));
        $empresa = new Empresa();
        $hayEmpresa = $empresa->buscar($idEmpresa);
        if (!$hayEmpresa) {
            echo "El ID ingresado no corresponde a ninguna empresa cargada.\n";
        }
    } while (!$hayEmpresa);

    do {// Comprueba que el número ingresado corresponda a un empleado cargado.
        echo "Número de empleado responsable: \n";
        $nroResponsable = trim(fgets(STDIN));
        $responsable = new ResponsableV();
        $hayResponsable = $responsable->buscar($nroResponsable);

        if (!$hayResponsable) {
            echo "El número ingresado no coresponde a ningún empleado cargado \n";
        }
    } while (!$hayResponsable);

    echo "Importe: \n";
    $importe = trim(fgets(STDIN));

    echo "Tipo de asiento: (cama / samicama)\n";
    $tipoAsiento = trim(fgets(STDIN));

    echo "Ida y vuelta: (si / no)\n";
    $idaVuelta = trim(fgets(STDIN));

    $viaje->cargarDatos($destino, $cantMaxPasajeros, $empresa, $responsable, $importe, $tipoAsiento, $idaVuelta);

    if ($viaje->insertar()) {
        echo "El viaje fue cargado\n";
    } else {
        echo $viaje->getmensajeoperacion();
    }
    return $viaje;
}

function modificarViaje($viaje){
    echo "Ingrese el destino: \n";
    $destino = trim(fgets(STDIN));
    if (viajeMismoDestino($destino)) {
        echo "Ya existe un viaje a ese destino!\n";
    }else {
        echo "Ingrese la cantidad máxima de pasajeros:\n";
        $cantMaxPasajeros = trim(fgets(STDIN));
        echo "Ingrese el importe:\n";
        $importe = trim(fgets(STDIN));
        echo "Ingrese el tipo de asiento: (cama / semicama):\n";
        $tipoAsiento = trim(fgets(STDIN));
        $viaje->setvdestino($destino);
        $viaje->setvcantmaxpasajeros($cantMaxPasajeros);
        $viaje->setvimporte($importe);
        $viaje->settipoasiento($tipoAsiento);

        if ($viaje->modificarViaje()) {
            echo "El viaje se modificó con éxito!\n";
        }else {
            echo "No se pudo modificar el viaje\n";
        }
    }
}

function viajeMismoDestino($destinoViaje){
    $resp = false;
    $viaje = new Viaje();
    $condicion = " vdestino = '{$destinoViaje}' ";
    $arrMismoDestino = $viaje->listar($condicion);
    if (count($arrMismoDestino) > 0) {
        $resp = true;
    }else {
        $resp;
    }
    return $resp;
}

/**
 * /////  MENU OPCIONES PASAJERO  \\\\\
 */

 function menuPasajero(){
    $noSalir = true;

    while ($noSalir) {
        echo "\n-----  PASAJERO  -----\n
        1. Ver pasajeros\n
        2. Cargar un pasajero\n
        3. Modificar pasajero\n
        4. Eliminar pasajero\n
        5. Volver al menú principal\n";

        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER PASAJEROS
                if (hayPasajero()) {
                    $pasajero = new Pasajero();
                    $pasajeros = $pasajero->listar("");
                    strArray($pasajeros);
                } else {
                    echo "No hay pasajeros cargados!\n";
                }
                break;
            case '2': // CARGAR PASAJERO5
                    cargarPasajero();
                break;
            case '3': // MODIFICAR PASAJERO
                if(hayPasajero()){
                    
                    modificarPasajero();
                } else {
                    echo "No hay pasajeros cargados!\n";
                }
                break;
            case '4': // ELIMINAR PASAJERO
                if (hayPasajero()) {
                    echo "Ingrese el DNI del pasajero a eliminar\n";
                    $dniPasajero = trim(fgets(STDIN));
                    $pasajero = new Pasajero();
                    if ($pasajero->buscar($dniPasajero)) {
                        eliminarPasajero($pasajero);    
                    } else {
                        echo "El DNI ingresado no corresponde a ningún pasajero cargado.\n";
                    }    
                }else {
                    echo "No hay pasajeros cargados.\n";
                }
                break;
            case '5': // SALIR
                $noSalir = false;
                break;
            default:
                echo "Opcion incorrecta!\n";
        }
    }
 }

function cargarPasajero(){
    $pasajero = new Pasajero();
    echo "Ingrese los datos del pasajero: \n";

    do {
        echo "DNI: \n";
        $dniPasajero = trim(fgets(STDIN));
        $check = $pasajero->buscar($dniPasajero);
        if ($check) {
            echo "El documento ingresado ya existe.\n";
        }
    } while ($check);
    
    echo "Nombre: \n";
    $nombre = trim(fgets(STDIN));
    echo "Apellido: \n";
    $apellido = trim(fgets(STDIN));
    echo "Teléfono: \n";
    $telefono = trim(fgets(STDIN));

    do {
        echo "ID del viaje: \n";
        $idViaje = trim(fgets(STDIN));
        $viaje = new Viaje();
        $check = $viaje->buscar($idViaje);
        
        if (!$check) {
            echo "El ID ingresado no corresponde a ningún viaje.\n";
        }else {
            $pasajeros = listadoPasajeros($idViaje);
            $cantMaxPasajeros = $viaje->getvcantmaxpasajeros();

            if (count($pasajeros) >= $cantMaxPasajeros) {
                echo "El viaje ya está completo. Elija otro.\n";
                $check = false;
            }
        }
    } while (!$check);

    $pasajero->cargarDatos($dniPasajero, $nombre,$apellido,$telefono, $viaje);

    if ($pasajero->insertar()) {
        echo "El pasajero fue cargado con éxito!\n";
    } else {
        echo "No se pudo cargar el pasajero.\n";
    }
}

function modificarPasajero(){
    $pasajero = new Pasajero();
    $pasajeros = $pasajero->listar();
    strArray($pasajeros);

    echo "Ingrese el Nro de DNI del pasajero: \n";
    $dni = trim(fgets(STDIN));

    $pasajero->buscar($dni);

    if ($pasajero->getrdocumento() != "")
    {
        echo "Ingrese nombre: \n";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese Apellido: \n";
        $apellido = trim(fgets(STDIN));
        echo "Ingrese telefono: \n";
        $telefono = trim(fgets(STDIN));  
        
        $pasajero->setpnombre($nombre);
        $pasajero->setapellido($apellido);
        $pasajero->setptelefono($telefono);


        echo "Ingrese ID del viaje: \n";
        $id = trim(fgets(STDIN));

        $viaje = new Viaje();
        if ($viaje->buscar($id)) {
            if (hayLugar($id)) {
                $pasajero->setobjviaje($viaje);

            } else {
                echo "No hay lugar disponible en este viaje.\n";
            }
        } else {
            echo "El ID ingresado no corresponde a ningún viaje cargado.\n";
        }
    }

    if ($pasajero->modificar()) {
        echo "El pasajero se modificó con éxito!\n";
    }else {
        echo "No se pudo modificar al pasajero.\n";
    }
}

/**
 * /////  MENU OPCIONES RESPONSABLE  \\\\\
 */
function menuResponsable(){
    $noSalir = true;

    while ($noSalir) {
        echo "\n-----  RESPONSABLE  -----\n
        1. Ver responsables\n
        2. Cargar un responsable\n
        3. Modificar responsable\n
        4. Eliminar responsable\n
        5. Volver al menú principal\n";

        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER RESPONSABLES
                # code...
                break;
            
            case '2': //CARGAR RESPONSABLE
                # code...
                break;
            case '3': // MODIFICAR RESPONSABLE
                # code...
                break;
            case '4': //ELIMINAR RESPONSABLE
                # code...
                break;
            case '5': // SALIR
                $noSalir = false;
                break;
            
            default:
                echo "Opcion incorrecta.\n";
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