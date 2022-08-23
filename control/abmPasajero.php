<?php

class abmPasajero{
    public function __construct()
    {
        
    }

    public function checkPasajero(){
        $pasajero = new Pasajero();
        $arrayPasajeros = $pasajero->listar();
        $pasajerosCargados = count($arrayPasajeros) > 0;
        return $pasajerosCargados;

    }

}

?>