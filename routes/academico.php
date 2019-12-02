<?php

$router->group(['middleware' => 'validarToken'], function () use ($router) {
    $router->group(['prefix' => 'api/'],function() use ($router){
        $router->group(['prefix' => 'academico','namespace' => 'Academico'],function() use($router){
            
            /**
             * Mantenimiento Docente  
             **/ 
            $router->group(['prefix' => 'docente'],function() use($router){
                $router->get('listar','DocenteController@listar');
                $router->post('insertar','DocenteController@insertar');
                $router->put('actualizar/{idDocente}','DocenteController@actualizar');
            });
            
            /**
             * Mantenimiento Facultad  
             **/ 
            $router->group(['prefix' => 'facultad'],function() use($router){
                $router->get('listar','FacultadController@listar');
                $router->post('insertar','FacultadController@insertar');
                $router->put('actualizar/{idFacultad}','FacultadController@actualizar');
            });
            
            /**
             * Mantenimiento Carrera  
             **/ 
            $router->group(['prefix' => 'carrera'],function() use($router){
                $router->get('listar','CarreraController@listar');
                $router->post('insertar','CarreraController@insertar');
                $router->put('actualizar/{idCarrera}','CarreraController@actualizar');
            });
            
            /**
             * Mantenimiento Semestre
             **/ 
            $router->group(['prefix' => 'semestre'],function() use($router){
                $router->get('listar','SemestreController@listar');
                $router->post('insertar','SemestreController@insertar');
                $router->put('actualizar/{idSemestre}','SemestreController@actualizar');
            });
            
            /**
             * Mantenimiento Jornada
             **/ 
            $router->group(['prefix' => 'jornada'],function() use($router){
                $router->get('listar','JornadaController@listar');
                $router->post('insertar','JornadaController@insertar');
                $router->put('actualizar/{idJornada}','JornadaController@actualizar');
            });
        });
    });
});