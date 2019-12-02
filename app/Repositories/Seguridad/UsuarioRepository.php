<?php
namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class UsuarioRepository {
    public function listar($idUsuario,$idPersona, $clave, $token, $idEstado){
        try {
            $r = DB::select('CALL SEG_CON_Usuario(?,?,?,?,?)',[
                $idUsuario,$idPersona, $clave, $token, $idEstado
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($opcion, $idUsuario, $idPersona, $clave, $token, $idEstado,$codUsuario){
        try {
            $r = DB::select('CALL SEG_MNT_Usuario(?,?,?,?,?,?,?)',[
                $opcion, $idUsuario, $idPersona, $clave, $token, $idEstado,$codUsuario
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
