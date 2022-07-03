<?php
require_once('BaseDatos.php');
require_once('Empresa.php');
require_once('ResponsableV.php');

class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $idempresa; // Es un objeto empresa
    private $objresponsable; // Ed un objeto responsable
    private $vimporte;
    private $tipoasiento;
    private $idayvuelta;
    private $coleccionPasajeros;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idviaje = '';
        $this->vdestino = '';
        $this->vcantmaxpasajeros = '';
        $this->idempresa = '';
        $this->objresponsable = '';
        $this->vimporte = '';
        $this->tipoasiento = '';
        $this->idayvuelta = '';
        $this->coleccionPasajeros = '';
    }

    public function getidviaje(){
        return $this->idviaje;
    }
    public function getvdestino(){
        return $this->vdestino;
    }
    public function getvcantmaxpasajeros(){
        return $this->vcantmaxpasajeros;
    }
    public function getidempresa(){
        return $this->idempresa;
    }
    public function getobjresponsable(){
        return $this->objresponsable;
    }
    public function getvimporte(){
        return $this->vimporte;
    }
    public function gettipoasiento(){
        return $this->tipoasiento;
    }
    public function getidayvuelta(){
        return $this->idayvuelta;
    }
    public function getcoleccionPasajeros(){
        return $this->coleccionPasajeros;
    }
    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    public function setidviaje($idviaje){
        $this->idviaje = $idviaje;
    }
    public function setvdestino($vdestino){
        $this->vdestino = $vdestino;
    }
    public function setvcantmaxpasajeros($vcantmaxpasajeros){
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }
    public function setidempresa($idempresa){
        $this->idempresa = $idempresa;
    }
    public function setobjresponsable($objresponsable){
        $this->objresponsable = $objresponsable;
    }
    public function setvimporte($vimporte){
        $this->vimporte = $vimporte;
    }
    public function settipoasiento($tipoasiento){
        $this->tipoasiento = $tipoasiento;
    }
    public function setidayvuelta($idayvuelta){
        $this->idayvuelta = $idayvuelta;
    }
    public function setcoleccionPasajeros($coleccionPasajeros){
        $this->coleccionPasajeros = $coleccionPasajeros;
    }
    public function setmensajeoperacion($mensajeoperacion){
        $this->mensajeoperacion = $mensajeoperacion;
    }


    public function cargarDatos($idviaje, $vdestino, $vcantmaxpasajeros, $idempresa, $objresponsable, $vimporte, $tipoasiento, $idayvuelta){
        $this->setidviaje($idviaje);
        $this->setvdestino($vdestino);
        $this->setvcantmaxpasajeros($vcantmaxpasajeros);
        $this->setidempresa($idempresa);
        $this->setobjresponsable($objresponsable);
        $this->setvimporte($vimporte);
        $this->settipoasiento($tipoasiento);
        $this->setidayvuelta($idayvuelta);
    }

    public function __toString()
    {
        $empresa = $this->getidempresa();
        $empresaStr = $empresa->__toString();
        $responsable = $this->getobjresponsable();
        $responsableStr = $responsable->__toString();


        $str = "--- VIAJE ---\n";
        $str .= "ID: " . $this->getidviaje() . "\n";
        $str .= "Destino: " . $this->getvdestino() . "\n";
        $str .= "Cant max Asientos: " . $this->getvcantmaxpasajeros() . "\n";
        $str .= "Empresa: " . $empresaStr . "\n";
        $str .= "Responsable: " . $responsableStr . "\n";
        $str .= "Importe: $" . $this->getvimporte() . "\n";
        $str .= "Tipo de Asiento: " . $this->gettipoasiento() . "\n";
        $str .= "Ida y Vuelta: " . $this->getidayvuelta() . "\n";
        return $str;
    }

    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $objEmpresa = $this->getidempresa();
        $idEmpresa = $objEmpresa->getIdempresa();
        $objResponsable = $this->getobjresponsable();
        $numResponsable = $objResponsable->getrnumeroempleado();
        $consultaInsertar = "INSERT INTO viaje VALUES({$this->getvdestino()}', {$this->getvcantmaxpasajeros()}, $idEmpresa, $numResponsable, {$this->getvimporte()}, '{$this->gettipoasiento()}', '{$this->getidayvuelta()}')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaInsertar)) {
                $resp = true;
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $objEmpresa = $this->getidempresa();
        $idEmpresa = $objEmpresa->getIdempresa();
        $objResponsable = $this->getobjresponsable();
        $numResponsable = $objResponsable->getrnumeroempleado();
        $consultaModificar = "UPDATE viaje SET vdestino = '{$this->getvdestino()}', vcantmaxpasajeros = {$this->getvcantmaxpasajeros()}, idempresa = $idEmpresa, rnumeroempleado = $numResponsable, vimporte = {$this->getvimporte()}, tipoAsiento = '{$this->gettipoasiento()}', idayvuelta = '{$this->getidayvuelta()}' WHERE idviaje = {$this->getidviaje()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModificar)) {
                $resp = true;
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaEliminar = "DELETE FROM viaje WHERE idviaje = {$this->getidviaje()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEliminar)) {
                $resp = true;
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function buscar($idViaje){
        $base = new BaseDatos();
        $resp = false;
        $consultaBuscar = "SELECT * FROM viaje WHERE idviaje = $idViaje";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaBuscar)) {
                if ($row2 = $base->Registro()) {
                    $this->setidviaje($idViaje);
                    $this->setvdestino($row2['vdestino']);
                    $this->setvcantmaxpasajeros($row2['vcantmaxpasajeros']);
                    $this->setvimporte($row2['vimporte']);
                    $this->settipoasiento($row2['tipoAsiento']);
                    $this->setidayvuelta($row2['idayvuelta']);
                    
                    $objEmpresa = new Empresa();
                    if ($objEmpresa->buscar($row2['idempresa'])) {
                        $this->setidempresa($objEmpresa);
                    }else {
                        $this->setidempresa('');
                    }
                    $objResponsable = new ResponsableV();
                    if ($objResponsable->buscar($row2['rnumeroempleado'])) {
                        $this->setobjresponsable($objResponsable);
                    }else {
                        $this->setobjresponsable('');
                    }
                    $resp = true;
                }
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function listar($condicion = ''){
        $arregloViajes = null;
        $base = new BaseDatos();
        $consultaListar = "SELECT * FROM viaje ";
        if ($condicion != '') {
            $consultaListar .= " WHERE " . $condicion;
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaListar)) {
                $arregloViajes = [];
                while ($row2 = $base->Registro()) {
                    $idViaje = $row2['idviaje'];
                    $objViaje = new Viaje();
                    $objViaje->buscar($idViaje);
                    array_push($arregloViajes, $objViaje);
                }
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloViajes;
    }

}

?>