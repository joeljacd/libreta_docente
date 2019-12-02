<?php

namespace App\Http\Controllers\Mantenimiento\Academico;
use App\Http\Controllers\Controller;
use App\Helpers\EstadoTransaccion;
use App\BusinessLayer\Mantenimiento\Academico\Periodo;
use App\AuthToken\JWToken;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;


class PeriodoController extends Controller
{
	private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $periBll;

	public function __construct(Request $request){
		$this->et = new EstadoTransaccion();
        $this->periBll = new Periodo($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'Mantenimiento';
        $this->opcion = 'Acádemico';
	}

	public function index(){
		try {
			$this->et = devolverConsulta($this->periBll->index($this->codUsuario));
		} catch (\Exception $e) {
			$this->et->mensaje = 'Error: ' . get_class($this) . '->index : ' . $e->getMessage();
            $this->et->existeError = true;	
		}
		return response()->json($this->et);
	}

	
	/**
	*@param evento (obligatorio)
	*@param id_periodo (obligatorio)
	*@param fecha_inicio (obligatorio)
	*@param fecha_fin (obligatorio)
	*@param estado (obligatorio)
	**/
	public function listar(){
		try {
			$this->et = $this->validarData($request['evento'],$request->all()); 
			if (!$this->et->existeError) {
				$this->et = devolverConsulta($this->periBll->listar($this->codUsuario));
			}

		} catch (\Exception $e) {
			$this->et->mensaje = 'Error: ' . get_class($this) . '->listar : ' . $e->getMessage();
            $this->et->existeError = true;		
		}

		return response()->json($this->et);
	}

	/**
	*@param evento (obligatorio)
	*@param id_periodo (obligatorio)
	*@param nombre (obligatorio)
	*@param abreviatura (opcional)
	*@param fecha_inicio (obligatorio)
	*@param fecha_fin (obligatorio)
	*@param estado (obligatorio)
	**/
	public function insertOrUpdate(Request $request){
		try {
			$this->et = $this->validarData($request['evento'],$request->all());
			if(!$this->et->existeError){
				$this->et = devolverConsulta($this->periBll->insertOrUpdate($this->codUsuario));
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
                        'nombre'           => 'required|String|max:100',
                        'abreviatura'      => 'String|max:20',
                        'fecha_inicio'     => 'required|String|max:20',
                        'fecha_fin'        => 'required|String|max:20',
                        'estado'           => 'required|' . Rule::in(["A", "I"])
                    ];
                    break;
                case 'L':
                    $reglas = [
                        'evento'           => 'required'
                    ];
                    break;
                case 'U':
                    $reglas = [
                        'evento'           => 'required',
                        'id_periodo'       => 'required|Integer',
                        'nombre'           => 'required|String|max:100',
                        'abreviatura'      => 'String|max:20',
                        'fecha_inicio'     => 'required|String|max:20',
                        'fecha_fin'        => 'required|String|max:20',
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