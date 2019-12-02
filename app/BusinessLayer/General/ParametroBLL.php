<?php
namespace App\BusinessLayer\General;

use App\Helpers\EstadoTransaccion;
use App\Repositories\General\ParametroRepository;
use Illuminate\Support\Facades\DB;

class ParametroBLL{

    private $opcion, $idSecParametro,$codParametro,$descripcion,$valorNumerico,$valorTexto,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->opcion          = !empty($data['opcion'])         ? $data['opcion']         : NULL;
            $this->idSecParametro  = !empty($data['idSecParametro']) ? $data['idSecParametro'] : NULL;
            $this->codParametro    = !empty($data['codParametro'])   ? $data['codParametro']   : NULL;
            $this->descripcion     = !empty($data['descripcion'])    ? $data['descripcion']    : NULL;
            $this->valorNumerico   = !empty($data['valorNumerico'])  ? $data['valorNumerico']  : NULL;
            $this->valorTexto      = !empty($data['valorTexto'])     ? $data['valorTexto']     : NULL;
            $this->idEstado        = !empty($data['idEstado'])       ? $data['idEstado']       : NULL;
        }
    }

    public function listar($codParametro){
        try {
            $paraRepo = new ParametroRepository();
            return $paraRepo->listar($codParametro);

        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idSecParametro,$codUsuario){
        try {
            DB::beginTransaction();
                $paraRepo = new ParametroRepository();
                $r = $paraRepo->insertarActualizar($this->opcion,$idSecParametro,$this->codParametro,$this->descripcion,$this->valorNumerico,$this->valorTexto,$this->idEstado,$codUsuario);
                if(!isset($r[0]->idSecParametro)) throw new \Exception(!empty($idSecParametro) ? EstadoTransaccion::$problemaActualizar : EstadoTransaccion::$problemaInsertar, EstadoTransaccion::$codeError);
            DB::commit();
            return $r;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
