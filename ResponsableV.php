<?php

class ResponsableV
{
    private $rnumeroempleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;
    private $mensajeOperacion;

    public function _construct()
    {
        $this->rnumeroempleado = '';
        $this->rnumerolicencia = '';
        $this->rnombre = '';
        $this->rapellido = '';
    }

    public function cargarDatos($rnumerolicencia, $rnombre, $rapellido){
        //$this->rnumeroempleado = $rnumeroempleado;
        $this->rnumerolicencia = $rnumerolicencia;
        $this->rnombre = $rnombre;
        $this->rapellido = $rapellido;
    }

    public function getrnumeroempleado()
    {
        return $this->rnumeroempleado;
    }
    public function setrnumeroempleado($rnumeroempleado)
    {
        $this->rnumeroempleado = $rnumeroempleado;
    }

    public function getrnumerolicencia()
    {
        return $this->rnumerolicencia;
    }
    public function setrnumerolicencia($rnumerolicencia)
    {
        $this->rnumerolicencia = $rnumerolicencia;
    }

    public function getrnombre()
    {
        return $this->rnombre;
    }
    public function setrnombre($rnombre)
    {
        $this->rnombre = $rnombre;
    }

    public function getrapellido()
    {
        return $this->rapellido;
    }
    public function setrapellido($rapellido)
    {
        $this->rapellido = $rapellido;
    }

    public function getmensaoperacion(){
        return $this->mensajeOperacion;
    }
    public function setmensajeoperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }

    public function __toString()
    {
        $str = "\n--- RESPONSABLE ---\n";
        $str .= "Nº Empleado: " . $this->getrnumeroempleado() . "\n";
        $str .= "Nº Licencia: " . $this->getrnumerolicencia() . "\n";
        $str .= "Nombre: " . $this->getrnombre() . "\n";
        $str .= "Apellido: " . $this->getrapellido() . "\n";
        return $str;
    }

    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO responsable VALUES ({$this->getrnumeroempleado()}, {$this->getrnumerolicencia()}, {$this->getrnombre()}, {$this->getrapellido()})";

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
        $base = new BaseDatos();
        $resp = false;
        $consultaModifica = "UPDATE responsable SET rnumerolicencia = {$this->getrnumerolicencia()}, rnombre = '{$this->getrnombre()}', rapellido = '{$this->getrapellido()}' WHERE rnumeroempleado = {$this->getrnumeroempleado()}";
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
        $consultaElimina = "DELETE FROM responsable WHERE rnumeroempleado = {$this->getrnumeroempleado()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaElimina)) {
                $resp = true;
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function listar($condicion = ''){
        $arregloResponsable = null;
        $base = new BaseDatos;
        $consultaListar = "SELECT * FROM responsable";
        if ($condicion != '') {
            $consultaListar = $consultaListar . ' WHERE ' . $condicion;
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaListar)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {
                    $rnumeroempleado = $row2['rnumeroempleado'];
                    $rnumerolicencia = $row2['rnumerolicencia'];
                    $rnombre = $row2['rnombre'];
                    $rapellido = $row2['rapellido'];

                    $responsable = new ResponsableV();
                    $responsable->cargarDatos($rnumeroempleado, $rnumerolicencia, $rnombre, $rapellido);
                    array_push($arregloResponsable, $responsable);
                }
            }else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloResponsable;
    }

    public function buscar($rnumeroempleado){
        $base = new BaseDatos();
        $resp = false;
        $consultaBuscar = "SELECT * FROM responsable WHERE rnumeroempleado = $rnumeroempleado";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaBuscar)) {
                if ($row2 = $base->Registro()) {
                    $this->setrnumeroempleado($row2['rnumeroempleado']);
                    $this->setrnumerolicencia($row2['rnumerolicencia']);
                    $this->setrnombre($row2['rnombre']);
                    $this->setrapellido($row2['rapellido']);
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
}

?>