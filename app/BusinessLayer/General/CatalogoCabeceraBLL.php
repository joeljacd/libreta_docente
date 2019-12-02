<?php
namespace App\BusinessLayer\General;

use App\Helpers\EstadoTransaccion;
use App\Repositories\General\CatalogoCabeceraRepository;

class CatalogoCabeceraBLL{
    private $opcion;

    public function __construct($data = NULL){
        if(!empty($data)){
            $this->opcion = $data['opcion'] ?? NULL;
        }
    }

    public function listar(){
        try {
            $cataRepo = new CatalogoCabeceraRepository();
            return $cataRepo->listar($this->opcion);
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->listar : ' . $e->getMessage(),EstadoTransaccion::$codeError);
        }
    }
}
