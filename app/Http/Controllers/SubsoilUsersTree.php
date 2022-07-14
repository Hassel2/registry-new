<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipStream\Bigint;

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
			->select('id', 'name')
			->where('subsoil_user', '=', $id)
			->get();

		return $this->sendResponse($result->toArray(), 'licenseAreas');
	}

	public function search(Request $request, $searchStr) {
		if (trim($searchStr) == '') 
			return $this->sendError('No search request was specified');

		$subsoilUsersSearch = DB::table('subsoil_users')
			->select('id', 'management_company')
			->where('company', 'like', '%'.$searchStr.'%')
			->orderBy('management_company')
			->get();

		$temp = [];
		$result = [];
		for ($i = 0; $i < count($subsoilUsersSearch) - 1; $i++) {
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

	/* public function search(Request $request, $searchStr) { */

	/* 	if (trim($searchStr) == '') */ 
	/* 		return $this->sendError('No search request was specified'); */

	/* 	$subsoilUsersSearch = DB::table('subsoil_users') */
	/* 		->select(DB::raw('id, company as name, management_company')) */
	/* 		->where('company', 'like', '%'.$searchStr.'%') */
	/* 		->orderBy('management_company') */
	/* 		->get(); */

	/* 	if (count($subsoilUsersSearch) == 0) */
	/* 		return $this->sendResponse([], 'No data found'); */

	/* 	$result = []; */

	/* 	foreach ($subsoilUsersSearch as $subsoilUser) { */
	/* 		if ($subsoilUser->management_company == null) { */
	/* 			$result[] = [ */
	/* 				'id' => $subsoilUser->id, */
	/* 				'name' => $subsoilUser->name, */
	/* 			]; */
	/* 			continue; */
	/* 		} */
	/* 		$result[] = $this->getTree($subsoilUser->management_company, */ 
	/* 			[ */
	/* 				'id' => $subsoilUser->id, */
	/* 				'name' => $subsoilUser->name, */
	/* 			]); */
	/* 	} */

	/* 	$temp = []; */
	/* 	$newres = []; */
	/* 	for ($i = 0; $i < count($result) - 1; $i++) { */
	/* 		$temp[] = $result[$i]; */
	/* 		if ($result[$i + 1]['id'] != $result[$i]['id']) { */
	/* 			$newres[] = call_user_func_array("array_merge_recursive", $temp); */
	/* 			$temp = []; */
	/* 		} */
	/* 	} */

	/* 	return $this->sendResponse($newres, 'Data retrieved successfully'); */
	/* } */

	private function getTree($managementCompanyId, $currentCompany): array {

		$managementCompany = DB::table('subsoil_users')
			->select(DB::raw('id, company as name, management_company'))
			->where('id', '=', $managementCompanyId)
			->get()[0];

		if ($managementCompany->management_company == null)
		{
			return [
				'id'    => $managementCompanyId, 
				'name'  => $managementCompany->name, 
				'nodes' => $currentCompany,
			];
		}

		return $this->getTree($managementCompany->management_company, 
			[
				'id'    => $managementCompanyId,
				'name'  => $managementCompany->name,
				'nodes' => $currentCompany,
			]);
	}
}
