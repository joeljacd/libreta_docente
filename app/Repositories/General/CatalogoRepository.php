<?php
namespace App\Repositories\General;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class CatalogoRepository{

    public function listar($opcion,$idSecCabCatalogo,$idSecDetCatalogo,$idEstado){
        try {
            $r = DB::select('CALL GNR_CON_Catalogo(?,?,?,?)',[ $opcion,$idSecCabCatalogo,$idSecDetCatalogo,$idEstado ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(), EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcionCab,$opcionDet,$idSecCatologo, $idSecCatologoDet,$catologo,$descripcion,$idEstado){
        try {
            $r = DB::select('CALL GNR_MNT_Catalogo(?,?,?,?,?,?,?)',[
                $opcionCab, $opcionDet, $idSecCatologo, $idSecCatologoDet, $catologo, $descripcion, $idEstado
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(), EstadoTransaccion::$codeError);
        }
    }
}
