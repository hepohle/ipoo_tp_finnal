<?php

require_once('BaseDatos.php');
class Empresa
{
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idempresa = '';
        $this->enombre = '';
        $this->edireccion = '';
        $this->mensajeoperacion = '';
    }

    public function getIdempresa(){
        return $this->idempresa;
    }
    public function setIdempresa($idempresa){
        $this->idempresa = $idempresa;
    }
    public function getEnombre(){
        return $this->enombre;
    }
    public function setEnombre($enombre){
        $this->enombre = $enombre;
    }
    public function getEdireccion(){
        return $this->edireccion;
    }
    public function setEdireccion($edireccion){
        $this->edireccion = $edireccion;
    }
    public function getMensajeoperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeoperacion($mensajeoperacion){
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargarDatos($enombre,$edireccion){
        
        $this->enombre = $enombre;
        $this->edireccion = $edireccion;
    }

    public function __toString()
    {
        $str = "--- EMPRESA ---\n";
        $str .= "ID: " . $this->getIdempresa() . "\n";
        $str .= "Nombre: " . $this->getEnombre() . "\n";
        return $str;
    }

    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa (enombre, edireccion)
                            VALUES ('". $this->getEnombre() . "','" . $this->getEdireccion() . "')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaInsertar)) {
                $resp = true;    
            }else {
                $this->setMensajeoperacion($base->getError());
            }
        }else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaModifica = "UPDATE empresa SET enombre = '{$this->getEnombre()}', edireccion = '{$this->getEdireccion()}' WHERE idempresa = {$this->getIdempresa()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            }else {
                $this->setMensajeoperacion($base->getError());
            }
        }else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }
    

    public function eliminar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaElimina = "DELETE FROM empresa WHERE idempresa = {$this->getIdempresa()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaElimina)) {
                $resp = true;
            }else {
                $this->setMensajeoperacion($base->getError());
            }
        }else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function buscar($idempresa){
        $base = new BaseDatos();
        $consultaBusca = "SELECT * FROM empresa WHERE 'idempresa' = $idempresa";
        $resp= false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaBusca)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdempresa($row2['idempresa']);
                    $this->setEnombre($row2['enombre']);
                    $this->setEdireccion($row2['edireccion']);
                    $resp = true;
                }else {
                    $this->setMensajeoperacion($base->getError());
                }
            }else {
                $this->setMensajeoperacion($base->getError());
            }
        }
        return $resp;
    }

    public function listar($condicion = ''){
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaListar = "SELECT * FROM empresa";

        if ($condicion != '') {
            $consultaListar .= " WHERE " . $condicion;
        }

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaListar)) {
                $arregloEmpresa = array();

                while ($row2 = $base->Registro()) {
                    $idempresa = $row2['idempresa'];
                    $enombre = $row2['enombre'];
                    $edireccion = $row2['edireccion'];
                    $objEmpresa = new Empresa();
                    $objEmpresa->cargarDatos($idempresa,$enombre,$edireccion);
                    array_push($arregloEmpresa, $objEmpresa);
                }
            }else {
                $this->setMensajeoperacion($base->getError());
            }
        }else {
            $this->setMensajeoperacion($base->getError());
        }
        return $arregloEmpresa;
    }
}

?>