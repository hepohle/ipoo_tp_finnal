<?php

class abmResponsableV{
    public function __construct()
    {
        
    }

    public function listarResponsables(){
        $objResponsable = new ResponsableV();
        $responsables = $objResponsable->listar();

        $datosResponsables = "\n\e[1;37;42mListado de Responsables: \e[0m\n";

        if (count($responsables) > 0) {
            foreach ($responsables as $responsable) {
                $datosResponsables .= $responsable . "\n";
            }
        }else {
            $datosResponsables .= "\n\e[1;37;41mNo hay responsables cargados.\e[0m\n";
        }
        return $datosResponsables;
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