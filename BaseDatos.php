<?php
/* IMPORTANTE !!!!  Clase para (PHP 5, PHP 7)*/

class BaseDatos {
    private $HOSTNAME;
    private $BASEDATOS;
    private $USUARIO;
    private $CLAVE;
    private $CONEXION;
    private $QUERY;
    private $RESULT;
    private $ERROR;
    /**
     * Constructor de la clase que inicia ls variables instancias de la clase
     * vinculadas a la coneccion con el Servidor de BD
     */
    public function __construct(){
        $this->HOSTNAME = "127.0.0.1";
        $this->BASEDATOS = "bdviajes";
        $this->USUARIO = "root";
        $this->CLAVE="";
        $this->CONEXION;
        $this->QUERY="";
        $this->RESULT= 0;
        $this->ERROR="";
    }
   
    /**
     * Obtiene el valor de HOSTNAME
     */ 
    public function getHOSTNAME(){
        return $this->HOSTNAME;
    }

    /**
     * Obtiene el valor de BASEDATOS
     */ 
    public function getBASEDATOS(){
        return $this->BASEDATOS;
    }

    /**
     * Obtiene el valor de USUARIO
     */ 
    public function getUSUARIO(){
        return $this->USUARIO;
    }

    /**
     * Obtiene el valor de CLAVE
     */ 
    public function getCLAVE(){
        return $this->CLAVE;
    }

    /**
     * Obtiene el valor de CONEXION
     */ 
    public function getCONEXION(){
        return $this->CONEXION;
    }

    /**
     * Obtiene el valor de QUERY
     */ 
    public function getQUERY(){
        return $this->QUERY;
    }

    /**
     * Obtiene el valor de RESULT
     */ 
    public function getRESULT(){
        return $this->RESULT;
    }

    /**
     * Obtiene el valor de ERROR
     */ 
    public function getERROR(){
        return $this->ERROR;
    }
    
      /**
     * Establece el valor de HOSTNAME
     */ 
    public function setHOSTNAME($HOSTNAME){
        $this->HOSTNAME = $HOSTNAME;
    }

    /**
     * Establece el valor de BASEDATOS
     */ 
    public function setBASEDATOS($BASEDATOS){
        $this->BASEDATOS = $BASEDATOS;
    }

    /**
     * Establece el valor de USUARIO
     */ 
    public function setUSUARIO($USUARIO){
        $this->USUARIO = $USUARIO;
    }

    /**
     * Establece el valor de CLAVE
     */ 
    public function setCLAVE($CLAVE){
        $this->CLAVE = $CLAVE;
    }

    /**
     * Establece el valor de QUERY
     */ 
    public function setQUERY($QUERY){
        $this->QUERY = $QUERY;
    }

    /**
     * Establece el valor de CONEXION
     */ 
    public function setCONEXION($CONEXION){
        $this->CONEXION = $CONEXION;
    }

    /**
     * Establece el valor de RESULT
     */ 
    public function setRESULT($RESULT){
        $this->RESULT = $RESULT;
    }

    /**
     * Establece el valor de ERROR
     */ 
    public function setERROR($ERROR){
        $this->ERROR = $ERROR;
    }
    /**
     * Inicia la coneccion con el Servidor y la  Base Datos Mysql.
     * Retorna true si la coneccion con el servidor se pudo establecer y false en caso contrario
     *
     * @return boolean
     */
    public function Iniciar(){
        $resp  = false;
        $conexion = mysqli_connect($this->getHOSTNAME(),$this->getUSUARIO(),$this->getCLAVE(),$this->getBASEDATOS());
        if ($conexion){
            if (mysqli_select_db($conexion,$this->getBASEDATOS())){
                $this->setCONEXION($conexion);
                unset($this->QUERY);
                unset($this->ERROR);
                $resp = true;
            }  else {
                $this->setERROR(mysqli_errno($conexion) . ": " .mysqli_error($conexion));
            }
        }else{
            $this->setERROR(mysqli_errno($conexion) . ": " .mysqli_error($conexion));
        }
        return $resp;
    }
    
    /**
     * Ejecuta una consulta en la Base de Datos.
     * Recibe la consulta en una cadena enviada por parametro.
     *
     * @param string $consulta
     * @return boolean
     */
    public function Ejecutar($consulta){
        $resp  = false;
        unset($this->ERROR);
        $this->setQUERY($consulta);
        $this->setRESULT(mysqli_query( $this->getCONEXION(),$consulta));
        if($this->getRESULT()){
            $resp = true;
        } else {
            $this->setERROR(mysqli_errno( $this->getCONEXION()).": ". mysqli_error( $this->getCONEXION()));
        }
        return $resp;
    }
    
    /**
     * Devuelve un registro retornado por la ejecucion de una consulta
     * el puntero se despleza al siguiente registro de la consulta
     *
     * @return boolean
     */
    public function Registro() {
        $resp = null;
        if ($this->getRESULT()){
            unset($this->ERROR);
            if($temp = mysqli_fetch_assoc($this->getRESULT())){
                $resp = $temp;
            }else{
                mysqli_free_result($this->getRESULT());
            }
        }else{
            $this->setERROR(mysqli_errno($this->getCONEXION()) . ": " . mysqli_error($this->getCONEXION()));
        }
        return $resp ;
    }
    
    /**
     * Devuelve el id de un campo autoincrement utilizado como clave de una tabla
     * Retorna el id numerico del registro insertado, devuelve null en caso que la ejecucion de la consulta falle
     *
     * @param string $consulta
     * @return int id de la tupla insertada
     */
    public function devuelveIDInsercion($consulta){
        $resp = null;
        unset($this->ERROR);
        $this->QUERY = $consulta;
        if ($this->setRESULT(mysqli_query($this->getCONEXION(), $consulta))){
            $id = mysqli_insert_id();
            $resp =  $id;
        } else {
            $this->setERROR(mysqli_errno( $this->getCONEXION()) . ": " . mysqli_error( $this->getCONEXION()));
           
        }
    return $resp;
    }
    
}
?>