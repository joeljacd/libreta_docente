<?php

// A partir de aquí las rutas necesitan el token para poder acceder a la información
$router->group(['middleware' => 'validarToken'], function () use ($router) {
    $router->group(['prefix' => 'api/'], function () use ($router) {
        $router->group(['prefix' => 'mantenimiento', 'namespace' => 'Mantenimiento'], function () use ($router) {
            $router->get('general', 'GeneralController@index');
            $router->post('general/listar', 'GeneralController@listar');
            $router->post( 'general/insertOrUpdate', 'GeneralController@insertOrUpdate');

            $router->group(['prefix' => 'academico', 'namespace' => 'Academico'], function () use ($router) {
                /*********************************************************************
                 * ASIGNATURA                               
                 *********************************************************************/
                $router->get('asignatura', 'AsignaturaController@index');
                $router->post( 'asignatura/listar', 'AsignaturaController@listar');
                $router->post( 'asignatura/insertOrUpdate', 'AsignaturaController@insertOrUpdate');
                /*********************************************************************
                * PERÍODO
                ********************************************************************/
                $router->get('periodo', 'PeriodoController@index');
                $router->post( 'periodo/listar', 'PeriodoController@listar');
                $router->post( 'periodo/insertOrUpdate', 'PeriodoController@insertOrUpdate');
            });

        });
    });
});