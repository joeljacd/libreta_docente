<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\RolRepository;
use Illuminate\Support\Facades\DB;

class RolBLL{
    private $idRol,$nombre,$abreviatura,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->idRol       = !empty($data['idRol'])       ? $data['idRol']       : NULL;
            $this->nombre      = !empty($data['nombre'])      ? $data['nombre']      : NULL;
            $this->abreviatura = !empty($data['abreviatura']) ? $data['abreviatura'] : NULL;
            $this->idEstado    = !empty($data['idEstado'])    ? $data['idEstado']    : NULL;
        }
    }

    public function listar(){
        try {
            $rolRepo = new RolRepository();
            $r = $rolRepo->listar($this->idRol,$this->nombre,$this->abreviatura,$this->idEstado);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idRol,$codUsuario){
        try {
            DB::beginTransaction();
                $rolRepo = new RolRepository();
                $r = $rolRepo->insertarActualizar(
                                                    empty($idRol) ? 'I' : 'U',
                                                    $idRol,
                                                    $this->nombre,
                                                    $this->abreviatura,
                                                    $this->idEstado,
                                                    $codUsuario
                                                 );
            DB::commit();
            return $r;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
