<?php

class abmViaje {
    public function __construct()
    {
        
    }

    public function agregarViaje($destino, $cantMaxPasajeros, $idempresa, $responsable, $importe, $tipoasiento, $idayvuelta){
        $viajeAgregado = false;
        $viaje = new Viaje();
        $viaje->cargarDatos($destino, $cantMaxPasajeros, $idempresa, $responsable, $importe, $tipoasiento, $idayvuelta);

        if ($viaje->insertar()) {
            $viajeAgregado = true;
        }
        return $viajeAgregado;
    }

    public function listarViajes(){
        $objViaje = new Viaje();
        $coleccionViajes = $objViaje->listar();

        $datosViajes = "\n\e[1;37;42m--- Listado de Viajes: \e[0m\n";

        if (count($coleccionViajes) > 0) {
            foreach ($coleccionViajes as $viaje) {
                $datosViajes .= $viaje . "\n";
            }
        } else {
            $datosViajes .= "\n\e[1;37;41mNo hay viajes cargad0s.\e[0m\n";
        }
        return $datosViajes;
    }

    public function traerViaje($id){
        $viaje = new Viaje();
        $encontrado = $viaje->buscar($id);

        if (!$encontrado) {
            $viaje = null;
        }
        return $viaje;
    }

    public function listarPasajerosViaje($id){
        $pasajero = new Pasajero();
        $condicion = " idviaje = " . $id;
        $pasajeros = $pasajero->listar($condicion);

        $datosPasajeros = "\n\e[1;37;42mListado de pasajeros: \e[0m\n";

        if (count($pasajeros) > 0) {
            foreach ($pasajeros as $pasajerov) {
                $datosPasajeros .= $pasajerov . "\n";
            }
        }else {
            $datosPasajeros .= "\n\e[1;37;41mEl viaje no tiene pasajeros cargados.\e[0m\n";
        }
        return $datosPasajeros;
    }

    public function checkViaje(){
        $viaje = new Viaje();
        $arrayViajes = $viaje->listar();
        $viajesCargados = count($arrayViajes) > 0;
        return $viajesCargados;
    }

    public function checkLugar(){

    }

    public function viajeMismoDestino($destinoViaje){
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


}


?>