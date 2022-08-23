<?php

class abmEmpresa{

    public function __construct()
    {
        
    }

    public function agregarEmpresa($nombre, $direccion){
        $empresaAgregada = false;
        $empresa = new Empresa();
        $empresa->cargarDatos($nombre, $direccion);

        if ($empresa->insertar()) {
            $empresaAgregada = true;
        }
        return $empresaAgregada;
    }

    public function listarEmpresas(){
        $objEmpresa = new Empresa();
        $coleccionEmpresas = $objEmpresa->listar();

        $datosEmpresas = "\n\e[1;37;42mEmpresas: \e[0m\n";

        if (count($coleccionEmpresas) > 0) {
            foreach ($coleccionEmpresas as $empresa) {
                $datosEmpresas .= $empresa . "\n";
            }    
        } else {
            $datosEmpresas .= "\n\e[1;37;41mNo hay empresas cargadas.\e[0m\n";
        }
        return $datosEmpresas;
    }

    public function traerEmpresa($id){
        $empresa = new Empresa();
        $encontrado = $empresa->buscar($id);
        $empresa->getColeccionViajes();

        if (!$encontrado) {
            $empresa = null;
        }
        return $empresa;
    }

    public function listarViajesEmpresa($id){
        $viaje = new Viaje();
        $condicion = " idempresa = " . $id;
        $viajes = $viaje->listar($condicion);

        $datosViajes = "\n\e[1;37;42mViajes: \e[0m\n";
        
        if (count($viajes) > 0) {
            foreach ($viajes as $viaje) {
                $datosViajes .= $viaje . "\n";
            }
        } else {
            $datosViajes .= "\n\e[1;37;41mLa empresa no tiene viajes cargados.\e[0m\n";
        }
        return $datosViajes;

    }

    public function modificarDatosEmpresa($objEmpresa, $nombre, $direccion){
        $objEmpresa->setEnombre($nombre);
        $objEmpresa->setEdireccion($direccion);
        $modificado = $objEmpresa->modificar();

        return $modificado;
    }

    public function eliminarEmpresa($objEmpresa){
        $id = $objEmpresa->getIdempresa();

        $viaje = new Viaje();
        $condicion = " idempresa = " . $id;
        $viajes = $viaje->listar($condicion);

        $rtaEliminar = false;

        if (count($viajes) == 0) {
            $objEmpresa->eliminar();
            $rtaEliminar = true;
        }
        return $rtaEliminar;
    }
}

?>