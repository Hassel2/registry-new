<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubsoilUsersTree extends Controller
{
	public function getManagementCompanies(Request $request) {

		$result = DB::table('subsoil_users')
			->select(DB::raw('id, company as name'))
			->whereRaw('management_company is null')
			->get();	

		return $this->sendResponse($result->toArray(), 'companies');
	}

	public function getChildCompanies(Request $request, $id) {

		$result = DB::table('subsoil_users')
			->select(DB::raw('id, company as name'))
			->where('management_company', '=', $id)
			->get();	

		if (count($result) != 0) return $this->sendResponse($result->toArray(), 'companies');

		$result = DB::table('license_areas')
			->select('id', 'name')
			->where('subsoil_user', '=', $id)
			->get();

		return $this->sendResponse($result->toArray(), 'licenseAreas');
	}

	public function search(Request $request, $searchStr) {

		$subsouilUsersSearch = DB::table('subsoil_users')
			->select(DB::raw('id, company as name'))
			->where('company', 'like', '%'.$searchStr.'%')
			->get();
			
		$licenseAreasSearch = DB::table('license_areas')
			->select('id', 'name')
			->where('name', 'like', '%'.$searchStr.'%')
			->get();

		$result = [
			'companies' => $subsouilUsersSearch,
			'licenseAreas' => $licenseAreasSearch,
		];

		return $this->sendResponse($result, 'Data retrieved successfully');
	}
}
