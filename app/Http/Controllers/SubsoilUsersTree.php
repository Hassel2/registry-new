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

		return $this->sendResponse($result->toArray(), 'Data retrived succesfully');
	}

	public function getChildCompanies(Request $request, $id) {

		$result = DB::table('subsoil_users')
			->select(DB::raw('id, company as name'))
			->where('management_company', '=', $id)
			->get();	

		if (count($result) != 0) return $this->sendResponse($result->toArray(), 'Data retrived succesfully');

		$result = DB::table('license_areas')
			->select('id', 'name')
			->where('subsoil_user', '=', $id)
			->get();

		return $this->sendResponse($result->toArray(), 'Data retrived succesfully');
	}

	public function search(Request $request, $searchStr) {

		$subsouilUsersSearch = DB::table('subsoil_users')
			->select(DB::raw('id, company as name'))
			->where('company', 'like', '%'.$searchStr.'%');
			
		$result = DB::table('license_areas')
			->select('id', 'name')
			->where('name', 'like', '%'.$searchStr.'%')
			->union($subsouilUsersSearch)
			->get();

		return $this->sendResponse($result->toArray(), 'Data retriced successfully');
	}
}
