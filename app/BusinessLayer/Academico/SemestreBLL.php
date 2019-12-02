<?php
namespace App\BusinessLayer\Academico;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Academico\SemestreRepository;
use Illuminate\Support\Facades\DB;

class SemestreBLL{
    private $idSemestre,$nombre,$codigo,$abreviatura,$idEstado;

    public function __construct($data = NULL){
        if(!empty($data)){
            $this->idSemestre  = !empty($data['idSemestre'])  ? $data['idSemestre']  : NULL;
            $this->nombre      = !empty($data['nombre'])      ? $data['nombre']      : NULL;
            $this->codigo      = !empty($data['codigo'])      ? $data['codigo']      : NULL;
            $this->abreviatura = !empty($data['abreviatura']) ? $data['abreviatura'] : NULL;
            $this->idEstado    = !empty($data['idEstado'])    ? $data['idEstado']    : NULL;
        }
    }

    public function listar(){
        try {
            $semeRepo = new SemestreRepository();
            $r = $semeRepo->listar(
                                    $this->idSemestre,
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

    public function insertarActualizar($idSemestre,$codUsuario){
        try {
            DB::beginTransaction();
                $semeRepo = new SemestreRepository();
                $r = $semeRepo->insertarActualizar(
                                                    empty($idSemestre) ? 'I' : 'U',
                                                    $idSemestre,
                                                    $this->codigo,
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
