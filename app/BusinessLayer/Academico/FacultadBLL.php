<?php
namespace App\BusinessLayer\Academico;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Academico\FacultadRepository;
use Illuminate\Support\Facades\DB;

class FacultadBLL{
    private $idFacultad,$codigo,$nombre,$abreviatura,$idEstado;

    public function __construct($data = NULL){
        if(!empty($data)){
            $this->idFacultad  = !empty($data['idFacultad'])  ? $data['idFacultad']  : NULL;
            $this->codigo      = !empty($data['codigo'])      ? $data['codigo']      : NULL;
            $this->nombre      = !empty($data['nombre'])      ? $data['nombre']      : NULL;
            $this->abreviatura = !empty($data['abreviatura']) ? $data['abreviatura'] : NULL;
            $this->idEstado    = !empty($data['idEstado'])    ? $data['idEstado']    : NULL;
        }
    }

    public function listar(){
        try {
            $facuRepo = new FacultadRepository();
            $r = $facuRepo->listar($this->idFacultad,$this->codigo,$this->nombre,$this->abreviatura,$this->idEstado);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idFacultad,$codUsuario){
        try {
            DB::beginTransaction();
                $facuRepo = new FacultadRepository();   
                $r = $facuRepo->insertarActualizar(
                                                    empty($idFacultad) ? 'I' : 'U',
                                                    $idFacultad,
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
