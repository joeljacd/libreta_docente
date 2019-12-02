<?php
namespace App\Http\Controllers\General;

use App\AuthToken\JWToken;
use App\BusinessLayer\General\CatalogoBLL;
use App\Helpers\EstadoTransaccion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class CatalogoController extends Controller{

    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $cataBll;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->cataBll = new CatalogoBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'General';
        $this->opcion = 'Parametro';
    }
    
    /**
     * @param opcion (obligatorio)
     * @param idSecCabCatalogo (opcional)
     * @param idSecDetCatalogo (opcional)
     * @param idEstado (opcional)
     **/
    public function listar(Request $request){
        try {
            if (!isset($request['opcion'])) throw new \Exception(EstadoTransaccion::$peticionError, EstadoTransaccion::$codeUnauthorized);
                $this->et = devolverConsulta($this->cataBll->listar());
                
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     * @param listaCabCatalogo type object (obligatorio) 
     * @param catalogo
     * @param descripcion
     * @param idEstado
     * @param listaDetCatalogo type object (obligatorio)
     * @param catalogo
     * @param descripcion
     * @param idEstado
     **/
    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'I'],$request));
            
            if (!$validacion->error) 
                $this->et = devolverConsulta($this->cataBll->insertarActualizar(null));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(),true,$e->getCode());
        }

        return response()->json($this->et,$this->et->status);
    }

    /**
     * @param listaCabCatalogo type object (obligatorio) 
     * @param idSecCatalogo
     * @param catalogo
     * @param descripcion
     * @param idEstado
     * @param listaDetCatalogo type object (obligatorio)
     * @param idSecCabCatalogo
     * @param idSecDetCatalogo
     * @param catalogo
     * @param descripcion
     * @param idEstado
     **/
    public function actualizar(Request $request, $idSecCabCatalogo){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge(['opcion' => 'U'],$request));

            if(!$validacion->error)
                $this->et = devolverConsulta($this->cataBll->insertarActualizar($idSecCabCatalogo));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }

        return response()->json($this->et, $this->et->status);
    }

    public function validarData($request){
        try {
            $reglas = [];
            switch (strtoupper($request['opcion'])) {
                case 'L':
                    $reglas = [
                        'opcion' => 'required'
                    ];
                    break;
                case 'I':
                    $reglas = [
                                'listaCabCatalogo'               => 'required',
                                'listaCabCatalogo.catalogo'      => 'required|String|max:50',
                                'listaCabCatalogo.descripcion'   => 'required|String|max:300',
                                'listaCabCatalogo.idEstado'      => 'required|Integer'
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'listaCabCatalogo'                  => 'required',
                                'listaCabCatalogo.idSecCabCatalogo' => 'required|Integer',
                                'listaCabCatalogo.catalogo'         => 'required|String|max:50',
                                'listaCabCatalogo.descripcion'      => 'required|String|max:300',
                                'listaCabCatalogo.idEstado'         => 'required|Integer'
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
