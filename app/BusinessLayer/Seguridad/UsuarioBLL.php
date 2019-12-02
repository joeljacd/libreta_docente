<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\UsuarioRepository;
use Illuminate\Support\Facades\DB;

class UsuarioBLL{

    private $opcion,$idUsuario,$idPersona,$clave,$token,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->opcion     = !empty($data['opcion'])     ? $data['opcion']     : NULL;
            $this->idUsuario  = !empty($data['idUsuario'])  ? $data['idUsuario']  : NULL;  
            $this->idPersona  = !empty($data['idPersona'])  ? $data['idPersona']  : NULL;  
            $this->clave      = !empty($data['clave'])      ? $data['clave']      : NULL;  
            $this->token      = !empty($data['token'])      ? $data['token']      : NULL;  
            $this->idEstado   = !empty($data['idEstado'])   ? $data['idEstado']   : NULL;  
        }    
    }

    public function listar(){
        try {
            $usuRepo = new UsuarioRepository();
            return $usuRepo->listar(
                                        $this->idUsuario,
                                        $this->idPersona,
                                        $this->clave,
                                        $this->token,
                                        $this->idEstado 
                                    );

        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idUsuario,$codUsuario){
        try {
            DB::beginTransaction();
                $usuRepo = new UsuarioRepository();
                $r = $usuRepo->insertarActualizar(
                                                    empty($idUsuario) ? 'I' : 'U',
                                                    $idUsuario,
                                                    $this->idPersona,
                                                    $this->clave,
                                                    $this->token,
                                                    $this->idEstado,
                                                    $codUsuario
                                                );
                if(!isset($r[0]->idUsuario)) throw new \Exception(!empty($idUsuario) ? EstadoTransaccion::$problemaActualizar : EstadoTransaccion::$problemaInsertar, EstadoTransaccion::$codeError);
            DB::commit();
            return $r;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
