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
			->orderBy(DB::raw('su.id'))
			->get();
		
		return $this->sendResponse($result->toArray());
	}

	public function getChilds(Request $request, $id) {

		if ($id < 0) {
			$result = DB::table('licenses')
				->select(DB::raw('concat(\'l\', id) as id, concat(series, number, view) as name, 0 as amount'))
				->where('license_area', '=', -$id)
				->get();

			return $this->sendResponse($result->toArray(), '');
		}

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
			->orderBy(DB::raw('su.id'));

		$licenses = DB::table('licenses')
			->select(DB::raw('id, concat(series, number, view) as name, license_area'));

		$result = DB::table(DB::raw('license_areas la'))
			->select(DB::raw('-la.id as id, la.name, count(licenses.license_area) as amount'))
			->where('la.subsoil_user', '=', $id)
			->leftJoinSub($licenses, 'licenses', function($join) {
				$join->on('licenses.license_area', '=', 'la.id');
			})
			->groupBy(DB::raw('la.id, licenses.license_area'))
			->orderBy('la.subsoil_user')
			->union($companies)
			->get();

		return $this->sendResponse($result->toArray());
	}

	public function search(Request $request, $searchStr) {
		if (trim($searchStr) == '') 
			return $this->sendError('No search request was specified');

		$licenseAreaSearch = DB::table('license_areas')
			->select(DB::raw('cast(-id as varchar) as id,  subsoil_user as management_company'))
			->where('name', 'like', '%'.$searchStr.'%')
			->orderBy('subsoil_user');

		$licenseSearch = DB::table('licenses')
			->select(DB::raw('concat(\'l\', id) as id, license_area as management_company'))
			->whereRaw('concat(series, number, view) like \'%'.$searchStr.'%\'')
			->orderBy('license_area');

		$subsoilUsersSearch = DB::table('subsoil_users')
			->select(DB::raw('cast(id as varchar) as id, management_company'))
			->where('company', 'like', '%'.$searchStr.'%')
			->orderBy('management_company')
			->union($licenseAreaSearch)
			->union($licenseSearch)
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
				if (substr($currentEl->id, 0, 1) == 'l' ) {
					$currentEl = DB::table('license_areas')
						->select(DB::raw('id, subsoil_user as management_company'))
						->where('id', '=', $managementCompany)
						->get()[0];
					continue;
				}
				$currentEl = DB::table('subsoil_users')
					->select('id', 'management_company')
					->where('id', '=', $managementCompany)
					->get()[0];
			}
			$result[] = $temp;
			$temp = [];
		}

		return $this->sendResponse($result);
	}
}
