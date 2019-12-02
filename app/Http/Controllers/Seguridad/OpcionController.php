<?php
namespace App\Http\Controllers\Seguridad;

use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\OpcionBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;


class OpcionController extends Controller{
    
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $opBLL;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->opBLL = new OpcionBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Seguridad';
        $this->opcion = 'Opción';
    }
    
    /**
     * @param opcion (opcional)
     * @param idOpcion (opcional)
     * @param nombre (opcional)
     * @param ruta (opcional)
     * @param icono (opcional)
     * @param idEstado (opcional)
     *  */
    public function listar(){
        try {
                $this->et = devolverConsulta($this->opBLL->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

     /**
     * @param nombre (obligatorio)
     * @param ruta (obligatorio)
     * @param icono (obligatorio)
     * @param idEstado (obligatorio)
     **/

    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion'=> 'I'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->opBLL->insertarActualizar(null,$this->codUsuario));

        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param idOpcion (obligatorio)
     * @param nombre (obligatorio)
     * @param ruta (obligatorio)
     * @param icono (obligatorio)
     * @param idEstado (obligatorio)
     **/
    public function actualizar(Request $request, $idOpcion){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U','idOpcion' => $idOpcion],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->opBLL->insertarActualizar($idOpcion,$this->codUsuario));

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
                                'nombre'   => 'required|String|max:300',
                                'ruta'     => 'required|String|max:300',
                                'icono'    => 'required|String|max:100',
                                'idEstado' => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idOpcion' => 'required|Integer',
                                'nombre'   => 'required|String|max:300',
                                'ruta'     => 'required|String|max:300',
                                'icono'    => 'required|String|max:100',
                                'idEstado' => 'required|Integer'
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
