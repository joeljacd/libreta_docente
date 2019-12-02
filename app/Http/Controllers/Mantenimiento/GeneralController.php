<?php

namespace App\Http\Controllers\Mantenimiento;
use App\Helpers\EstadoTransaccion;
use App\AuthToken\JWToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessLayer\Mantenimiento\Mantenimiento;
use Validator;
use Illuminate\Validation\Rule;

class GeneralController extends Controller{

    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $manteBll;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->manteBll = new Mantenimiento($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Mantenimiento';
        $this->opcion = 'Administrador';
    }

    public function index(){
        try {
            $this->et->data = $this->manteBll->index();
            $this->et->mensaje = EstadoTransaccion::$procesoExitoso;
        } catch (\Exception $e) {
            $this->et->mensaje = 'Error: ' . get_class($this) . '->index : ' . $e->getMessage();
            $this->et->existeError = true;
        }
        
        return response()->json($this->et);
    }

    /**
     * @param evento (obligatorio)
     * @param name (obligatorio)
     */
    public function listar(Request $request)
    {
        try {
            $request = json_decode($request->getContent(), true);
            $validacion = $this->validarData($request['evento'], $request);
            if (count($validacion) > 0) {
                $this->et->mensaje = $validacion;
                $this->et->existeError =  true;
            }else{
                $this->et = devolverConsulta($this->manteBll->listar());
            }
        } catch (\Exception $e) {
            $this->et->mensaje = 'Error: ' . get_class($this) . '->listar : ' . $e->getMessage();
            $this->et->existeError = true;
        }
        return response()->json($this->et);
    }

    /**
     *@param evento (obligatorio)
     *@param name (obligatorio)
     *@param idParametro (obligatorio en el evento U)
     *@param valParametro (obligatorio)
     *@param abreviatura (opcional)
     *@param estado (obligatorio en evento U) 
    */
    public function insertOrUpdate(Request $request) {
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData($request['evento'],$request);
            if (count( $validacion) > 0) {
                $this->et->mensaje = $validacion;
                $this->et->existeError =  true;
            }else {
                $this->et = devolverConsulta($this->manteBll->insertOrUpdate($this->codUsuario));
            }
        } catch (\Exception $e) {
            $this->et->mensaje = 'Error: ' . get_class($this) . '->insert : ' . $e->getMessage();
            $this->et->existeError = true;
        } 
        return response()->json($this->et);
    }

    private function validarData($evento,$request){
        try {
            $msg = [];
            switch (strtoupper( $evento)) {
                case 'I':
                    $reglas = [
                        'evento'           => 'required',
                        'name'             => 'required|String',
                        'valParametro'     => 'required|max:255',
                        'estado'           => 'required|' . Rule::in(["A", "I"])
                    ];
                    break;
                case 'L':
                    $reglas = [
                        'evento'           => 'required',
                        'name'             => 'required'
                    ];
                    break;
                case 'U':
                    $reglas = [
                        'evento'           => 'required',
                        'name'             => 'required|String',
                        'idParametro'      => 'required',
                        'valParametro'     => 'required|max:255',
                        'estado'           => 'required|' . Rule::in(["A", "I"])
                    ];
                    break;
            }
            $validacion = Validator::make($request, $reglas);
            if ($validacion->fails()) {
                $msg = json_decode($validacion->messages(), true);
            }
            return $msg;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        } 
    }
}
