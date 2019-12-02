<?php 


$router->group(['prefix' => 'api/'],function() use ($router){
    $router->group(['namespace' => 'Seguridad'],function() use ($router){
        $router->get('logout','LoginController@logout');
        $router->post('login','LoginController@login');
        $router->post('update-information', 'UpdateInformationUserController@updateInformationUser');
    });
});

$router->group(['middleware' => 'validarToken'], function () use ($router) {
    $router->group(['prefix' => 'api/'],function() use ($router){
        $router->get('/', function () use ($router) {
            return $router->app->version();
        });
        $router->group(['prefix' => 'seguridad','namespace' => 'Seguridad'],function() use($router){
            
            /**
             * Mantenimiento Usuario  
             **/ 
            $router->group(['prefix' => 'usuario'],function() use($router){
                $router->get('listar','UsuarioController@listar');
                $router->post('insertar','UsuarioController@insertar');
                $router->put('actualizar/{idUsuario}','UsuarioController@actualizar');
            });
            
            /**
             * Mantenimiento Rol
             **/ 
            $router->group(['prefix' => 'rol'],function() use($router){
                $router->get('listar','RolController@listar');
                $router->post('insertar','RolController@insertar');
                $router->put('actualizar/{idRol}','RolController@actualizar');
            });

            /**
             * Mantenimiento Rol - Usuario  
             **/ 
            $router->group(['prefix' => 'rol-usuario'],function() use($router){
                $router->get('listar','TRolUsuarioController@listar');
                $router->post('insertar','TRolUsuarioController@insertar');
                $router->put('actualizar/{idSecRolUsuario}','TRolUsuarioController@actualizar');
            });
            
            /**
             * Mantenimiento Opción  
             **/ 
            $router->group(['prefix' => 'opcion'],function() use($router){
                $router->get('listar','OpcionController@listar');
                $router->post('insertar','OpcionController@insertar');
                $router->put('actualizar/{idOpcion}','OpcionController@actualizar');
            });

            /**
             * Mantenimiento Módulo  
             **/ 
            $router->group(['prefix' => 'modulo'],function() use($router){
                $router->get('listar','ModuloController@listar');
                $router->post('insertar','ModuloController@insertar');
                $router->put('actualizar/{idModulo}','ModuloController@actualizar');
            });
            
            /**
             * Mantenimiento Opción - Módulo  
             **/ 
            $router->group(['prefix' => 'opcion-modulo'],function() use($router){
                $router->get('listar','TOpcionModuloController@listar');
                $router->post('insertar','TOpcionModuloController@insertar');
                $router->put('actualizar/{idSecModuloOpcion}','TOpcionModuloController@actualizar');
            });
            
            /**
             * Mantenimiento Rol - Módulo - Opción
             **/ 
            $router->group(['prefix' => 'rol-modulo-opcion'],function() use($router){
                $router->get('listar','TRolModuloOpcionController@listar');
                $router->post('insertar','TRolModuloOpcionController@insertar');
                $router->put('actualizar/{idSec}','TRolModuloOpcionController@actualizar');
            });
            
            /**
             * Mantenimiento Agrupación  
             **/ 
            $router->group(['prefix' => 'agrupacion'],function() use($router){
                $router->get('listar','AgrupacionController@listar');
                $router->post('insertar','AgrupacionController@insertar');
                $router->put('actualizar/{idAgrupacion}','AgrupacionController@actualizar');
            });
        });
    });
});
