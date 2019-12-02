<?php

namespace App\Repositories\Mantenimiento\Academico;

use Illuminate\Support\Facades\DB;


class PeriodoRepository {
	
	public function listar($evento,$idPeriodo,$nombre,$abreviatura,$fechaInicio,$fechaFin,$estado,$codUsuario){
		try {
			return DB::select('CALL ACA_MNT_Periodo(?,?,?,?,?,?,?,?)',[
						$evento,$idPeriodo,$nombre,$abreviatura,$fechaInicio,$fechaFin,$estado,$codUsuario
				   ]);
		} catch (\Exception $e) {
			 throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage());
		}
	}

	public function insertOrUpdate($evento,$idPeriodo,$nombre,$abreviatura,$fechaInicio,$fechaFin,$estado,$codUsuario){
		try {
			return DB::select('CALL ACA_MNT_Periodo(?,?,?,?,?,?,?,?)',[
						$evento,$idPeriodo,$nombre,$abreviatura,$fechaInicio,$fechaFin,$estado,$codUsuario
				   ]);
		} catch (\Exception $e) {
			throw new \Exception(' : '.get_class($this).' ->insertOrUpdate : '.$e->getMessage());
		}
	}
}