<?php
namespace App\Http\Controllers\Seguridad;

use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\TRolModuloOpcionBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class TRolModuloOpcionController extends Controller{
    
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $rolModOpRepo;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->rolModOpRepo = new TRolModuloOpcionBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Seguridad';
        $this->opcion = 'Rol Módulo Opción';
    }

     /**
     * @param idSec (opcional)
     * @param idRol (opcional)
     * @param idModuloOpcion (opcional)
     * @param idModulo (opcional)
     * @param idOpcion (opcional)
     * @param idEstado (opcional)
     * */

    public function listar(){
        try {
            $this->et = devolverConsulta($this->rolModOpRepo->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param idRol (obligatorio)
     * @param idModuloOpcion (obligatorio)
     * @param idEstado (obligatorio)
     * */
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->rolModOpRepo->insertarActualizar(null, $this->codUsuario));

        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }


    /**
     * @param idSec (obligatorio)
     * @param idRol (obligatorio)
     * @param idModuloOpcion (obligatorio)
     * @param idEstado (obligatorio)
     * */ 
    public function actualizar(Request $request,$idSec){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U', 'idSec' => $idSec],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->rolModOpRepo->insertarActualizar($idSec, $this->codUsuario));

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
                                'idRol'          => 'required|Integer',
                                'idModuloOpcion' => 'required|Integer',
                                'idEstado'       => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idSec'          => 'required|Integer',
                                'idRol'          => 'required|Integer',
                                'idModuloOpcion' => 'required|Integer',
                                'idEstado'       => 'required|Integer'
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
