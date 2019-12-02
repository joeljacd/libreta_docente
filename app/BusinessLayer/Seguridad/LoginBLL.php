<?php

namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\LoginRepository;

class LoginBLL
{
	private $et;
	private $loginRepo;

	public function __construct(){
		$this->et = new EstadoTransaccion();
		$this->loginRepo = new LoginRepository();
	}

	/** 
	*@param usrName => username (obligatorio) 
	*@param usrPass => clave (obligatorio)
	**/
    public function userInfo($usrName, $usrPass){		
		try {		
			$r =  $this->loginRepo->login( $usrName, $usrPass);
			
			if(count($r) > 0){
	            $this->et->data = $r[0];
	        } else {
	            $this->et->error = true;
	            $this->et->mensaje = [ 'user' => 'Credenciales inválidas', ];
			}
		} catch (Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->userInfo : ' . $e->getMessage(),EstadoTransaccion::$codeError);
		}
		return $this->et;
	}
	
	 /** 
	 * Generar Menu con sus opciones
	*@param codUsuario  (obligatorio) 
	*/

	public function generaMenu($codUsuario,$idRol)
	{
		try {

			$r = $this->loginRepo->consultaMenu( $codUsuario,$idRol);
			
			if (count($r) == 0) {
				$this->et->error = true;
				$this->et->mensaje = [
					'user' => 'Error al cargar el menú'
				];
			} else {
				$r = json_decode(json_encode($r),true);
				$l = [];

				$menu = collect($r);
				$menu = $menu->groupBy( 'Agrupacion_logica');
				$menu = $menu->map(function ($item, $key) {
					return $item->groupBy( 'Nombre_modulo');
				});
				
				foreach ($menu->toArray() as $key => $value) {
					$l[] =  [
								'header' => $key,
								'icon'   => $value[array_keys($value)[0]][0]['iconoAgrupacion'],
								'i18n'   => $key,
								'items'  => devolverSubMenu($value,$key)
							];
				}
				
				$this->et->data = $l;
			}
		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->generaMenu : ' . $e->getMessage(),EstadoTransaccion::$codeError);
		}
		return $this->et;
	}

	public function generaAcciones($codEmpresa, $codPerfil)
	{
		try {

			$r = $this->loginRepo->consultaAcciones($codEmpresa, $codPerfil);
			if ($r[0]->valido == 0) {
				$this->et->error = true;
				$this->et->mensaje = [
					'user' => 'Error al cargar las acciones'
				];
			} else {
				$acciones =  collect($r);

				$acciones = $acciones->groupBy('Nombre_modulo');

				$acciones = $acciones->map(function ($item, $key) {
					return $item->groupBy('Nombre_opcion');
				});

				$acciones = $acciones->map(function ($item, $key) {
					return $item->map(function ($item, $key) {
						$nombreAccion = ($item->implode('Nombre_accion', ','));
						return $item->map(function ($item, $key) use ($nombreAccion) {
							$item->{'Nombre_accion'} = $nombreAccion;
							$item = collect($item)->forget(['Nombre_modulo', 'Nombre_opcion', 'valido']); //Eliminar de cada subnodo final estas keys
							return $item;
						});
					});
				});
				$this->et->data = $acciones->map(function ($item, $key) {
					return $item->map(function ($item, $key) {
						return ($item->unique());
					});
				});
			}
		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->generaAcciones : ' . $e->getMessage(),EstadoTransaccion::$codeError);
		}
		return $this->et;
	}
}