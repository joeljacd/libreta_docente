<?php

namespace App\Helpers;

use App\Repositories\Catalogo\CatalogoRepository;

class Catalogo {

    public static function getParametros($evento,$array){
        try {
            $data = [];
            $catalogoRepo = new CatalogoRepository();
            foreach ($array as $key => $value) {
                $data[$value] =  $catalogoRepo->listar($evento,$value,null);
            }
        } catch (\Exception $e) {
            throw new \Exception(' : ' . get_class($this) . '->index : ' . $e->getMessage());
        }

        return $data;
    }    
}
