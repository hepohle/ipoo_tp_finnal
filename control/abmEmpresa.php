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

        foreach ($coleccionEmpresas as $empresa) {
            $datosEmpresas .= $empresa . "\n";
        }
        return $datosEmpresas;
    }

    public function traerEmpresa($id){
        $empresa = new Empresa();
        $empresaEncontrada = $empresa->buscar($id);

        if (!$empresaEncontrada) {
            $empresa = null;
        }
        return $empresa;
    }


    public function modificarDatosEmpresa($objEmpresa, $nombre, $direccion){
        $objEmpresa->setEnombre($nombre);
        $objEmpresa->setEdireccion($direccion);
        $modificado = $objEmpresa->modificar();

        return $modificado;
    }

    public function eliminarEmpresa($objEmpresa){
        $objEmpresa;

    }
}

?>