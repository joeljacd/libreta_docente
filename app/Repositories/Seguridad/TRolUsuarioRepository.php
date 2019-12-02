<?php
namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class TRolUsuarioRepository{
    
    public function listar($idSecRolUsuario,$idusuario,$idRol,$idEstado){
        try {
            $r = DB::select('CALL SEG_CON_Rol_Usuario(?,?,?,?)',[
                $idSecRolUsuario,$idusuario,$idRol,$idEstado
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion, $idSecRolUsuario, $idUsuario, $idRol, $idEstado, $codUsuario){
        try {
            $r = DB::select('CALL SEG_MNT_Rol_Usuario(?,?,?,?,?,?)',[
                $opcion, $idSecRolUsuario, $idUsuario, $idRol, $idEstado, $codUsuario
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
