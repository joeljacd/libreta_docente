<?php

namespace App\Repositories\Mantenimiento\Academico;

use Illuminate\Support\Facades\DB;

class AsignaturaRepository {
    
    public function listar($evento,$idAsignatura,$nombre,$abreviatura,$cDocente,$codAsignatura,$creditos,$cPractica,$cAutonomo,$totalHoras,$estado,$codUsuario){
        try {
            $r =  DB::select( 'CALL ACA_MNT_Asignatura(?,?,?,?,?,?,?,?,?,?,?,?)',[
                $evento, $idAsignatura, $nombre, $abreviatura, $cDocente, $codAsignatura, $creditos, $cPractica, $cAutonomo, $totalHoras, $estado, $codUsuario
            ]);
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage());
        }

        return $r;
    }
    
    
    public function insertOrUpdate($evento,$idAsignatura,$nombre,$abreviatura,$cDocente,$codAsignatura,$creditos,$cPractica,$cAutonomo,$totalHoras,$estado,$codUsuario){
        try {
            $r =  DB::select( 'CALL ACA_MNT_Asignatura(?,?,?,?,?,?,?,?,?,?,?,?)',[
                $evento, $idAsignatura, $nombre, $abreviatura, $cDocente, $codAsignatura, $creditos, $cPractica, $cAutonomo, $totalHoras, $estado, $codUsuario
            ]);
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertOrUpdate : ' . $e->getMessage());
        }

        return $r;
    }
}
