<?php
namespace App\Http\Controllers\Seguridad;

use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\TRolUsuarioBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class TRolUsuarioController extends Controller{
   
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $rolUsuBll;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->rolUsuBll = new TRolUsuarioBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Seguridad';
        $this->opcion = 'Rol por Usuario';
    }

    /** 
     * @param idSecRolUsuario (opcional)
     * @param idRol (opcional)
     * @param idUsuario (opcional)
     * @param idEstado (opcional)
    **/
    public function listar(){
        try {
            $this->et = devolverConsulta($this->rolUsuBll->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     *@param idUsuario (obligatorio) 
     *@param idRol (opcional) 
     *@param idEstado (obligatorio) 
     * */
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));
            if (!$validacion->error) 
                $this->et = devolverConsulta($this->rolUsuBll->insertarActualizar(null, $this->codUsuario));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(),true,$e->getCode());
        }

        return response()->json($this->et,$this->et->status);
    }

    /**
     *@param idSecRolUsuario (obligatorio) 
     *@param idUsuario (obligatorio) 
     *@param idRol (opcional) 
     *@param idEstado (obligatorio) 
     * */
    public function actualizar(Request $request, $idSecRolUsuario){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge($request, ['opcion' => 'U','idSecRolUsuario' => $idSecRolUsuario]));
            if(!$validacion->error)
                $this->et = devolverConsulta($this->rolUsuBll->insertarActualizar($idSecRolUsuario,$this->codUsuario));
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
                                'idUsuario' => 'required|Integer',
                                'idRol'     => 'required|Integer',
                                'idEstado'  => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idSecRolUsuario' => 'required|Integer',
                                'idUsuario'       => 'required|Integer',
                                'idRol'           => 'required|Integer',
                                'idEstado'        => 'required|Integer'
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
