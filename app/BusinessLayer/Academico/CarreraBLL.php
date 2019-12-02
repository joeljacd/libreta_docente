<?php
namespace App\BusinessLayer\Academico;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Academico\CarreraRepository;
use Illuminate\Support\Facades\DB;

class CarreraBLL{
    private $idCarrera,$nombre,$codigo,$abreviatura,$idEstado;

    public function __construct($data = NULL){
        if(!empty($data)){
            $this->idCarrera   = !empty($data['idCarrera'])   ? $data['idCarrera']   : NULL;
            $this->nombre      = !empty($data['nombre'])      ? $data['nombre']      : NULL;
            $this->codigo      = !empty($data['codigo'])      ? $data['codigo']      : NULL;
            $this->abreviatura = !empty($data['abreviatura']) ? $data['abreviatura'] : NULL;
            $this->idEstado    = !empty($data['idEstado'])    ? $data['idEstado']    : NULL;
        }
    }

    public function listar(){
        try {
            $caRepo = new CarreraRepository();
            $r = $caRepo->listar(
                                    $this->idCarrera,
                                    $this->nombre,
                                    $this->codigo,
                                    $this->abreviatura,
                                    $this->idEstado
                                );

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idCarrera,$codUsuario){
        try {
            DB::beginTransaction();
                $caRepo = new CarreraRepository();
                $r = $caRepo->insertarActualizar(
                                                    empty($idCarrera) ? 'I' : 'U',
                                                    $idCarrera,
                                                    $this->nombre,
                                                    $this->codigo,
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
