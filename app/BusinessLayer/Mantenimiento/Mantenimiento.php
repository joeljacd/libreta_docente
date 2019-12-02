<?php
namespace App\BusinessLayer\Mantenimiento;

use App\Repositories\Mantenimiento\MantenimientoRepository;
use App\Repositories\General\OpcionesVariasRepository;
use App\Helpers\Catalogo;

class Mantenimiento
{
    private $manRepo ;
    private $evento;
    private $nameTable;
    private $idParametro;
    private $valParametro;
    private $abreviatura;
    private $estado;

    public function __construct($data=NULL)
    {
        $this->manRepo = new MantenimientoRepository();
        if ($data != NULL) {
            $this->evento         = empty($data['evento'])          ? NULL : $data['evento'];
            $this->nameTable      = empty($data['name'])            ? NULL : $data['name'];
            $this->idParametro    = empty($data['idParametro'])     ? NULL : $data['idParametro'];
            $this->valParametro   = empty($data['valParametro'])    ? NULL : $data['valParametro'];
            $this->abreviatura    = empty($data['abreviatura'])     ? NULL : $data['abreviatura'];
            $this->estado         = empty($data['estado'])          ? 'A'  : $data['estado'];
        }
    }

    public function index(){
        try {
            $respuesta = Catalogo::getParametros('C', ['tipo_estado']);

            $cod_estado  = $respuesta["tipo_estado"][0]->codigo ?? NULL;
            $listaEstado = Catalogo::getParametros('T', ['tipo_estado']);
            $cabecera    = $this->manRepo->listar(null,'C');
            return [
                    'cod_estado'   => $cod_estado, 
                    'cabecera'     => $cabecera,
                    'listaEstados' => $listaEstado['tipo_estado']
                    ];
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->index : ' . $e->getMessage());
        }
    }
    
    public function listar(){
        try {
            $respuesta = $this->manRepo->listar($this->nameTable,$this->evento);

            return $respuesta;        
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage());
        }
    }

    public function insertOrUpdate($codUsuario){
        try {
            $array = $this->manRepo->insertOrUpdate($this->evento,
                                                    $this->idParametro,
                                                    $codUsuario,
                                                    $this->nameTable,
                                                    $this->valParametro,
                                                    $this->abreviatura,
                                                    $this->estado);

            return $array;
        } catch (\Exception $e) {
            throw new \Exception(' : '.get_class($this). '->insertOrUpdate : '.$e->getMessage());
        }
    }

}
