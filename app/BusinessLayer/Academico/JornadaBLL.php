<?php
namespace App\BusinessLayer\Academico;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Academico\JornadaRepository;
use Illuminate\Support\Facades\DB;

class JornadaBLL{
    
    private $opcion,$idJornada,$codigo,$nombre,$abreviatura,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->opcion      = !empty($data['opcion'])      ? $data['opcion']      : NULL;
            $this->idJornada   = !empty($data['idJornada'])   ? $data['idJornada']   : NULL;
            $this->codigo      = !empty($data['codigo'])      ? $data['codigo']      : NULL;
            $this->nombre      = !empty($data['nombre'])      ? $data['nombre']      : NULL;
            $this->abreviatura = !empty($data['abreviatura']) ? $data['abreviatura'] : NULL;
            $this->idEstado    = !empty($data['idEstado'])    ? $data['idEstado']    : NULL;
        }
    }

    public function listar(){
        try {
            $jorRepo = new JornadaRepository();
            $r = $jorRepo->listar(
                                    $this->opcion,
                                    $this->codigo,
                                    $this->nombre,
                                    $this->abreviatura,
                                    $this->idEstado
                                 );

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idJornada,$codUsuario){
        try {
            DB::beginTransaction();
                $jorRepo = new JornadaRepository();
                $r = $jorRepo->insertarActualizar(
                                                    empty($idJornada) ? 'I' : 'U',
                                                    $idJornada,
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
