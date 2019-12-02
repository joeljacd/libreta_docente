<?php
namespace App\Helpers;

class EstadoTransaccion
{
    public $clase                        = null;
    public $data                        = [];
    public $mensaje                     = "";
    public $error                       = false;
    public $status                      = 200;
    public static $codeOk               = 200;
    public static $codeBadRequest       = 400;
    public static $codeUnauthorized     = 401;
    public static $codeNotFound         = 404;
    public static $codeError            = 500;
    public static $noExistenToken       = "No se a encontrado el token.";
    public static $tokenInvalido        = "El token enviado no es válido.";
    public static $noExistenDatos       = "No existen datos con el criterio seleccionado";
    public static $procesoExitoso       = "Proceso ejecutado exitosamente";
    public static $procesoErroneo       = "Hubo un error, comuníquese con su administrador de sistemas";
    public static $registroYaExiste     = "No se puede crear, registro ya existe";
    public static $operacionNoPermitida = 'Operación no permitida';
    public static $consultaExitosa      = 'Consulta Exitosa';
    public static $peticionError        = 'No se encontro la petición a realizar.';
    public static $problemaInsertar     = 'No se pudo realizar la inserción.';
    public static $problemaActualizar   = 'No se pudo realizar la actualización.';
    public static $faltaParametros      = 'No se encontrarón los datos solicictados para continuar con la petición.';


    function __construct() 
    {
        //$this->data = new data();
    }
}
