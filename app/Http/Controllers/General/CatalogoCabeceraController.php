<?php
namespace App\Http\Controllers\General;

use App\AuthToken\JWToken;
use App\BusinessLayer\General\CatalogoCabeceraBLL;
use App\Helpers\EstadoTransaccion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CatalogoCabeceraController extends Controller{
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $cataBll;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->cataBll = new CatalogoCabeceraBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'General';
        $this->opcion = 'Cátalogo de Cabeceras';
    }

    /**
     * @param opcion (obligatorio)
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
}
