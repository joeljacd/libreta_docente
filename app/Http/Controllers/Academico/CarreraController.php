<?php
namespace App\Http\Controllers\Academico;

use App\AuthToken\JWToken;
use App\BusinessLayer\Academico\CarreraBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class CarreraController extends Controller{
    
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $carreBLL;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->carreBLL = new CarreraBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Académico';
        $this->opcion = 'Carrera';
    }

    /**
     * @param idCarrera (opcional)
     * @param codigo (opcional)
     * @param nombre (opcional)
     * @param abreviatura (opcional)
     * @param idEstado (opcional)
     **/

    public function listar(){
        try {
            $this->et = devolverConsulta($this->carreBLL->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param nombre (obligatorio)
     * @param codigo (opcional)
     * @param abreviatura (opcional)
     * @param idEstado (obligatorio)
     **/
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->carreBLL->insertarActualizar(null, $this->codUsuario));

        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }


    /**
     * @param idCarrera (obligatorio)
     * @param codigo (opcional)
     * @param nombre (obligatorio)
     * @param abreviatura (opcional)
     * @param idEstado (obligatorio)
     **/
    public function actualizar(Request $request,$idCarrera){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U', 'idCarrera' => $idCarrera],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->carreBLL->insertarActualizar($idCarrera, $this->codUsuario));

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
                                'idEstado'    => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idCarrera'   => 'required|Integer',
                                'nombre'      => 'required|String|max:300',
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
