<?php

namespace App\Http\Controllers\Mantenimiento\Academico;

use App\Http\Controllers\Controller;
use App\Helpers\EstadoTransaccion;
use App\AuthToken\JWToken;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use App\BusinessLayer\Mantenimiento\Academico\Asignatura;

class AsignaturaController  extends Controller{
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $asigBll;

    public function __construct(Request $request)
    {
        $this->et = new EstadoTransaccion();
        $this->asigBll = new Asignatura($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Mantenimiento';
        $this->opcion = 'Acádemico';
    }

    public function index(){
        try {
            $this->et->data = $this->asigBll->index($this->codUsuario);
            $this->et->mensaje = EstadoTransaccion::$procesoExitoso;
        } catch (\Exception $e) {
            $this->et->mensaje = 'Error: ' . get_class($this) . '->index : ' . $e->getMessage();
            $this->et->existeError = true;
        }

        return response()->json($this->et);
    }

    /**
     * @param evento (obligatorio)
      */
    public function listar(Request $request){
        try {
            $request =  json_decode($request->getContent(),true);

            $this->et =  $this->validarData($request['evento'],$request);

            if (!$this->et->existeError) {
                $this->et = devolverConsulta($this->asigBll->listar());
            }
        } catch (\Exception $e) {
            $this->et->mensaje = 'Error: ' . get_class($this) . '->listar : ' . $e->getMessage();
            $this->et->existeError = true;
        }

        return response()->json($this->et);
    }

    /**
    *@param evento (obligatorio)
    *@param idAsignatura (obligatorio para evento U)
    *@param descripcion (opcional)
    *@param abreviatura (opcional)
    *@param cDocente (opcional)
    *@param codAsignatura (opcional)
    *@param creditos (opcional)
    *@param cPractica (opcional)
    *@param cAutonomo (opcional)
    *@param totalHoras (opcional)
    *@param estado (obligatorio)
    */
    public function insertOrUpdate(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $this->et = $this->validarData($request['evento'],$request);

            if (!$this->et->existeError) {
                $this->et = devolverConsulta($this->asigBll->insertOrUpdate($this->codUsuario));
            }
        } catch (\Exception $e) {
            $this->et->mensaje = 'Error: ' . get_class($this) . '->insertOrUpdate : ' . $e->getMessage();
            $this->et->existeError = true;
        }

        return response()->json($this->et);
    }

    private function validarData($evento, $request){
        try {
            switch (strtoupper($evento)) {
                case 'I':
                    $reglas = [
                        'evento'           => 'required',
                        'descripcion'      => 'required',
                        'estado'           => 'required|' . Rule::in(["A", "I"])
                    ];
                    break;
                case 'L':
                    $reglas = [
                        'evento'           => 'required',
                    ];
                    break;
                case 'U':
                    $reglas = [
                        'evento'           => 'required',
                        'idAsignatura'     => 'required',
                        'descripcion'      => 'required',
                        'estado'           => 'required|' . Rule::in(["A", "I"])
                    ];
                    break;
            }
            $validacion = Validator::make($request, $reglas);
            if ($validacion->fails()) {
                $this->et->mensaje = json_decode($validacion->messages(), true);
                $this->et->existeError = true;
            }

            return $this->et;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
