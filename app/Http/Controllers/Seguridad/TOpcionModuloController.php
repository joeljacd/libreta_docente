<?php
namespace App\Http\Controllers\Seguridad;

use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\TOpcionModuloBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class TOpcionModuloController extends Controller{
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $opMoBLL;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->opMoBLL = new TOpcionModuloBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Seguridad';
        $this->opcion = 'Opción por Módulo';
    }

    /**
     * @param idSecModuloOpcion (opcional)
     * @param idModulo (opcional)
     * @param idOpcion (opcional)
     * @param idEstado (opcional)
     **/
    public function listar(){
        try {
            $this->et = devolverConsulta($this->opMoBLL->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param idModulo (obligatorio)
     * @param idOpcion (obligatorio)
     * @param idEstado (obligatorio)
     **/ 
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->opMoBLL->insertarActualizar(null, $this->codUsuario));

        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }


    /**
     * @param idSecModuloOpcion (obligatorio)
     * @param idModulo (obligatorio)
     * @param idOpcion (obligatorio)
     * @param idEstado (obligatorio)
     **/ 
    public function actualizar(Request $request,$idSecModuloOpcion){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U', 'idSecModuloOpcion' => $idSecModuloOpcion],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->opMoBLL->insertarActualizar($idSecModuloOpcion, $this->codUsuario));

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
                                'idModulo' => 'required|Integer',
                                'idOpcion' => 'required|Integer',
                                'idEstado' => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idSecModuloOpcion'  => 'required|Integer',
                                'idModulo'           => 'required|Integer',
                                'idOpcion'           => 'required|Integer',
                                'idEstado'           => 'required|Integer'
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
