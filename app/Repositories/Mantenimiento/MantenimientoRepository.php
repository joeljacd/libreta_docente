<?php

namespace App\Repositories\Mantenimiento;

use Illuminate\Support\Facades\DB;

class MantenimientoRepository 
{
    public function listar($codParametro, $evento)
    {
        try {
            $r = DB::select('CALL ACA_CON_ListarMantenimientoAdministrador(?,?)', [
                $evento,
                $codParametro
            ]);

            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage());
        }
    }

    public function insertOrUpdate($evento,$id_parametro,$codUsuario,$name_table, $val_parametro,$abreviatura,$estado){
        try {
            $r = DB::select( 'CALL ACA_InsertarOrUpdateMantenimiento(?,?,?,?,?,?,?)',[
                $evento,
                $id_parametro,
                $codUsuario,
                $name_table,
                $val_parametro,
                $abreviatura,
                $estado
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : '.get_class($this).' ->update : '.$e->getMessage());
        }
    }
    
    
}
