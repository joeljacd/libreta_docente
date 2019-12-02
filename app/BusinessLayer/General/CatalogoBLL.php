<?php
namespace App\BusinessLayer\General;

use App\Helpers\EstadoTransaccion;
use App\Repositories\General\CatalogoRepository;
use Illuminate\Support\Facades\DB;

class CatalogoBLL{
    private $opcion,$listaCabCatalogo,$listaDetCatalogo,$idSecCabCatalogo,$idSecDetCatalogo,$catalogo,$idEstado;

    public function __construct($data = NULL){
        if (!empty($data)) {
            $this->opcion             = !empty($data['opcion'])           ? $data['opcion']           : NULL;
            $this->idSecCabCatalogo   = !empty($data['idSecCabCatalogo']) ? $data['idSecCabCatalogo'] : NULL;
            $this->idSecDetCatalogo   = !empty($data['idSecDetCatalogo']) ? $data['idSecDetCatalogo'] : NULL;
            $this->catalogo           = !empty($data['catalogo'])         ? $data['catalogo']         : NULL;
            $this->idEstado           = !empty($data['idEstado'])         ? $data['idEstado']         : NULL;
            $this->listaCabCatalogo   = !empty($data['listaCabCatalogo']) ? $data['listaCabCatalogo'] : [];
            $this->listaDetCatalogo   = !empty($data['listaDetCatalogo']) ? $data['listaDetCatalogo'] : [];
        }
    }

    public function listar(){
        try {
            $cataRepo = new CatalogoRepository();
            return $cataRepo->listar($this->opcion,$this->idSecCabCatalogo,$this->idSecDetCatalogo,$this->idEstado);
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

    public function insertarActualizar($idSecCabCatalogo){
        try {
            DB::beginTransaction();
                $cataRepo = new CatalogoRepository();
                $peticion = [];
                    $r =  $cataRepo->insertarActualizar(
                                                            'CAB',
                                                            !isset($this->listaCabCatalogo['idSecCabCatalogo']) ? 'I' : 'U',
                                                            $this->listaCabCatalogo['idSecCabCatalogo'] ?? NULL,
                                                            null,
                                                            $this->listaCabCatalogo['catalogo'],
                                                            $this->listaCabCatalogo['descripcion'],
                                                            $this->listaCabCatalogo['idEstado']
                                                        );

                if(!isset($r[0]->filas_afectadas)) throw new Exception($this->opcionDet == 'I' ? EstadoTransaccion::$problemaInsertar : EstadoTransaccion::$problemaActualizar,EstadoTransaccion::$codeError);
                    $peticion[] = [
                                    'opcion'          => 'catalogo cabecera', 
                                    'filas_afectadas' => $r[0]->filas_afectadas,
                                    'mensaje'         => $r[0]->mensaje
                                  ];
                    $idSecCabCatalogo = $r[0]->idSecCatalogo;
                    

                    foreach ($this->listaDetCatalogo as $v) {
                        $r =  $cataRepo->insertarActualizar(
                                                                'DET',
                                                                !isset($v['idSecDetCatalogo']) ? 'I' : 'U',
                                                                $idSecCabCatalogo,
                                                                $v['idSecDetCatalogo'] ?? NULL,
                                                                $v['catalogo'],
                                                                $v['descripcion'],
                                                                $v['idEstado']
                                                            );

                        if (!isset($r[0]->filas_afectadas)) throw new Exception('', EstadoTransaccion::$codeError);
                            $peticion[] = [
                                            'opcion'          => 'catalogo detalle', 
                                            'filas_afectadas' => $r[0]->filas_afectadas,
                                            'mensaje'         => $r[0]->mensaje
                                          ];
                    }
            DB::commit();
            return $peticion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(' : ' . get_class($this) . '->insertarActualizar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }

}
