<?php

namespace App\BusinessLayer\Mantenimiento\Academico;

use App\Repositories\Mantenimiento\Academico\AsignaturaRepository;
use App\Helpers\Catalogo;

class Asignatura {


    private $evento = 'C', 
            $idAsignatura, 
            $nombre, 
            $abreviatura, 
            $cDocente, 
            $codAsignatura, 
            $creditos, 
            $cPractica, 
            $cAutonomo, 
            $totalHoras, 
            $estado;


    public function __construct($data = NULL){
        if (sizeof($data) > 0) {
            $this->evento           = empty($data['evento'])        ? 'C' : $data['evento'];
            $this->idAsignatura     = empty($data[ 'idAsignatura']) ? NULL : $data[ 'idAsignatura'];
            $this->nombre           = empty($data['descripcion'])   ? NULL : $data['descripcion'];
            $this->abreviatura      = empty($data['abreviatura'])   ? NULL : $data['abreviatura'];
            $this->cDocente         = empty($data['cDocente'])      ? NULL : $data['cDocente'];
            $this->codAsignatura    = empty($data['codAsignatura']) ? NULL : $data['codAsignatura'];
            $this->creditos         = empty($data['creditos'])      ? NULL : $data['creditos'];
            $this->cPractica        = empty($data['cPractica'])     ? NULL : $data['cPractica'];
            $this->cAutonomo        = empty($data['cAutonomo'])     ? NULL : $data['cAutonomo'];
            $this->totalHoras       = empty($data['totalHoras'])    ? NULL : $data['totalHoras'];
            $this->estado           = empty($data['estado'])        ? NULL : $data['estado'];
        }
    }

    public function index($codUsuario){
        try {
            $asiRepo =  new AsignaturaRepository();

            $respuesta = Catalogo::getParametros('C', ['tipo_estado']);
            $cod_estado  = $respuesta["tipo_estado"][0]->codigo ?? NULL;
            $respuesta = Catalogo::getParametros('T', ['tipo_estado']);
            $listaEstado = $respuesta["tipo_estado"];

            $cabecera = $asiRepo->listar( $this->evento,
                                          $this->idAsignatura,
                                          $this->nombre,
                                          $this->abreviatura,
                                          $this->cDocente,
                                          $this->codAsignatura,
                                          $this->creditos,
                                          $this->cPractica,
                                          $this->cAutonomo,
                                          $this->totalHoras,
                                          $this->estado, 
                                          $codUsuario);

        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->index : ' . $e->getMessage());
        }

        return [ 'cod_estado' => $cod_estado, 'listaEstado' => $listaEstado, 'cabecera' => dataParaDatatable($cabecera)];
    }

    public function listar(){
        try {
            $asiRepo =  new AsignaturaRepository();

            $listAsignatura = $asiRepo->listar( $this->evento,
                                                $this->idAsignatura,
                                                $this->nombre,
                                                $this->abreviatura,
                                                $this->cDocente,
                                                $this->codAsignatura,
                                                $this->creditos,
                                                $this->cPractica,
                                                $this->cAutonomo,
                                                $this->totalHoras,
                                                $this->estado,
                                                null
                                            );
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage());
        }

        return $listAsignatura;
    }

    public function insertOrUpdate($codUsuario){
        try {
            $asiRepo =  new AsignaturaRepository();

            $array = $asiRepo->insertOrUpdate( $this->evento,
                                                $this->idAsignatura,
                                                $this->nombre,
                                                $this->abreviatura,
                                                $this->cDocente,
                                                $this->codAsignatura,
                                                $this->creditos,
                                                $this->cPractica,
                                                $this->cAutonomo,
                                                $this->totalHoras,
                                                $this->estado,
                                                $codUsuario
                                            );
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->insertOrUpdate : ' . $e->getMessage());
        }

        return $array;
    }
}
