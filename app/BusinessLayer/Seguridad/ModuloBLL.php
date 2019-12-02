<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\ModuloRepository;
use Illuminate\Support\Facades\DB;

class ModuloBLL{
    
    private $idModulo,$idAgrupacion,$nombre,$icono,$idEstado;

    public function __construct($data = NULL){
        if(!empty($data)){
            $this->idModulo     = !empty($data['idModulo'])     ? $data['idModulo']     : NULL;
            $this->idAgrupacion = !empty($data['idAgrupacion']) ? $data['idAgrupacion'] : NULL;
            $this->nombre       = !empty($data['nombre'])       ? $data['nombre']       : NULL;
            $this->icono        = !empty($data['icono'])        ? $data['icono']        : NULL;
            $this->idEstado     = !empty($data['idEstado'])     ? $data['idEstado']     : NULL;
        }
    }

    public function listar(){
        try {
            $moduRepo =  new ModuloRepository();
            $r = $moduRepo->listar(
                                     $this->idModulo,
                                     $this->idAgrupacion == 'null' ? NULL : $this->idAgrupacion,
                                     $this->nombre,
                                     $this->icono,
                                     $this->idEstado
                                  );
            
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idModulo,$codUsuario){
        try {
            DB::beginTransaction();
                $moduRepo =  new ModuloRepository();
                $r = $moduRepo->insertarActualizar(
                                                    empty($idModulo) ? 'I' : 'U',
                                                    $idModulo,
                                                    $this->idAgrupacion,
                                                    $this->nombre,
                                                    $this->icono,
                                                    $this->idEstado,
                                                    $codUsuario
                                                  );
                return $r;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
