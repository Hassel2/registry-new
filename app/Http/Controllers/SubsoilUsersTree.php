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
			->select(DB::raw('id, company as name, management_company'))
			->where('company', 'like', '%'.$searchStr.'%')
			->get();

		if (count($subsoilUsersSearch) == 0)
			return $this->sendResponse([], 'No data');

		$result = [];

		foreach ($subsoilUsersSearch as $subsoilUser) {
			if ($subsoilUser->management_company == null) {
				$result[] = [
					'id' => $subsoilUser->id,
					'name' => $subsoilUser->name,
				];
				continue;
			}
			$result[] = $this->getTree($subsoilUser->management_company, 
				[
					'id' => $subsoilUser->id,
					'name' => $subsoilUser->name,
				]);
		}

		return $this->sendResponse($result, 'Data retrieved successfully');
	}

	private function getTree($managementCompanyId, $currentCompany): array {

		$managementCompany = DB::table('subsoil_users')
			->select(DB::raw('id, company as name, management_company'))
			->where('id', '=', $managementCompanyId)
			->get()[0];

		/* $childs = DB::table('subsoil_users') */
		/* 	->select(DB::raw('id, company as name, management_company')) */
		/* 	->where('management_company', '=', $managementCompanyId) */
		/* 	->where('id', '!=', $currentCompany['id']) */
		/* 	->get(); */

		if ($managementCompany->management_company == null)
		{
			return [
				'id'     => $managementCompanyId, 
				'name'   => $managementCompany->name, 
				'nodes' => $currentCompany,
			];
		}

		return $this->getTree($managementCompany->management_company, 
			[
				'id'     => $managementCompanyId,
				'name'   => $managementCompany->name,
				'nodes'  => $currentCompany,
			]);
	}
}
