<?php
namespace App\Repositories\General;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class PersonaRepository{
    
    public function listar($idPersona){
        try {
            $r = DB::select('CALL GNR_CON_Persona(?)',[
                $idPersona
            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar(
                                        $opcion,$idPersona,$idTipoDocumento,$nroidentificacion,$primerNombre,
                                        $segundoNombre,$primerApellido,$segundoApellido,$fechaNacimiento,$idTipoSangre,
                                        $telefono,$celular,$direccion,$emailPersonal,$emailInstitucional,
                                        $idEstadoCivil,$idGenero,$idPais,$idProvincia,$idCanton,
                                        $idDiscapacidad,$porcDiscapacidad
                                      )
    {
        try {
            $r = DB::select('CALL GNR_MNT_Persona(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[
                                                    $opcion,$idPersona,$idTipoDocumento,$nroidentificacion,$primerNombre,
                                                    $segundoNombre,$primerApellido,$segundoApellido,$fechaNacimiento,$idTipoSangre,
                                                    $telefono,$celular,$direccion,$emailPersonal,$emailInstitucional,
                                                    $idEstadoCivil,$idGenero,$idPais,$idProvincia,$idCanton,
                                                    $idDiscapacidad,$porcDiscapacidad
                            ]);
            return $r;
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
