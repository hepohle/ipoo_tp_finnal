<?php

class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $idempresa; // Es un objeto empresa
    private $objresponsable; // Es un objeto responsable
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
        $this->coleccionPasajeros = [];
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


    public function cargarDatos($vdestino, $vcantmaxpasajeros, $idempresa, $objresponsable, $vimporte, $tipoasiento, $idayvuelta){
        //$this->setidviaje($idviaje);
        $this->setvdestino($vdestino);
        $this->setvcantmaxpasajeros($vcantmaxpasajeros);
        $this->setidempresa($idempresa);
        $this->setobjresponsable($objresponsable);
        $this->setvimporte($vimporte);
        $this->settipoasiento($tipoasiento);
        $this->setidayvuelta($idayvuelta);
    }


    function arrColPasajeros(){
        $base = new BaseDatos();
        $resp = false;
        $condicion = "idviaje = " . $this->getidviaje();

        if ($base->Iniciar()) {
            $objPasajero = new Pasajero();
            $coleccionPasajeros = $objPasajero->listar($condicion);
            if (is_array($coleccionPasajeros)) {
                $this->setcoleccionPasajeros($coleccionPasajeros);
                $resp = true;
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function __toString()
    {
        $id_viaje = $this->getidviaje();
        $empresa = $this->getidempresa();
        $empresaStr = $empresa->__toString();
        $responsable = $this->getobjresponsable();
        $responsableStr = $responsable->__toString();

        $str = "\n--- VIAJE ---\n";
        $str .= "ID: " . $id_viaje . "\n";
        $str .= "Destino: " . $this->getvdestino() . "\n";
        $str .= "Cant max Asientos: " . $this->getvcantmaxpasajeros() . "\n";
        $str .= "Empresa: " . $empresaStr . "\n";
        $str .= "Responsable: " . $responsableStr . "\n";
        $str .= "Importe: $" . $this->getvimporte() . "\n";
        $str .= "Tipo de Asiento: " . $this->gettipoasiento() . "\n";
        $str .= "Pasajeros: " . $this->strArrPasajeros(). "\n";
        $str .= "Ida y Vuelta: " . $this->getidayvuelta() . "\n";
        return $str;
    }

    public function strArrPasajeros()
    {
        $objPasajeros = new pasajero();

        $condicion = " idviaje = " . $this->getIdViaje();
        $coleccionPasajeros = $objPasajeros->Listar($condicion);
        $retorno = "";

        foreach ($coleccionPasajeros as $pasajero) {
            $retorno .= $pasajero->__toString() . "\n-------------";
        }
        return $retorno;
    }

    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $objEmpresa = $this->getidempresa();
        $idEmpresa = $objEmpresa->getIdempresa();
        $objResponsable = $this->getobjresponsable();
        $numResponsable = $objResponsable->getrnumeroempleado();
        $consultaInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) VALUES('{$this->getvdestino()}', {$this->getvcantmaxpasajeros()}, {$idEmpresa}, {$numResponsable}, {$this->getvimporte()}, '{$this->gettipoasiento()}', '{$this->getidayvuelta()}')";
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
                    $objEmpresa->buscar($row2['idempresa']);
                    $this->setidempresa($objEmpresa);
                                            
                    $objResponsable = new ResponsableV();
                    $objResponsable->buscar($row2['rnumeroempleado']);
                    $this->setobjresponsable($objResponsable);
                    
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

    public static function listar($condicion = ''){
        $arregloViajes = null;
        $base = new BaseDatos();
        $consultaListar = "SELECT * FROM viaje ";
        if ($condicion != '') {
            $consultaListar .= " WHERE " . $condicion;
        }

        //$consultaListar .= " ORDER BY idempresa ";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaListar)) {
                $arregloViajes = array();

                while ($row2 = $base->Registro()) {
                    
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);

                    $arregloViajes[] = $objViaje;

                    // $idViaje = $row2['idviaje'];
                    // $vdestino = $row2['vdestino'];
                    // $cantMaxPasajeros = $row2['vcantmaxpasajeros'];
                    
                    // $objEmpresa = new Empresa();
                    // $idempresa = $row2['idempresa'];
                    // if ($objEmpresa->buscar($idempresa)) {
                        
                    // }else {
                    //     $objEmpresa = null;
                    // }

                    // $objResponsable = new ResponsableV();
                    // $numEmpleado = $row2['rnumeroempleado'];
                    // if ($objResponsable->buscar($numEmpleado)) {
                        
                    // }else {
                    //     $objResponsable = null;
                    // }
                    // $vimporte = $row2['vimporte'];
                    // $tipoAsiento = $row2['tipoAsiento'];
                    // $idayvuelta = $row2['idayvuelta'];

                    // $viaje = new Viaje();
                    // $viaje->cargarDatos($vdestino,$cantMaxPasajeros,$objEmpresa,$objResponsable,$vimporte,$tipoAsiento,$idayvuelta);
                    // array_push($arregloViajes, $viaje);
                }
            }else {
                Viaje::setmensajeoperacion($base->getError());
            }
        }else {
            Viaje::setmensajeoperacion($base->getError());
        }
        return $arregloViajes;
    }

}

?>