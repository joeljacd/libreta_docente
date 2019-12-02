<?php
namespace App\Http\Controllers\Seguridad;

use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\ModuloBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class ModuloController extends Controller{
    
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $moduBLL;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->moduBLL = new ModuloBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Seguridad';
        $this->opcion = 'Módulo'; 
    }

    /**
     * @param idModulo
     * @param idAgrupacion
     * @param nombre
     * @param icono
     * @param idEstado
    */
    public function listar(){
        try {
            $this->et = devolverConsulta($this->moduBLL->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param idAgrupacion
     * @param nombre
     * @param icono
     * @param idEstado
     * */
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->moduBLL->insertarActualizar(null,$this->codUsuario));

        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param idModulo
     * @param idAgrupacion
     * @param nombre
     * @param icono
     * @param idEstado
     * */
    public function actualizar(Request $request,$idModulo){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U', 'idModulo' => $idModulo],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->moduBLL->insertarActualizar($idModulo,$this->codUsuario));

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
                                'idAgrupacion'  => 'required|Integer',
                                'nombre'        => 'required|String|max:300',
                                'icono'         => 'required|String|max:100',
                                'idEstado'      => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idModulo'      => 'required|Integer',
                                'idAgrupacion'  => 'required|Integer',
                                'nombre'        => 'required|String|max:300',
                                'icono'         => 'required|String|max:100',
                                'idEstado'      => 'required|Integer'
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
