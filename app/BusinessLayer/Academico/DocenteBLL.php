<?php
namespace App\BusinessLayer\Academico;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Academico\DocenteRepository;
use Illuminate\Support\Facades\DB;

class DocenteBLL{
    private $idDocente,$idPersona,$titulo,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->idDocente   = !empty($data['idDocente'])   ? $data['idDocente']   : NULL;
            $this->idPersona   = !empty($data['idPersona'])   ? $data['idPersona']   : NULL;
            $this->titulo      = !empty($data['titulo']   )   ? $data['titulo']      : NULL;
            $this->idEstado    = !empty($data['idEstado'])    ? $data['idEstado']    : NULL;
        }
    }

    public function listar(){
        try {
            $doceRepo = new DocenteRepository();
            $r = $doceRepo->listar($this->idDocente,$this->idPersona,$this->idEstado);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idDocente,$codUsuario){
        try {
            DB::beginTransaction();
                $doceRepo = new DocenteRepository();
                $r = $doceRepo->insertarActualizar(
                                                    empty($idDocente) ? 'I' : 'U',
                                                    $idDocente,
                                                    $this->idPersona,
                                                    $this->titulo,
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
