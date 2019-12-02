<?php
namespace App\Repositories\General;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class ParametroRepository {
    
    public function listar($codParametro){
        try {
            $r = DB::select('CALL GNR_CON_Parametro(?)',[
                $codParametro
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion, $idSecParametro, $codParametro, $descripcion, $valorNumerico, $valorTexto, $idEstado, $codUsuario){
        try {
            $r = DB::select('CALL GNR_MNT_Parametro(?,?,?,?,?,?,?,?)',[
                $opcion, $idSecParametro, $codParametro, $descripcion, $valorNumerico, $valorTexto, $idEstado, $codUsuario
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
