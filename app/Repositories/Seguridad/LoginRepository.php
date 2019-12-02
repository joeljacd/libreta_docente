<?php

namespace App\Repositories\Seguridad;

use App\Helpers\EstadoTransaccion;
use Illuminate\Support\Facades\DB;

class LoginRepository
{
	public function login($usrName, $usrPass){		
		try {
			$r = DB::select('CALL ACA_SEG_Login(?,?)', [
				$usrName,
				$usrPass
			]);
		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->login : ' . $e->getMessage(),EstadoTransaccion::$codeError);
		}
		return $r;
	}

	public function consultaMenu($codUsuario,$idRol){
		try {
			$r = DB::select('CALL ACA_SEG_Menu(?,?)', [
				$codUsuario,$idRol
			]);
			return $r;
		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->consultaMenu : ' . $e->getMessage(), EstadoTransaccion::$codeError);
		}
	}

	public function consultaAcciones($codEmpresa, $codPerfil){
		try {
			$r = DB::select('CALL ACA_SEG_ListarAcciones(?, ?)', [
				$codEmpresa,
				$codPerfil
			]);
		} catch (\Exception $e) {
			throw new \Exception(' : ' . get_class($this) . '->consultaAcciones : ' . $e->getMessage(), EstadoTransaccion::$codeError);
		}
		return $r;
	}
}