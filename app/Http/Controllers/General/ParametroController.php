<?php
namespace App\Http\Controllers\General;

use App\AuthToken\JWToken;
use App\BusinessLayer\General\ParametroBLL;
use App\Helpers\EstadoTransaccion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ParametroController extends Controller{

    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $paraBll;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->paraBll = new ParametroBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'General';
        $this->opcion = 'Parametro';
    }

    public function listar($codParametro = null){
        try {
            $this->et = devolverConsulta($this->paraBll->listar($codParametro));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     *@param opcion (obligatorio) 
     *@param codParametro (obligatorio) 
     *@param descripcion (opcional) 
     *@param valorNumerico (opcional) 
     *@param valorTexto (opcional) 
     *@param idEstado (obligatorio) 
     * */
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            if(!isset($request['opcion'])) throw new \Exception(EstadoTransaccion::$peticionError,EstadoTransaccion::$codeUnauthorized);
                $validacion = $this->validarData($request);
                if (!$validacion->error) 
                    $this->et = devolverConsulta($this->paraBll->insertarActualizar(null, $this->codUsuario));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(),true,$e->getCode());
        }

        return response()->json($this->et,$this->et->status);
    }

    /**
     *@param opcion (obligatorio) 
     *@param idSecParametro (obligatorio) 
     *@param codParametro (obligatorio) 
     *@param descripcion (opcional) 
     *@param valorNumerico (opcional) 
     *@param valorTexto (opcional) 
     *@param idEstado (obligatorio) 
     * */
    public function actualizar(Request $request, $idSecParametro){
        try {
            $request = json_decode($request->getContent(),true);
            if (!isset($request['opcion'])) throw new \Exception(EstadoTransaccion::$peticionError, EstadoTransaccion::$codeUnauthorized);
                $validacion = $this->validarData(array_merge($request, ['idSecParametro' => $idSecParametro]));
                if(!$validacion->error)
                    $this->et = devolverConsulta($this->paraBll->insertarActualizar($idSecParametro,$this->codUsuario));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }

        return response()->json($this->et, $this->et->status);
    }

    public function validarData($request){
        try {
            $reglas = [];
            switch (strtoupper($request['opcion'])) {
                case 'I':
                    $reglas = [
                                'codParametro' => 'required|String|max:50'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idSecParametro' => 'required|Integer',
                                'codParametro'   => 'required|String|max:50'
                              ];
                    break;
                default:
                    $this->et = devolverError(get_class($this), EstadoTransaccion::$peticionError, true, EstadoTransaccion::$codeNotFound);
                    return $this->et;
                    break;
                }
                $validacion = Validator::make($request,$reglas);
                
                if($validacion->fails())
                    $this->et = devolverValidacion($validacion->messages());

                return $this->et;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->validarData : ' . $e->getMessage(), EstadoTransaccion::$codeError);
        }
    }
}
