<?php

class abmResponsableV{
    public function __construct()
    {
        
    }

    public function checkResponsable(){
        $responsable = new ResponsableV();
        $arrayResponsables = $responsable->listar();
        $responsablesCargados = count($arrayResponsables) > 0;
        return $responsablesCargados;
    }

    public function traerResponsable($nro){
        $responsable = new ResponsableV();
        $encontrado = $responsable->buscar($nro);

        if (!$encontrado) {
            $responsable = null;
        }
        return $responsable;
    }
}


?>