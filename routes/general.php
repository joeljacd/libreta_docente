<?php
// A partir de aquí las rutas necesitan el token para poder acceder a la información
$router->group(['middleware' => 'validarToken'], function () use ($router) {
    $router->group(['prefix' => 'api/'],function() use ($router){
        $router->group(['prefix' => 'general','namespace' => 'General'],function() use($router){
            /**
             *Catalogo
             **/
            $router->group(['prefix' => 'catalogo'], function () use ($router) {
                $router->get('listar', 'CatalogoController@listar');
                $router->post('insertar', 'CatalogoController@insertar');
                $router->put('actualizar/{idSecCabCatalogo}', 'CatalogoController@actualizar');
            });

            /**
             *Catalogo Cabecera Data Table
             **/
            $router->group(['prefix' => 'catalogo-cabecera'], function () use ($router) {
                $router->get('listar', 'CatalogoCabeceraController@listar');
            });
            
            /**
             *Parametros
             **/
            $router->group(['prefix' => 'parametro'], function () use ($router) {
                $router->get('listar[/{codParametro}]', 'ParametroController@listar');
                $router->post('insertar', 'ParametroController@insertar');
                $router->put('actualizar/{parametro}', 'ParametroController@actualizar');
            });

            /**
             *Persona
             **/
            $router->group(['prefix' => 'persona'], function () use ($router) {
                $router->get('listar[/{idPersona}]', 'PersonaController@listar');
                $router->post('insertar', 'PersonaController@insertar');
                $router->put('actualizar/{idPersona}', 'PersonaController@actualizar');
            });
       });
    });
});



