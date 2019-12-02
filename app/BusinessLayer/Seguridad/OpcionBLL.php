<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\OpcionRepository;
use Illuminate\Support\Facades\DB;

class OpcionBLL{
    private $idOpcion,$nombre,$opcion,$ruta,$icono,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->opcion    = !empty($data['opcion'])   ? $data['opcion']   : NULL;
            $this->idOpcion  = !empty($data['idOpcion']) ? $data['idOpcion'] : NULL;
            $this->nombre    = !empty($data['nombre'])   ? $data['nombre']   : NULL;
            $this->ruta      = !empty($data['ruta'])     ? $data['ruta']     : NULL;
            $this->icono     = !empty($data['icono'])    ? $data['icono']    : NULL;
            $this->idEstado  = !empty($data['idEstado']) ? $data['idEstado'] : NULL;
        }
    }

    public function listar(){
        try {
            $opRepo = new OpcionRepository();
            $r = $opRepo->listar(
                                    $this->idOpcion,
                                    $this->nombre,
                                    $this->ruta,
                                    $this->icono,
                                    $this->idEstado
                                );

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idOpcion,$codUsuario){
        try {
            DB::beginTransaction();
                $opRepo = new OpcionRepository();
                $r = $opRepo->insertarActualizar(
                                                    empty($idOpcion) ? 'I' : 'U',
                                                    $idOpcion,
                                                    $this->nombre,
                                                    $this->ruta,
                                                    $this->icono,
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
