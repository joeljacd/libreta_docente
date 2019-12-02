<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\TRolUsuarioRepository;
use Illuminate\Support\Facades\DB;

class TRolUsuarioBLL {
    
    private $idSec, $idUsuario, $idRol, $idEstado;

    public function __construct($data = NULL){
        if(!empty($data)){
            $this->idSec       = !empty($data['index'])      ? $data['index']      : NULL;
            $this->idUsuario   = !empty($data['idUsuario'])  ? $data['idUsuario']  : NULL;
            $this->idRol       = !empty($data['idRol'])      ? $data['idRol']      : NULL;
            $this->idEstado    = !empty($data['idEstado'])   ? $data['idEstado']   : NULL;
        }
    }

    public function listar(){
        try {
            $rolUsuRepo = new TRolUsuarioRepository();
            $r = $rolUsuRepo->listar($this->idSec,$this->idUsuario,$this->idRol,$this->idEstado);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idSecRolUsuario,$codUsuario){
        try {
            DB::beginTransaction();
                $rolUsuRepo = new TRolUsuarioRepository();
                $r = $rolUsuRepo->insertarActualizar(
                                                        empty($idSecRolUsuario) ? 'I' : 'U',
                                                        $idSecRolUsuario,
                                                        $this->idUsuario,
                                                        $this->idRol,
                                                        $this->idEstado,
                                                        $codUsuario
                                                    );

                if(!isset($r[0]->idSecRolUsuario)) throw new \Exception(!empty($idSecRolUsuario) ? EstadoTransaccion::$problemaActualizar : EstadoTransaccion::$problemaInsertar, EstadoTransaccion::$codeError);
            DB::commit();
            return $r;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
