<?php
include_once 'datos/BaseDatos.php';
include_once 'datos/Pasajero.php';
include_once 'datos/Viaje.php';
include_once 'datos/ResponsableV.php';
include_once 'datos/Empresa.php';
include_once 'control/abmEmpresa.php';
include_once 'control/abmPasajero.php';
include_once 'control/abmResponsableV.php';
include_once 'control/abmViaje.php';

/**
 * \\\\ MENU PRINCIPAL /////
 */
function menuPrincipal(){
    echo"\e[32m     ___                  _   ___        _     __\e[0m\n";
    echo"\e[32m    / _ )__ _____ ___    | | / (_)__ _  (_)__ / /\e[0m\n";
    echo"\e[32m   / _  / // / -_) _ \   | |/ / / _ `/ / / -_)_/ \e[0m\n";
    echo"\e[32m  /____/\_,_/\__/_//_/   |___/_/\_,_/_/ /\__(_)  \e[0m\n";
    echo"\e[32m                                   |___/         \e[0m\n";
    echo "\n\e[1;37;42m-----  MENU PRINCIPAL  -----\e[0m\n";
echo 
"\n1. EMPRESAS\n
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
        echo "\n\e[1;37;42m-----  EMPRESAS  -----\e[0m\n";
        echo
"\n1. Ver empresas\n
2. Cargar empresa\n
3. Modificar empresa\n
4. Eliminar empresa\n
5. Mostrar viajes de empresa\n
6. Volver al menú principal\n";
        
        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER EMPRESAS
                verEmpresas();
                break;

            case '2': // CARGAR EMPRESA
                cargarEmpresa();
                break;

            case '3': // MODIFICAR EMPRESA
                modificarEmpresa();
            break;
                
            case '4': //ELIMINAR EMPRESA
                borrarEmpresa();
            break;

            case '5': // MOSTRAR VIAJES DE EMPRESA
                mostrarViajesEmpresa();
            break;
                
            case '6': // SALIR
                $noSalir = false;
                break;
            
            default:
            echo "\e[1;37;41mOpcion incorrecta!\e[0m\n";
        }
    }
}

/**
 * ////////// FUNCIONES EMPRESA \\\\\\\\\\
 */

/**
 * Devuelve el objeto empresa seleccionado
 */
function seleccionarEmpresa(){
    $abmEmpresa = new abmEmpresa();
    do {
        echo $abmEmpresa->listarEmpresas();
        echo "Seleccione el ID de la empresa: \n";
        $id = trim(fgets(STDIN));
        $objEmpresa = $abmEmpresa->traerEmpresa($id);    

        if ($objEmpresa == null) {
            echo "\n\e[1;37;41mEl ID ingresado no corresponde a una empresa.\e[0m\n";
        }

    } while ($objEmpresa == null);
    
    return $objEmpresa;
}

/**
 * Devuelve un string con todos los datos de todas las empresas cargadas.
 */
function verEmpresas(){
    $abmEmpresa = new abmEmpresa();
    echo $abmEmpresa->listarEmpresas();
}

/**
 * Carga una empresa a la base de datos 
 */
function cargarEmpresa(){
    $abmEmpresa = new abmEmpresa();
    echo "Ingrese el nombre de la empresa: \n";
    $nombreEmpresa = trim(fgets(STDIN));
    echo "Ingrese la dirección de la empresa: \n";
    $direccionEmpresa = trim(fgets(STDIN));
    //$abmEmpresa->agregarEmpresa($nombreEmpresa, $direccionEmpresa);

    if ($abmEmpresa->agregarEmpresa($nombreEmpresa, $direccionEmpresa)) {
        echo "\e[1;37;42mLa empresa se cargó con éxito!\e[0m\n";
    }else {
        echo "No pudo cargarse la empresa\n";   
    }
}

/**
 * Modifica una empresa.
 */
function modificarEmpresa(){
    $abmEmpresa = new abmEmpresa();
    $objEmpresa = seleccionarEmpresa();

    echo "Ingrese el nombre: \n";
    $enombre = trim(fgets(STDIN));
    echo "Ingrese la dirección\n";
    $edireccion = trim(fgets(STDIN));

    $modificado = $abmEmpresa->modificarDatosEmpresa($objEmpresa, $enombre, $edireccion);
    
    if ($modificado) {
        echo "\e[1;37;42mLa empresa se modificó con exito!\e[0m\n";
    }else {
        echo "No se pudo modificar la empresa\n";
    }
}

/**
 * Muestra los viajes cargados de una empresa
 */
function mostrarViajesEmpresa(){
    $abmEmpresa = new abmEmpresa();
    $objEmpresa = seleccionarEmpresa();
    $id = $objEmpresa->getIdempresa();

    echo $abmEmpresa->listarViajesEmpresa($id);
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
        echo "\e[1;37;42mLa empresa se eliminó con éxito\e[0m\n";
    } else {
        echo "\e[1;37;41mLa empresa no se pudo eliminar.\e[0m\n";
    }
}

/**
 * Borra una empresa de la base de datos, si es posible.
 */
function borrarEmpresa(){
    $abmEmpresa = new abmEmpresa();
    $objEmpresa = seleccionarEmpresa();
    $empresaBorrada = $abmEmpresa->eliminarEmpresa($objEmpresa);

    if ($empresaBorrada) {
        echo "\e[1;37;42mLa empresa fue borrada\e[0m\n";
    }else {
        echo "\e[1;37;41mLa empresa tiene viajes cargados no se puede eliminar.\e[0m\n";
    }

}

/**
 * recibe un objeto pasajero y lo elimina
 */
function eliminarPasajero($pasajero){
    if ($pasajero->eliminar()) {
        echo "\e[1;37;42mSe elimino el pasajero\e[0m\n";
    } else {
        echo "\e[1;37;41mNo se pudo eliminar el pasajero\e[0m\n";
    }
}

/**
 * recibe un objeto viaje y lo elimina
 */
function eliminarViaje($objViaje){
     if ($objViaje->eliminar()) {
         echo "\e[1;37;42mEl viaje se elimino con éxito.\e[0m\n";
     } else {
         echo "\e[1;37;41mNo se pudo eliminar el viaje\e[0m\n";
     }
}

/**
 * \\\\\ MENU OPCIONES DE VIAJES ////
 */
function menuViajes(){
    $noSalir = true;

    while ($noSalir) {
        echo "\n\e[1;37;42m-----  VIAJES  -----\e[0m\n";       
        echo
"\n1. Ver viajes\n
2. Cargar un viaje\n
3. Modificar viaje\n
4. Eliminar viaje\n
5. Mostrar pasajeros del viaje\n
6. Volver al menú principal\n";

        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER VIAJES
                verViajes();
                break;
            case '2': // CARGAR VIAJE
                if (hayEmpresa() && hayResponsable()) {
                    cargarViaje();
                }else {
                    echo "\e[1;37;41mNo hay Empresas y/o Responsables cargados. No es posible cargar viajes.\e[0m\n";
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
                        echo "\e[1;37;41mEl ID ingresado no corresponde a ningún viaje cargado.\e[0m\n";
                    }

                }else {
                    echo "\e[1;37;41mNo hay viajes cargados para modificar\e[0m\n";
                }
                break;
            case '4': // ELIMINAR VIAJE
                borrarViaje();

                // $viaje = new Viaje();
                // $arrViajes = $viaje->listar("");
                // strArray($arrViajes);
                // echo "Ingrese el ID del viaje para borrarlo:\n";
                // $idViaje = trim(fgets(STDIN));
                // if($viaje->buscar($idViaje)){
                //     if (count(listadoPasajeros($idViaje)) > 0) {
                //         echo "\e[1;37;42mEl viaje tiene pasajeros, quiere borrarlos? (si / no)\e[0m\n";
                //         $resp = strtoupper(trim(fgets(STDIN)));
                //         if ($resp == "SI") {
                //             eliminarPasajeros($viaje);
                //             eliminarViaje($viaje);
                //         }
                //     }else {
                //         eliminarViaje($viaje);
                //     }
                // } else {
                //     echo "\e[1;37;41mEl ID ingresado no corresponde a ningún viaje cargado.\e[0m\n";
                // }
                break;
            case '5': // MOSTRAR PASAJEROS DEL VIAJE
                mostrarPasajerosViaje();
                break;
            case '6': // SALIR
                $noSalir = false;
                break;
            default:
            echo "\e[1;37;41mOpcion incorrecta!\e[0m\n";
        }
    }
}

/**
 * ////////// FUNCIONES VIAJE \\\\\\\\\\
 */

function seleccionarViaje(){
    $abmViaje = new abmViaje();

    do {
        echo $abmViaje->listarViajes();
        echo "Seleccione el ID del viaje: \n";
        $id = trim(fgets(STDIN));
        $objViaje = $abmViaje->traerViaje($id);

        if ($objViaje == null) {
            echo "\n\e[1;37;41mEl ID ingresado no corresponde a un viaje.\e[0m\n";
        }

    } while ($objViaje == null);
    return $objViaje;
}

function verViajes(){
    $abmViaje = new abmViaje();
    echo $abmViaje->listarViajes();
}

/**
 * Retorna un boolean si hay o no empresas cargadas.
 */
function hayEmpresa(){
    $abmEmpresa = new abmEmpresa();
    $rta = $abmEmpresa->checkEmpresa();
    return $rta;
}

/**
 * Retorna un boolean si hay o no Responsables cargados.
 */ 
function hayResponsable(){
    $abmResponsableV = new abmResponsableV();
    $rta = $abmResponsableV->checkResponsable();
    return $rta;
}

/**
 * Retorna un boolean si hay o no viajes
 */
function hayViaje(){
    $abmViaje = new abmViaje();
    $rta = $abmViaje->checkViaje();
    return $rta;
}

function hayPasajero(){
    $abmPasajero = new abmPasajero();
    $rta = $abmPasajero->checkPasajero();
    return $rta;
}

function mostrarPasajerosViaje(){
    $abmViaje = new abmViaje();
    $objViaje = seleccionarViaje();
    $id = $objViaje->getidviaje();

    echo $abmViaje->listarPasajerosViaje($id);
}

/**
 * Retorna un boolean si hay o no lugar en el viaje
 */
function hayLugar($id){
    $abmViaje = new abmViaje();
    $rta = $abmViaje->checkLugar($id);
    return $rta;
}

function borrarViaje(){
    $abmViaje = new abmViaje();
    $objViaje = seleccionarViaje();
    $viajeBorrado = $abmViaje->eliminarViaje($objViaje);

    if ($viajeBorrado) {
        echo "\e[1;37;42mEl viaje fue borrado\e[0m\n";
    }else {
        echo "\e[1;37;41mEl viaje tiene pasajeros cargados no se puede eliminar.\e[0m\n";
    }
}

function cargarViaje(){
    $abmViaje = new abmViaje();
    echo "Ingrese los datos del viaje: \n";

    do {//Comprueba que no haya viajes al mismo destino que se intenta cargar.
        echo "Destino: \n";
        $destino = trim(fgets(STDIN));
        $hayViajesMismoDestino = $abmViaje->viajeMismoDestino($destino);
        if ($hayViajesMismoDestino) {
            echo "\e[1;37;41mYa hay un viaje cargado a ese destino\e[0m\n";
        }
    } while ($hayViajesMismoDestino);

    echo "Cantidad máxima de pasajeros: \n";
    $cantMaxPasajeros = trim(fgets(STDIN));

    do {// Comprueba que el ID corresponda a una empresa cargada.
        echo "ID de la Empresa: \n";
        $idEmpresa = trim(fgets(STDIN));
        $abmEmpresa = new abmEmpresa();
        $objEmpresa = $abmEmpresa->traerEmpresa($idEmpresa);
        if ($objEmpresa == null) {
            echo "\e[1;37;41mEl ID ingresado no corresponde a ninguna empresa cargada.\e[0m\n";
        }
    } while ($objEmpresa == null);

    do {// Comprueba que el número ingresado corresponda a un empleado cargado.
        echo "Número de empleado responsable: \n";
        $nroResponsable = trim(fgets(STDIN));
        $abmResponsable = new abmResponsableV();
        $objResponsable = $abmResponsable->traerResponsable($nroResponsable);

        if ($objResponsable == null) {
            echo "\e[1;37;41mEl número ingresado no coresponde a ningún empleado cargado \e[0m\n";
        }
    } while ($objResponsable == null);

    echo "Importe: \n";
    $importe = trim(fgets(STDIN));

    echo "Tipo de asiento: (cama / samicama)\n";
    $tipoAsiento = trim(fgets(STDIN));

    echo "Ida y vuelta: (si / no)\n";
    $idaVuelta = trim(fgets(STDIN));

    $cargado = $abmViaje->agregarViaje($destino, $cantMaxPasajeros, $objEmpresa, $objResponsable, $importe, $tipoAsiento, $idaVuelta);

    if ($cargado) {
        echo "\n\e[1;37;42mEl viaje fue cargado\e[0m\n";
    } else {
        echo "\n\e[1;37;41mError al cargar el viaje\e[0m\n";
    }
}

function modificarViaje($viaje){
    echo "Ingrese el destino: \n";
    $destino = trim(fgets(STDIN));
    if (viajeMismoDestino($destino)) {
        echo "\e[1;37;41mYa existe un viaje a ese destino!\e[0m\n";
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
            echo "\e[1;37;42mEl viaje se modificó con éxito!\e[0m\n";
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
        echo "\n\e[1;37;42m-----  PASAJERO  -----\e[0m\n";
        echo
"\n1. Ver pasajeros\n
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
                    echo "\e[1;37;41mNo hay pasajeros cargados!\e[0m\n";
                }
                break;
            case '2': // CARGAR PASAJERO5
                    cargarPasajero();
                break;
            case '3': // MODIFICAR PASAJERO
                if(hayPasajero()){
                    
                    modificarPasajero();
                } else {
                    echo "\e[1;37;41mNo hay pasajeros cargados!\e[0m\n";
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
                        echo "\e[1;37;41mEl DNI ingresado no corresponde a ningún pasajero cargado.\e[0m\n";
                    }    
                }else {
                    echo "\e[1;37;41mNo hay pasajeros cargados.\e[0m\n";
                }
                break;
            case '5': // SALIR
                $noSalir = false;
                break;
            default:
                echo "\e[1;37;41mOpcion incorrecta!\e[0m\n";
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
            echo "\e[1;37;41mEl documento ingresado ya existe.\e[0m\n";
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
            echo "\e[1;37;41mEl ID ingresado no corresponde a ningún viaje.\e[0m\n";
        }else {
            $pasajeros = listadoPasajeros($idViaje);
            $cantMaxPasajeros = $viaje->getvcantmaxpasajeros();

            if (count($pasajeros) >= $cantMaxPasajeros) {
                echo "\e[1;37;41mEl viaje ya está completo. Elija otro.\e[0m\n";
                $check = false;
            }
        }
    } while (!$check);

    $pasajero->cargarDatos($dniPasajero, $nombre,$apellido,$telefono, $viaje);

    if ($pasajero->insertar()) {
        echo "\e[1;37;42mEl pasajero fue cargado con éxito!\e[0m\n";
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

    if($pasajero->buscar($dni)){

        if ($pasajero->getrdocumento() != ""){
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
                    echo "\e[1;37;41mNo hay lugar disponible en este viaje.\e[0m\n";
                }
            } else {
                echo "\e[1;37;41mEl ID ingresado no corresponde a ningún viaje cargado.\e[0m\n";
            }
        }
        if ($pasajero->modificar()) {
            echo "\e[1;37;42mEl pasajero se modificó con éxito!\e[0m\n";
        }else {
            echo "No se pudo modificar al pasajero.\n";
        }
    }else {
        echo "\e[1;37;41mEl dni ingresado no corresponde a ningun pasajero\e[0m\n";
    }
}

/**
 * /////  MENU OPCIONES RESPONSABLE  \\\\\
 */
function menuResponsable(){
    $noSalir = true;

    while ($noSalir) {
        echo "\n\e[1;37;42m-----  RESPONSABLE  -----\e[0m\n";
        echo
"\n1. Ver responsables\n
2. Cargar un responsable\n
3. Modificar responsable\n
4. Eliminar responsable\n
5. Volver al menú principal\n";

        $opcion = trim(fgets(STDIN));

        switch ($opcion) {
            case '1': // VER RESPONSABLES
                $responsable = new ResponsableV();
                $responsables = $responsable->listar("");
                if (count($responsables) > 0 ) {
                    strArray($responsables);
                } else {
                    echo "\e[1;37;41mNo hay responsables cargados\e[0m\n";
                }
                break;
            
            case '2': //CARGAR RESPONSABLE
                insertarResponsable();
                break;
            case '3': // MODIFICAR RESPONSABLE
                $responsable = new ResponsableV();
                if (hayResponsable()) {
                    echo "Ingrese el nº de empleado del responsable a modificar:\n";
                    $numEmpleado = trim(fgets(STDIN));

                    if ($responsable->buscar($numEmpleado)) {
                        echo "Ingrese nº de Licencia: \n";
                        $numLicencia = trim(fgets(STDIN));
                        echo "Nombre: \n";
                        $nombre = trim(fgets(STDIN));
                        echo "Apellido: \n";
                        $apellido = trim(fgets(STDIN));

                        $responsable->setrnumerolicencia($numLicencia);
                        $responsable->setrnombre($nombre);
                        $responsable->setrapellido($apellido);

                        if ($responsable->modificar()) {
                            echo "\e[1;37;42mEl responsable fue modificado con éxito.\e[0m\n";
                        } else {
                            echo "No se pudo modificar el responsable\n";
                        }

                    } else {
                        echo "\e[1;37;41mEl nº de empleado ingresado no corresponde a ningún responsable cargado.\e[0m\n";
                    }
                } else {
                    echo "\e[1;37;41mNo hay responsables cargados.\e[0m\n";
                }
                break;
            case '4': //ELIMINAR RESPONSABLE
                $responsable = new ResponsableV();
                if (hayresponsable()) {
                    echo "Ingrese el nº de empleado del responsable a eliminar.\n";
                    $numEmpleado = trim(fgets(STDIN));

                    if ($responsable->buscar($numEmpleado)) {
                        $viaje = new Viaje();
                        $condicion = 'rnumeroempleado = ' . $numEmpleado;
                        $viajesConResponsable = $viaje->listar($condicion);

                        if (count($viajesConResponsable) == 0) {
                            if ($responsable->eliminar()) {
                                echo "\e[1;37;42mSe eliminó con éxito.\e[0m\n";
                            } else {
                                echo "No se pudo eliminar el responsable\n";
                            }
                        }else {
                            echo "\e[1;37;41mNo se puede eliminar tiene viajes a cargo.\e[0m\n";
                        }
                    } else {
                        echo "\e[1;37;41mEl nº ingresado no corresponde a ningún responsable cargado.\e[0m\n";
                    }
                }
                break;
            case '5': // SALIR
                $noSalir = false;
                break;
            
            default:
                echo "\e[1;37;41mOpcion incorrecta.\e[0m\n";
                break;
        }
    }
}

function insertarResponsable(){
    $responsable = new ResponsableV();
    echo "Ingrese nº de Licencia: \n";
    $numLicencia = trim(fgets(STDIN));
    echo "Nombre: \n";
    $nombre = trim(fgets(STDIN));
    echo "Apellido: \n";
    $apellido = trim(fgets(STDIN));

    $responsable->cargarDatos($numLicencia, $nombre, $apellido);

    if ($responsable->insertar()) {
        echo "\e[1;37;42mResponsable insertado con exito!\e[0m\n";
    } else {
        echo "No se pudo insertar el responsable.\n";
    }
}


//----------------------------------------********************
//----------------------------------------********************
//----------------------------------------********************

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