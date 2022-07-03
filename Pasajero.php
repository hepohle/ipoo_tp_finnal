<?php
require_once('BaseDatos.php');
require_once('Viaje.php');
class Pasajero
{
    private $rdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $objViaje;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->rdocumento = '';
        $this->pnombre = '';
        $this->papellido = '';
        $this->ptelefono = '';
        $this->objViaje = '';
        $this->mensajeoperacion = '';
    }

    public function getrdocumento()
    {
        return $this->rdocumento;
    }
    public function getpnombre()
    {
        return $this->pnombre;
    }
    public function getpapellido()
    {
        return $this->papellido;
    }
    public function getptelefono()
    {
        return $this->ptelefono;
    }
    public function getobjviaje(){
        return $this->objViaje;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }
    public function setrdocumento($rdocumento)
    {
        $this->rdocumento = $rdocumento;
    }
    public function setpnombre($pnombre)
    {
        $this->pnombre = $pnombre;
    }
    public function setapellido($papellido)
    {
        $this->papellido = $papellido;
    }
    public function setptelefono($ptelefono)
    {
        $this->ptelefono = $ptelefono;
    }
    public function setobjviaje($objViaje){
        $this->objViaje = $objViaje;
    }
    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargarDatos($rdocumento, $pnombre, $papellido, $ptelefono, $objViaje){
        $this->rdocumento = $rdocumento;
        $this->pnombre = $pnombre;
        $this->papellido = $papellido;
        $this->ptelefono = $ptelefono;
        $this->objViaje = $objViaje;
    }

    public function __toString()
    {
        $str = "--- PASAJERO ---\n";
        $str .= "Documento: " . $this->getrdocumento() . "\n";
        $str .= "Nombre: " . $this->getpnombre() . "\n";
        $str .= "Apellido: " . $this->getpapellido() . "\n";
        $str .= "Telefono: " . $this->getptelefono() . "\n";
        return $str;
    }

    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero VALUES (". $this->getrdocumento() . ",'" . $this->getpnombre() . "','" . $this->getpapellido() . "'," . $this->getptelefono() . ",". $this->getobjviaje()->getidviaje().")";
        if ($base->Iniciar()) {
            
            if ($base->Ejecutar($consultaInsertar)) {
                $resp = true;
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaModifica = "UPDATE pasajero SET rdocumento = '" . $this->getrdocumento() . "', 
            pnombre = '" . $this->getpnombre() . "',
            papellido = '" . $this->getpapellido() . "',
            ptelefono = '" . $this->getptelefono() . "',
            idviaje = " . $this->getobjviaje()->getidviaje() ." WHERE pdocumento = " . $this->getrdocumento();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
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
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM pasajero WHERE rdocumento=" . $this->getrdocumento();
            if($base->Ejecutar($consultaBorra)){
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function listar($condicion="")
    {
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero ";
        if ($condicion!="") {
            $consultaPasajero = $consultaPasajero.' WHERE ' . $condicion;
        }

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajero = array();
                while ($row2 = $base->Registro()) {
                    $rdocumento = $row2['rdocumento'];
                    $pnombre = $row2['pnombre'];
                    $papellido = $row2['papellido'];
                    $ptelefono = $row2['ptelefono'];

                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    $this->setobjviaje($objViaje);
                    
                    $objPasajero = new Pasajero;
                    $objPasajero->cargarDatos($rdocumento,$pnombre,$papellido,$ptelefono,$objViaje); 
                    
                    array_push($arregloPasajero, $objPasajero);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloPasajero;
    }

    public function buscar($rdocumento){
        $base = new BaseDatos();
        $resp= false;
        $consultaBuscar = "SELECT * FROM pasajero WHERE 'rdocumento' = " . $rdocumento;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaBuscar)) {
                if ($row2 = $base->Registro()) {
                    $this->setrdocumento($row2['rdocumento']);
                    $this->setpnombre($row2['pnombre']);
                    $this->setapellido($row2['papellido']);
                    $this->setptelefono($row2['ptelefono']);
                    
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    $this->setobjviaje($objViaje);
                    
                    $resp=true;
                }
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

}

?>