<?php
namespace App\Http\Controllers\Seguridad;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\AuthToken\JWToken;
use App\BusinessLayer\Seguridad\LoginBLL;
use Validator;

class LoginController extends Controller{

    private $et;
    private $codUsuario;
    private $modulo;
    private $opcion;
    private $loginBll;

    public function __construct(){
        $this->et = new EstadoTransaccion();
        $this->modulo = 'seguridad';
        $this->opcion = 'login';
        $this->loginBll = new LoginBLL();
    }
  
    public function login(Request $request)
    {
        try {
            if(empty($request->all())) throw new \Exception(EstadoTransaccion::$faltaParametros,EstadoTransaccion::$codeUnauthorized);
                $request = json_decode($request->getContent(), true);
                $objJWToken = new JWToken();
            
                $this->et = $this->validarData($request);
                
                if($this->et->error) {
                    throw new \Exception("Error de validación de datos",EstadoTransaccion::$codeUnauthorized);
                }
                
                $usrName          = $request['usr_name'];
                $usrPass          = $request['usr_pass'];

                $usrPass = $this->cifrarPassword($usrName, $usrPass);
                
                $this->et = $this->loginBll->userInfo($usrName, $usrPass);
                
                if($this->et->error) {
                    throw new \Exception('Error controlado. Generado desde un repository o una BLL',EstadoTransaccion::$codeError);
                }

                $userInfo = $this->et->data;

                $codPersona = $userInfo->cod_persona;
                $this->codUsuario = $userInfo->cod_usuario;

                unset($userInfo->cod_usuario);
                unset($userInfo->cod_persona);

                $this->et = $this->loginBll->generaMenu($this->codUsuario, $userInfo->idRol);

                $menu = $this->et->data;

                $token = $objJWToken->generarToken(1, $this->codUsuario);
            
                $this->et->mensaje = EstadoTransaccion::$consultaExitosa;
                $this->et->data =  [    'Token'       => $token,
                                        'Informacion' => $userInfo,
                                        'Menu'        => $menu
                                    ];
        } catch (\Exception $e) {
            $this->et = devolverError(get_class($this),$e->getMessage(),true,$e->getCode());
        }
        return response()->json($this->et,$this->et->status);
    }

    private function cifrarPassword($usrName, $usrPass){
        // $cod_clave       = hash('sha256', $usrPass);
        // $cod_clave       = substr($cod_clave, 3, 40) . substr($cod_clave, 11, 30);
        // $cod_user        = hash('sha256', $usrName);
        // $cod_clave_final = $cod_clave . $cod_user;
        // $usrPass         = hash('sha256', $cod_clave_final);

        return hash('sha256',$usrPass);
    }

    private function validarData($request){
        $this->et = new EstadoTransaccion();
        $reglas = [
            'usr_name'    => 'required|max:255',
            'usr_pass'    => 'required|max:20',
        ];

        $validacion = Validator::make($request, $reglas);

        $errores = '';
        if ($validacion->fails()){
            $this->et->error = true;
            foreach ($validacion->messages()->all() as $mensaje) {
                $errores .= $mensaje . '<br/>';
            }
            $this->et->mensaje = [
                'user' => $errores,
            ];
            // $this->et->mensaje = $validacion->messages();
            // $this->et->mensaje = $validacion->messages()->all();
        }
        return $this->et; 
    }
}
