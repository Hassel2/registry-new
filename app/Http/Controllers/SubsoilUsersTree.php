<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubsoilUsersTree extends Controller
{
	public function getManagementCompanies(Request $request) {

		$result = DB::table('subsoil_users')
			->select('id', 'company')
			->joinSub(
				DB::table('subsoil_users')
					->select('management_company')
					->distinct(), 'mng_comp',
				function ($join) {
					$join->on('subsoil_users.id', '=', 'mng_comp.management_company');
				}
			)->get();	

		return $this->sendResponse($result->toArray(), 'Data retrived succesfully');
	}

	public function getChildCompanies(Request $request, $id) {

		$result = DB::table('subsoil_users')
			->select('id', 'company')
			->where('management_company', '=', $id)
			->get();	

		return $this->sendResponse($result->toArray(), 'Data retrived succesfully');
	}
}
