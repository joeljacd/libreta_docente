<?php
namespace App\Repositories\General;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class CatalogoCabeceraRepository{

    public function listar($opcion){
        try {
            $r = DB::select('CALL GNR_CON_Cabecera(?)',[ $opcion ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
