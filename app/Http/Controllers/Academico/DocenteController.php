<?php
namespace App\Http\Controllers\Academico;

use App\AuthToken\JWToken;
use App\BusinessLayer\Academico\DocenteBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class DocenteController extends Controller{
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $doceBLL;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->doceBLL = new DocenteBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Académico';
        $this->opcion = 'Docente';
    }

    /**
     * @param idDocente (opcional)
     * @param idPersona (opcional)
     * @param idEstado (opcional)
     * */

    public function listar(){
        try {
            $this->et = devolverConsulta($this->doceBLL->listar());
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param idPersona (obligatorio)
     * @param idEstado (obligatorio)
     **/ 
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->doceBLL->insertarActualizar(null, $this->codUsuario));

        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }


    /**
     * @param idDocente (obligatorio)
     * @param idPersona (obligatorio)
     * @param idEstado (obligatorio)
     **/
    public function actualizar(Request $request,$idDocente){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U', 'idDocente' => $idDocente],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->doceBLL->insertarActualizar($idDocente, $this->codUsuario));

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
                                'idPersona'   => 'required|Integer',
                                'titulo'      => 'required|String|max:255',
                                'idEstado'    => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idDocente'   => 'required|Integer',
                                'idPersona'   => 'required|Integer',
                                'titulo'      => 'required|String|max:255',
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
