<?php
namespace App\Http\Controllers\Seguridad;

use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\RolBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class RolController extends Controller{
    
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $rolBLL;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->rolBLL = new RolBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Seguridad';
        $this->opcion = 'Rol';
    }

    /**
     * @param idRol (opcional)
     * @param rol (opcional)
     * @param idEstado (opcional)
     * */

    public function listar(){
        try {
            $this->et = devolverConsulta($this->rolBLL->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param nombre (obligatorio)
     * @param abreviatura (obligatorio)
     * @param idEstado (obligatorio)
     **/ 
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->rolBLL->insertarActualizar(null, $this->codUsuario));

        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }


    /**
     * @param idRol (obligatorio)
     * @param nombre (obligatorio)
     * @param abreviatura (obligatorio)
     * @param idEstado (obligatorio)
     **/ 
    public function actualizar(Request $request,$idRol){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U', 'idRol' => $idRol],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->rolBLL->insertarActualizar($idRol, $this->codUsuario));

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
                                'nombre'      => 'required|String|max:300',
                                'abreviatura' => 'required|String|max:20',
                                'idEstado'    => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idRol'       => 'required|Integer',
                                'nombre'      => 'required|String|max:300',
                                'abreviatura' => 'required|String|max:20',
                                'idEstado'    => 'required|Integer'
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
