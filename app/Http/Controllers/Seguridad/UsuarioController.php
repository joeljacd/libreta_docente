<?php
namespace App\Http\Controllers\Seguridad;

use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\UsuarioBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class UsuarioController extends Controller{
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $usuBll;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->usuBll = new UsuarioBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Seguridad';
        $this->opcion = 'Usuario';    
    }

    /**
     * @param idUsuario (opcional)
     * @param idPersona (opcional)
     * @param idEstado (opcional)
     */
    public function listar(){
        try {
            $this->et = devolverConsulta($this->usuBll->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     *@param idPersona (obligatorio) 
     *@param clave (obligatorio) 
     *@param token (opcional) 
     *@param idEstado (obligatorio) 
     * */
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));
            if (!$validacion->error) 
                $this->et = devolverConsulta($this->usuBll->insertarActualizar(null, $this->codUsuario));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(),true,$e->getCode());
        }

        return response()->json($this->et,$this->et->status);
    }


    /**
     *@param idUsuario (obligatorio) 
     *@param idPersona (obligatorio) 
     *@param clave (obligatorio) 
     *@param token (opcional) 
     *@param idEstado (obligatorio) 
     * */
    public function actualizar(Request $request,$idUsuario){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge($request,['opcion' => 'U','idUsuario' => $idUsuario]));
            if (!$validacion->error) 
                $this->et = devolverConsulta($this->usuBll->insertarActualizar($idUsuario, $this->codUsuario));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(),true,$e->getCode());
        }

        return response()->json($this->et,$this->et->status);
    }

    public function validarData($request){
        try {
            $reglas = [];
            switch (strtoupper($request['opcion'])) {
                case 'I':
                    $reglas = [
                                'idPersona' => 'required|Integer',
                                'clave'     => 'required|String|max:300',
                                'idEstado'  => 'required|Integer',
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idUsuario' => 'required|Integer',
                                'idPersona' => 'required|Integer',
                                'clave'     => 'required|String|max:300',
                                'idEstado'  => 'required|Integer',
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
