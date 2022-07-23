<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubsoilUsersTree extends Controller
{
	public function getManagementCompanies(Request $request) {

		$license_areas = DB::table('license_areas')
			->select(DB::raw('id, name, subsoil_user as management_company'));

		$childs = DB::table('subsoil_users')
			->select(DB::raw('id, company as name, management_company'))
			->whereNotNull('management_company')
			->union($license_areas);

		$result = DB::table(DB::raw('subsoil_users su'))
			->select(DB::raw('su.id, company as name, count(childs.management_company) as amount'))
			->leftJoinSub($childs, 'childs', function ($join) {
				$join->on('childs.management_company', '=', 'su.id');
			})
			->whereNull('su.management_company')
			->groupBy(DB::raw('su.id, childs.management_company'))
			->orderBy(DB::raw('su.company'))
			->get();
		
		return $this->sendResponse($result->toArray(), 'companies');
	}

	public function getChildCompanies(Request $request, $id) {

		$license_areas = DB::table('license_areas')
			->select(DB::raw('id, name, subsoil_user as management_company'));

		$childs = DB::table('subsoil_users')
			->select(DB::raw('id, company as name, management_company'))
			->whereNotNull('management_company')
			->union($license_areas);


		$companies = DB::table(DB::raw('subsoil_users su'))
			->select(DB::raw('su.id, company as name, count(childs.management_company) as amount'))
			->leftJoinSub($childs, 'childs', function ($join) {
				$join->on('childs.management_company', '=', 'su.id');
			})
			->where('su.management_company', '=', $id)
			->groupBy(DB::raw('su.id, childs.management_company'))
			->orderBy(DB::raw('su.company'));

		$result = DB::table('license_areas')
			->select(DB::raw('-id as id, name, 0 as amount'))
			->where('subsoil_user', '=', $id)
			->union($companies)
			->get();

		return $this->sendResponse($result->toArray(), '');
	}

	public function search(Request $request, $searchStr) {
		if (trim($searchStr) == '') 
			return $this->sendError('No search request was specified');

		$licenseAreaSearch = DB::table('license_areas')
			->select(DB::raw('-id, subsoil_user as management_company'))
			->where('name', 'like', '%'.$searchStr.'%')
			->orderBy('subsoil_user');

		$subsoilUsersSearch = DB::table('subsoil_users')
			->select('id', 'management_company')
			->where('company', 'like', '%'.$searchStr.'%')
			->orderBy('management_company')
			->union($licenseAreaSearch)
			->get();

		$temp = [];
		$result = [];
		for ($i = 0; $i < count($subsoilUsersSearch); $i++) {
			$currentEl = $subsoilUsersSearch[$i];
			$temp[] = $currentEl->id;
			while (true) {
				$managementCompany = $currentEl->management_company;
				if ($managementCompany == null) break; 
				$temp[] = $managementCompany;
				$currentEl = DB::table('subsoil_users')
					->select('id', 'management_company')
					->where('id', '=', $managementCompany)
					->get()[0];
			}
			$result[] = $temp;
			$temp = [];
		}

		return $this->sendResponse($result, 'Data retrieved successfully');
	}
}
