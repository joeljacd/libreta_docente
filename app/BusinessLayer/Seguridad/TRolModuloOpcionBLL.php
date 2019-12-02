<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\TRolModuloOpcionRepository;
use Illuminate\Support\Facades\DB;

class TRolModuloOpcionBLL{
    
    private $opcion,$idSec,$idRol,$idModuloOpcion,$idOpcion,$idModulo,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->opcion         = !empty($data['opcion'])                                     ? $data['opcion']         : NULL;
            $this->idSec          = !empty($data['idSec'])                                      ? $data['idSec']          : NULL;
            $this->idRol          = !empty($data['idRol'])     && $data['idRol'] != 'null'      ? $data['idRol']          : NULL;
            $this->idModulo       = !empty($data['idModulo'])  && $data['idModulo'] != 'null'   ? $data['idModulo']       : NULL;
            $this->idOpcion       = !empty($data['idOpcion'])  && $data['idOpcion'] != 'null'   ? $data['idOpcion']       : NULL;
            $this->idModuloOpcion = !empty($data['idModuloOpcion'])                             ? $data['idModuloOpcion'] : NULL;
            $this->idEstado       = !empty($data['idEstado'])                                   ? $data['idEstado']       : NULL;
        }
    }

    public function listar(){
        try {
            $rolModOpRepo = new TRolModuloOpcionRepository();
            $r = $rolModOpRepo->listar(
                                        $this->idSec,
                                        $this->idRol,
                                        $this->idModuloOpcion,
                                        $this->idModulo,
                                        $this->idOpcion,
                                        $this->idEstado
                                    );

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idSec,$codUsuario){
        try {
            DB::beginTransaction();
                $rolModOpRepo = new TRolModuloOpcionRepository();
                $r = $rolModOpRepo->insertarActualizar(
                                                    empty($idSec) ? 'I' : 'U',
                                                    $idSec,
                                                    $this->idRol,
                                                    $this->idModuloOpcion,
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
