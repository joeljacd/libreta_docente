<?php
namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\AgrupacionRepository;
use Illuminate\Support\Facades\DB;

class AgrupacionBLL{
    
    private $idAgrupacion,$nombre,$icono,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->idAgrupacion = !empty($data['idAgrupacion']) ? $data['idAgrupacion'] :  NULL;
            $this->nombre       = !empty($data['nombre'])       ? $data['nombre']       :  NULL;
            $this->icono        = !empty($data['icono'])        ? $data['icono']        :  NULL;
            $this->idEstado     = !empty($data['idEstado'])     ? $data['idEstado']     :  NULL;
        }
    }

    public function listar(){
        try {
            $agruRepo = new AgrupacionRepository();
            $r = $agruRepo->listar($this->idAgrupacion,$this->nombre,$this->icono,$this->idEstado);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idAgrupacion,$codUsuario){
        try {
            DB::beginTransaction();
                $agruRepo = new AgrupacionRepository();
                $r = $agruRepo->insertarActualizar(
                                                    empty($idAgrupacion) ? 'I' : 'U',
                                                    $idAgrupacion,
                                                    $this->nombre,
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
