<?php

namespace App\Repositories\Catalogo;

use Illuminate\Support\Facades\DB;

class CatalogoRepository {
    
    public function listar( $evento,$val_parametro,$cod_parametro) {
        try {
            $r =  DB::select( 'CALL ACA_CON_ListarOpcionesTablasVarias(?,?,?)',[
                $evento, $val_parametro, $cod_parametro]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception( ' : ' . get_class($this) . ' ->update : ' . $e->getMessage());
        }
    }
}
