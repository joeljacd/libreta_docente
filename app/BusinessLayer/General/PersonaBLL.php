<?php
namespace App\BusinessLayer\General;

use App\Helpers\EstadoTransaccion;
use App\Repositories\General\PersonaRepository;
use Illuminate\Support\Facades\DB;

class PersonaBLL{
    private $idTipoDocumento,$nroidentificacion,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido,$fechaNacimiento,$idTipoSangre,$telefono;
    private $celular,$direccion,$emailPersonal,$emailInstitucional,$idEstadoCivil,$idGenero,$idPais,$idProvincia,$idCanton,$idDiscapacidad,$porcDiscapacidad;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->idTipoDocumento     = !empty($data['idTipoDocumento'])    ? $data['idTipoDocumento']    : NULL;
            $this->nroidentificacion   = !empty($data['nroidentificacion'])  ? $data['nroidentificacion']  : NULL;
            $this->primerNombre        = !empty($data['primerNombre'])       ? $data['primerNombre']       : NULL;
            $this->segundoNombre       = !empty($data['segundoNombre'])      ? $data['segundoNombre']      : NULL;
            $this->primerApellido      = !empty($data['primerApellido'])     ? $data['primerApellido']     : NULL;
            $this->segundoApellido     = !empty($data['segundoApellido'])    ? $data['segundoApellido']    : NULL;
            $this->fechaNacimiento     = !empty($data['fechaNacimiento'])    ? $data['fechaNacimiento']    : NULL;
            $this->idTipoSangre        = !empty($data['idTipoSangre'])       ? $data['idTipoSangre']       : NULL;
            $this->telefono            = !empty($data['telefono'])           ? $data['telefono']           : NULL;
            $this->celular             = !empty($data['celular'])            ? $data['celular']            : NULL;
            $this->direccion           = !empty($data['direccion'])          ? $data['direccion']          : NULL;
            $this->emailPersonal       = !empty($data['emailPersonal'])      ? $data['emailPersonal']      : NULL;
            $this->emailInstitucional  = !empty($data['emailInstitucional']) ? $data['emailInstitucional'] : NULL;
            $this->idEstadoCivil       = !empty($data['idEstadoCivil'])      ? $data['idEstadoCivil']      : NULL;
            $this->idGenero            = !empty($data['idGenero'])           ? $data['idGenero']           : NULL;
            $this->idPais              = !empty($data['idPais'])             ? $data['idPais']             : NULL;
            $this->idProvincia         = !empty($data['idProvincia'])        ? $data['idProvincia']        : NULL;
            $this->idCanton            = !empty($data['idCanton'])           ? $data['idCanton']           : NULL;
            $this->idDiscapacidad      = !empty($data['idDiscapacidad'])     ? $data['idDiscapacidad']     : NULL;
            $this->porcDiscapacidad    = !empty($data['porcDiscapacidad'])   ? $data['porcDiscapacidad']   : NULL;
        }
    }

    public function listar($idPersona){
        try {
            $perRepo = new PersonaRepository();
            return $perRepo->listar($idPersona);

        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idPersona){
        try {
            DB::beginTransaction();
                $perRepo = new PersonaRepository();
                $r = $perRepo->insertarActualizar(
                                                    empty($idPersona) ? 'I' : 'U',
                                                    $idPersona,
                                                    $this->idTipoDocumento,
                                                    $this->nroidentificacion,
                                                    $this->primerNombre,
                                                    $this->segundoNombre,
                                                    $this->primerApellido,
                                                    $this->segundoApellido,
                                                    $this->fechaNacimiento,
                                                    $this->idTipoSangre,
                                                    $this->telefono,
                                                    $this->celular,
                                                    $this->direccion,
                                                    $this->emailPersonal,
                                                    $this->emailInstitucional,
                                                    $this->idEstadoCivil,
                                                    $this->idGenero,
                                                    $this->idPais,
                                                    $this->idProvincia,
                                                    $this->idCanton,
                                                    $this->idDiscapacidad,
                                                    $this->porcDiscapacidad
                                                 );
                if(!isset($r[0]->idPersona)) throw new \Exception(!empty($idPersona) ? EstadoTransaccion::$problemaActualizar : EstadoTransaccion::$problemaInsertar, EstadoTransaccion::$codeError);
            DB::commit();
            return $r;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
