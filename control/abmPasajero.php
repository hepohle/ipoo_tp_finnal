<?php

class abmPasajero{
    public function __construct()
    {
        
    }

    public function listarPasajeros(){
        $objPasajero = new Pasajero();
        $pasajeros = $objPasajero->listar();

        $datosPasajeros = "\n\e[1;37;42mListado de Pasajeros: \e[0m\n";

        if (count($pasajeros) > 0) {
            foreach ($pasajeros as $pasajero) {
                $datosPasajeros .= $pasajero . "\n";
            }
        }else {
            $datosPasajeros .= "\n\e[1;37;41mNo hay pasajeros cargados.\e[0m\n";
        }
        return $datosPasajeros;
    }

    public function checkPasajero(){
        $pasajero = new Pasajero();
        $arrayPasajeros = $pasajero->listar();
        $pasajerosCargados = count($arrayPasajeros) > 0;
        return $pasajerosCargados;

    }

}

?>