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
			
		foreach ($result as $subsoilUser) {
			$amount = DB::table('subsoil_users')
				->select(DB::raw('count(*) as amount'))
				->where('management_company', '=', $subsoilUser->id)
				->get()[0]
				->amount;
			
			if ($amount == 0)
				$amount = DB::table('license_areas')
					->select(DB::raw('count(*) as amount'))
					->where('subsoil_user', '=', $subsoilUser->id)
					->get()[0]
					->amount;
			
			$subsoilUser->amount = $amount;
		}

		return $this->sendResponse($result->toArray(), 'companies');
	}

	public function getChildCompanies(Request $request, $id) {

		$result = DB::table('subsoil_users')
			->select(DB::raw('id, company as name'))
			->where('management_company', '=', $id)
			->get();
			
		if (count($result) != 0) {
			foreach ($result as $subsoilUser) {
				$amount = DB::table('subsoil_users')
					->select(DB::raw('count(*) as amount'))
					->where('management_company', '=', $subsoilUser->id)
					->get()[0]
					->amount;
					
				if ($amount == 0)
					$amount = DB::table('license_areas')
						->select(DB::raw('count(*) as amount'))
						->where('subsoil_user', '=', $subsoilUser->id)
						->get()[0]
						->amount;

				$subsoilUser->amount = $amount;
			}
			
			return $this->sendResponse($result->toArray(), 'companies');
		}

		$result = DB::table('license_areas')
			->select(DB::raw('-id, name'))
			->where('subsoil_user', '=', $id)
			->get();

		return $this->sendResponse($result->toArray(), 'licenseAreas');
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
