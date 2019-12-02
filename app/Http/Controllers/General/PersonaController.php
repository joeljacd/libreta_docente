<?php
namespace App\Http\Controllers\General;

use App\AuthToken\JWToken;
use App\BusinessLayer\General\PersonaBLL;
use App\Helpers\EstadoTransaccion;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Validator;

class PersonaController extends Controller{
    
    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $perBLL;

    public function __construct(Request $request){
        $this->et = new EstadoTransaccion();
        $this->perBLL = new PersonaBLL($request->all());
        $userInfo = JWToken::userInfo($request);
        $this->codUsuario = $userInfo->useid;
        $this->modulo = 'General';
        $this->opcion = 'Persona';
    }

    /** 
     * @param idPersona (opcional)
    */
    public function listar($idPersona = NULL){
        try {
            $this->et = devolverConsulta($this->perBLL->listar($idPersona));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(), true, $e->getCode());
        }
        return response()->json($this->et, $this->et->status);
    }

    /**
     *@param opcion (obligatorio) 
     *@param idTipoDocumento (obligatorio) 
     *@param nroidentificacion (obligatorio) 
     *@param primerNombre (obligatorio) 
     *@param segundoNombre (opcional) 
     *@param primerApellido (obligatorio) 
     *@param segundoApellido (opcional) 
     *@param fechaNacimiento (opcional) 
     *@param telefono (opcional) 
     *@param celular (opcional) 
     *@param direccion (opcional) 
     *@param emailPersonal (opcional) 
     *@param emailInstitucional (opcional) 
     *@param idEstadoCivil (opcional) 
     *@param idGenero (obligatorio) 
     *@param idPais (opcional) 
     *@param idProvincia (opcional) 
     *@param idCanton (opcional) 
     *@param idDiscapacidad (opcional) 
     *@param porcDiscapacidad (opcional) 
     **/

    public function insertar(Request $request){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData(array_merge($request,['opcion'=> 'I']));
            if (!$validacion->error) 
                $this->et = devolverConsulta($this->perBLL->insertarActualizar(null, $this->codUsuario));
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this), $e->getMessage(),true,$e->getCode());
        }

        return response()->json($this->et,$this->et->status);
    }

    /**
     *@param opcion (obligatorio) 
     *@param idPersona (obligatorio) 
     *@param idTipoDocumento (obligatorio) 
     *@param nroidentificacion (obligatorio) 
     *@param primerNombre (obligatorio) 
     *@param segundoNombre (opcional) 
     *@param primerApellido (obligatorio) 
     *@param segundoApellido (opcional) 
     *@param fechaNacimiento (opcional) 
     *@param telefono (opcional) 
     *@param celular (opcional) 
     *@param direccion (opcional) 
     *@param emailPersonal (opcional) 
     *@param emailInstitucional (opcional) 
     *@param idEstadoCivil (opcional) 
     *@param idGenero (obligatorio) 
     *@param idPais (opcional) 
     *@param idProvincia (opcional) 
     *@param idCanton (opcional) 
     *@param idDiscapacidad (opcional) 
     *@param porcDiscapacidad (opcional) 
     **/

    public function actualizar(Request $request, $idPersona){
        try {
            $request = json_decode($request->getContent(),true);
            $validacion = $this->validarData($request = array_merge($request, ['opcion' => 'U','idPersona' => $idPersona]));
            if(!$validacion->error)
                $this->et = devolverConsulta($this->perBLL->insertarActualizar($idPersona));
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
                                'idTipoDocumento'   => 'required|Integer',
                                'nroidentificacion' => 'required|String|max:10',
                                'primerNombre'      => 'required|String|max:100',
                                'primerApellido'    => 'required|String|max:100',
                                'idGenero'          => 'required|Integer',
                              ];
                    break;
                case 'U':
                    $reglas = [
                                'idPersona'         => 'required|Integer',
                                'idTipoDocumento'   => 'required|Integer',
                                'nroidentificacion' => 'required|String|max:10',
                                'primerNombre'      => 'required|String|max:100',
                                'primerApellido'    => 'required|String|max:100',
                                'idGenero'          => 'required|Integer',
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
