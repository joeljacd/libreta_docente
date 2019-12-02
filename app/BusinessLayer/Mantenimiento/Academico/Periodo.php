<?php
namespace App\BusinessLayer\Mantenimiento\Academico;
use App\Repositories\Mantenimiento\Academico\PeriodoRepository;
use App\Helpers\Catalogo;

class Periodo {
	private $evento = 'L';
	private $idPeriodo;
	private $nombre;
	private $abreviatura;
	private $fechaInicio;
	private $fechaFin;
	private $estado;

	function __construct($data = NULL){
		if (!empty($data)) {
			$this->evento       = !empty($data['evento']       ? $data['evento']       : 'L');
			$this->idPeriodo    = !empty($data['id_periodo']   ? $data['id_periodo']   : NULL);
			$this->nombre       = !empty($data['nombre']       ? $data['nombre']       : NULL);
			$this->abreviatura  = !empty($data['abreviatura']  ? $data['abreviatura']  : NULL);
			$this->fechaInicio  = !empty($data['fecha_inicio'] ? $data['fecha_inicio'] : NULL);
			$this->fechaFin     = !empty($data['fecha_fin']    ? $data['fecha_fin']    : NULL);
			$this->estado       = !empty($data['estado']       ? $data['estado']       : 'A');
		}
	}

	public function index(){
		try {
			$periRepo = new PeriodoRepository();

			$respuesta = Catalogo::getParametros('C', ['tipo_estado']);
            $cod_estado  = $respuesta["tipo_estado"][0]->codigo ?? NULL;
            $respuesta = Catalogo::getParametros('T', ['tipo_estado']);
			$listaEstado = $respuesta["tipo_estado"];
			$periRepo = new $periRepo->listar($this->evento,$this->idPeriodo,$this->nombre,$this->abreviatura,$this->fechaInicio,$this->fechaFin,$this->estado,null);
            

            return [ 'listaEstados' => $listaEstado , 'listaCebecera' => [] , 'listaDetalle' => []];

		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->index : ' . $e->getMessage());
		}	
	}

	public function listar($codUsuario)
	{
		try {
			$periRepo =  new PeriodoRepository();

			$listaPeriodos = $periRepo->listar($this->evento,
            	                               $this->idPeriodo,
            	                               $this->nombre,
            	                               $this->abreviatura,
            	                               $this->fechaInicio,
            	                               $this->fechaFin,
            	                               $this->estado,
            	                               $codUsuario
            	                              );

			return $listaPeriodos;
		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage());
		}
	}

	public function insertOrUpdate($codUsuario){
		try {
			$periRepo =  new PeriodoRepository();

			$listaPeriodos = $periRepo->insertOrUpdate($this->evento,
		            	                               $this->idPeriodo,
		            	                               $this->nombre,
		            	                               $this->abreviatura,
		            	                               $this->fechaInicio,
		            	                               $this->fechaFin,
		            	                               $this->estado,
		            	                               $codUsuario
            	                              			);

		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->insertOrUpdate : ' . $e->getMessage());	
		}
	}
}