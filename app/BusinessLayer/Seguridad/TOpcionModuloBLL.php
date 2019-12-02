<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\TOpcionModuloRepository;
use Illuminate\Support\Facades\DB;

class TOpcionModuloBLL{
    private $idSecModuloOpcion,$idModulo,$idOpcion,$idEstado;

    public function __construct($data = NULL){
        if(!empty($data)){
            $this->idSecModuloOpcion = !empty($data['idSecModuloOpcion'])                        ? $data['idSecModuloOpcion'] : NULL;
            $this->idModulo          = !empty($data['idModulo']) && $data['idModulo'] != 'null'  ? $data['idModulo']          : NULL;
            $this->idOpcion          = !empty($data['idOpcion']) && $data['idOpcion'] != 'null'  ? $data['idOpcion']          : NULL;
            $this->idEstado          = !empty($data['idEstado'])                                 ? $data['idEstado']          : NULL;
        }
    }

    public function listar(){
        try {
            $opMoRepo = new TOpcionModuloRepository();
            $r = $opMoRepo->listar( 
                                    $this->idSecModuloOpcion,
                                    $this->idModulo,
                                    $this->idOpcion,
                                    $this->idEstado
                                  );
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idSecModuloOpcion,$codUsuario){
        try {
            DB::beginTransaction();
                $opMoRepo = new TOpcionModuloRepository();
                $r = $opMoRepo->insertarActualizar(
                                                     empty($idSecModuloOpcion) ? 'I' : 'U',
                                                     $idSecModuloOpcion,
                                                     $this->idModulo,
                                                     $this->idOpcion,
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
